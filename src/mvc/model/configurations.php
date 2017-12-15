<?php
/** @var $model \bbn\mvc\model */

// DB connection
$db =& $model->data['db'];

// Get all configurations
if ( !empty($db) && \count($model->data) === 1 ){
  return [];
  $confs = $db->rselect_all('configurations');
  foreach ( $confs as $i => $conf ){
    $config = json_decode($conf['config'], 1);
    foreach ( $config as $k => $c ){
      if ( $c['ver_id'] !== 'latest' ){
        $config[$k]['ver_name'] = $db->select_one('versions', 'name', ['id' => $c['ver_id']]);
      }
      else {
        $config[$k]['ver_name'] = $db->select_one('libraries', 'latest', ['name' => $c['lib_name']]);
      }
      $confs[$i]['config'] = json_encode($config);
    }
    return $confs;
  }


}

// Add new configuration
else if ( !empty($db) &&
  (\count($model->data) > 2) &&
  !empty($model->data['hash']) &&
  !empty($model->data['config'])
){
  unset($model->data['db']);
  $db->insert('configurations', $model->data);
}

// Update configuration
else if ( !empty($db) &&
  (\count($model->data) > 2) &&
  !empty($model->data['hash']) &&
  !empty($model->data['new_hash']) &&
  !empty($model->data['config'])
){
  $id = $model->data['hash'];
  $model->data['hash'] = $model->data['new_hash'];
  unset($model->data['db']);
  unset($model->data['new_hash']);
  if ( $db->update('configuration', $model->data, ['hash' => $id]) ){
    return $model->data;
  }
  return false;
}

// Delete configuration
else if ( !empty($db) &&
  (\count($model->data) === 2) &&
  !empty($model->data['hash'])
){
  if ( $db->delete('configurations', ['hash' => $model->data['hash']]) ){
    return ['success' => 1];
  }
}