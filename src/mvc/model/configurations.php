<?php
/** @var $this \bbn\mvc\controller */

// DB connection
$db =& $this->data['db'];

// Get all configurations
if ( !empty($db) && count($this->data) === 1 ){
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
  (count($this->data) > 2) &&
  !empty($this->data['hash']) &&
  !empty($this->data['config'])
){
  unset($this->data['db']);
  $db->insert('configurations', $this->data);
}

// Update configuration
else if ( !empty($db) &&
  (count($this->data) > 2) &&
  !empty($this->data['hash']) &&
  !empty($this->data['new_hash']) &&
  !empty($this->data['config'])
){
  $id = $this->data['hash'];
  $this->data['hash'] = $this->data['new_hash'];
  unset($this->data['db']);
  unset($this->data['new_hash']);
  if ( $db->update('configuration', $this->data, ['hash' => $id]) ){
    return $this->data;
  }
  return false;
}

// Delete configuration
else if ( !empty($db) &&
  (count($this->data) === 2) &&
  !empty($this->data['hash'])
){
  if ( $db->delete('configurations', ['hash' => $this->data['hash']]) ){
    return ['success' => 1];
  }
}