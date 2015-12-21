<?php
/** @var $this \bbn\mvc\controller */

// DB connection
$db =& $this->data['db'];

// Get all libraries
if ( !empty($db) && count($this->data) === 1 ){
  return $db->get_rows("
    SELECT libraries.*, licences.name AS licence
    FROM libraries
    LEFT JOIN licences
      ON libraries.licence = licences.licence
    ORDER BY title COLLATE NOCASE ASC
  ");
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
  !empty($this->data['status']) &&
  ( !empty($this->data['files']) ||
    !empty($this->data['languages']) ||
    !empty($this->data['themes'])
  )
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
    'lat_update' => date('Y-m-d H:i:s', time()),
    'last_check' => date('Y-m-d H:i:s', time())
  ]) ){
    $content = false;
    if ( !empty($this->data['file']) ){
      $content['files'] = json_decode($this->data['file']);
    }
    if ( !empty($this->data['languages']) ){
      $content['languages'] = json_decode($this->data['languages']);
    }
    if ( !empty($this->data['themes']) ){
      $content['themes'] = json_decode($this->data['themes']);
    }
    if ( !empty($content) ){
      $db->insert('versions', [
        'name' => $this->data['vname'],
        'library' => $this->data['name'],
        'content' => json_encode($this->data['content']),
        'status' => $this->data['status'],
        'date_added' => date('Y-m-d H:i:s', time())
      ]);
    }
  }

}