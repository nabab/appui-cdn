<?php
/** @var $model \bbn\mvc\model */

// DB connection
$db =& $model->data['db'];

// Get all libraries
if ( !empty($db) && count($model->data) === 1 ){
  return $db->rselect_all('libraries', [], [], ['title' => 'ASC']);
}

// Get all library's info
else if ( !empty($db) && (count($model->data) === 2) && !empty($model->data['id_lib']) ){
  $ret = [];
  $ret['versions'] = $model->get_model('./versions');
  return $ret;
}

// Insert new library
else if ( !empty($db) &&
  !empty($model->data['name']) &&
  !empty($model->data['title']) &&
  !empty($model->data['vname']) &&
  ( !empty($model->data['files']) ||
    !empty($model->data['languages']) ||
    !empty($model->data['themes']) ) &&
  empty($model->data['edit'])
){
  if ( $db->insert('libraries', [
    'name' => $model->data['name'],
    'fname' => $model->data['fname'],
    'title' => $model->data['title'],
    'latest' => $model->data['vname'],
    'website' => $model->data['website'],
    'author' => $model->data['author'],
    'licence' => $model->data['licence'],
    'download_link' => $model->data['download_link'],
    'doc_link' => $model->data['doc_link'],
    'git' => $model->data['git'],
    'support_link' => $model->data['support_link'],
    'last_update' => date('Y-m-d H:i:s', time()),
    'last_check' => date('Y-m-d H:i:s', time())
  ]) ){
    $ver = $model->get_model('./versions');
    
    if ( !empty($ver['success']) ){
      return $db->get_rows("
        SELECT *
        FROM libraries
        ORDER BY title COLLATE NOCASE ASC
      ");
    }
    return ['error' => _("Error to add new library's version.")];
  }
  return false;
}

// Update library
else if ( !empty($db) &&
  !empty($model->data['name']) &&
  !empty($model->data['new_name']) &&
  !empty($model->data['title']) &&
  !empty($model->data['edit'])
){
  $id = $model->data['name'];
  $model->data['name'] = $model->data['new_name'];
  unset($model->data['db']);
  unset($model->data['new_name']);
  unset($model->data['edit']);
  $columns = $db->get_columns('libraries');
  $change = [];
  foreach ( $model->data as $n => $v ){
    if ( isset($columns[$n]) ){
      $change[$n] = $v;
    }
  }
  if ( $db->update('libraries', $change, ['name' => $id]) ){
    return $model->data;
  }
  return false;
}

// Delete library
else if ( !empty($db) &&
  !empty($model->data['name'])
){
  if ( $db->delete('libraries', ['name' => $model->data['name']]) ){
    // Get all library's versions' id
    $versions = $db->rselect_all('versions', ['id'], ['library' => $model->data['name']]);
    foreach ( $versions as $ver ){
      // Delete versions
      $db->delete('versions', ['id' => $ver['id']]);
      // Delete dependecies
      $db->delete('dependencies', ['id_slave' => $ver['id']]);
      $db->delete('dependencies', ['id_master' => $ver['id']]);
    }
    return ['success' => 1];
  }
}