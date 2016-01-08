<?php
/** @var $this \bbn\mvc\controller */

// DB connection
$db =& $this->data['db'];

// Get all libraries
if ( !empty($db) && count($this->data) === 1 ){
  return $db->rselect_all('libraries', [], [], ['title' => 'ASC']);
}

// Get all library's info
else if ( !empty($db) && (count($this->data) === 2) && !empty($this->data['id_lib']) ){
  $ret = [];
  $ret['versions'] = $this->get_model('./versions');
  return $ret;
}

// Insert new library
else if ( !empty($db) &&
  (count($this->data) > 2) &&
  !empty($this->data['name']) &&
  !empty($this->data['title']) &&
  !empty($this->data['vname']) &&
  ( !empty($this->data['files']) ||
    !empty($this->data['languages']) ||
    !empty($this->data['themes']) ) &&
  empty($this->data['edit'])
){
  if ( $db->insert('libraries', [
    'name' => $this->data['name'],
    'fname' => $this->data['fname'],
    'title' => $this->data['title'],
    'latest' => $this->data['vname'],
    'website' => $this->data['website'],
    'author' => $this->data['author'],
    'licence' => $this->data['licence'],
    'download_link' => $this->data['download_link'],
    'doc_link' => $this->data['doc_link'],
    'git' => $this->data['git'],
    'support_link' => $this->data['support_link'],
    'last_update' => date('Y-m-d H:i:s', time()),
    'last_check' => date('Y-m-d H:i:s', time())
  ]) ){
    $ver = $this->get_model('./versions');
    if ( !empty($ver['success']) ){
      return $db->get_rows("
        SELECT *
        FROM libraries
        ORDER BY title COLLATE NOCASE ASC
      ");
    }
    return ['error' => "Error to add new library's version."];
  }
  return false;
}

// Update library
else if ( !empty($db) &&
  (count($this->data) > 2) &&
  !empty($this->data['name']) &&
  !empty($this->data['new_name']) &&
  !empty($this->data['title']) &&
  !empty($this->data['edit'])
){
  $id = $this->data['name'];
  $this->data['name'] = $this->data['new_name'];
  unset($this->data['db']);
  unset($this->data['new_name']);
  unset($this->data['edit']);
  if ( $db->update('libraries', $this->data, ['name' => $id]) ){
    return $this->data;
  }
  return false;
}

// Delete library
else if ( !empty($db) &&
  (count($this->data) === 2) &&
  !empty($this->data['name'])
){
  if ( $db->delete('libraries', ['name' => $this->data['name']]) ){
    // Get all library's versions' id
    $versions = $db->rselect_all('versions', ['id'], ['library' => $this->data['name']]);
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