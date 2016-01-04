<?php
/** @var $this \bbn\mvc\controller */

// DB connection
$db =& $this->data['db'];

if ( !empty($this->data['id_lib']) ){
  // Get all library's versions
  $versions =  $db->get_rows("
    SELECT *
    FROM versions
    WHERE library = ?
    ORDER BY date_added DESC",
    $this->data['id_lib']
  );

  foreach( $versions as $i => $ver ){
    // Get all versions' dependencies
    $versions[$i]['dependencies'] = $db->get_rows("
      SELECT libraries.title, libraries.name, versions.name AS version
      FROM dependencies
        LEFT JOIN versions
          ON id_master = versions.id
        LEFT JOIN libraries
          ON versions.library = libraries.name
      WHERE id_slave = ?
      GROUP BY libraries.title
      ORDER BY libraries.name",
      $ver['id']
    );

    // Make version's files TreeView
    $versions[$i]['files_tree'] = \bbn\tools::make_tree((array)json_decode($ver['content']));
  }
  return $versions;
}

// Get library's version name, the files data for the content treeviews and the list of all libraries with them versions
else if ( !empty($this->data['folder']) && !empty(BBN_CDN_PATH) ){
  $ret = [];
  // Library path
  $lib_path = BBN_CDN_PATH . 'lib/' . $this->data['folder'];
  // Check if the library's subfolders are already inserted into db and use the first not included as version
  if ( is_dir($lib_path) && ($dirs = \bbn\file\dir::get_dirs($lib_path)) ){
    $ver = [];
    foreach ( $dirs as $dir ){
      if ( empty($db->select('versions', [], [
        'name' => basename($dir),
        'library' => $this->data['folder']
      ])) ){
        array_push($ver, $dir);
      }
    }
    if ( empty($ver) ){
      return ['error' => "All library's versions (subfolders) are existing into database."];
    }
  }
  else {
    return ['error' => "The library's directory isn't existing or you don't have a version folder inserted."];
  }
  // Make the tree data
  function tree($path, $ver_path){
    $res = [];
    foreach ( \bbn\file\dir::get_files($path, 1) as $p ){
      $r = [
        'text' => basename($p),
        'path' => substr($p, strlen($ver_path), strlen($p))
      ];
      if ( is_dir($p) ){
        $r['items'] = tree($p, $ver_path);
      }
      array_push($res, $r);
    }
    return $res;
  }
  $ret['tree'] = tree($ver[0], $ver[0]);
  // Version name
  $ret['version'] = basename($ver[0]);
  // Get all libraries list
  $ret['lib_ver'] = $db->get_rows("
    SELECT libraries.title || ' - ' || versions.name AS name, libraries.name AS lib,
      versions.name AS ver, versions.id AS id_ver
    FROM libraries
    LEFT JOIN versions
      ON versions.library = libraries.name
    ORDER BY lib ASC
  ");
  return $ret;

}

// Insert library's version
else if ( !empty($this->data['name']) &&
  !empty($this->data['vname']) &&
  !empty($this->data['status']) &&
  ( !empty($this->data['files']) ||
    !empty($this->data['languages']) ||
    !empty($this->data['themes']) )
){
  $content = [
    'files' => !empty($this->data['file']) ? $this->data['file'] : [],
    'languages' => !empty($this->data['languages']) ? $this->data['languages'] : [],
    'themes' => !empty($this->data['themes']) ? $this->data['themes'] : []
  ];
  if ( $db->insert('versions', [
    'name' => $this->data['vname'],
    'library' => $this->data['name'],
    'content' => json_encode($content),
    'status' => $this->data['status'],
    'date_added' => date('Y-m-d H:i:s', time())
  ]) ) {
    if ( !empty($this->data['dependencies']) ){
      $id = $db->last_id();
      foreach ( $this->data['dependencies'] as $dep ){
        $db->insert('dependencies', [
          'id_master' => $dep,
          'id_slave' => $id
        ]);
      }
    }
  }
  return ['success' => 1];
}

// Update library's version
else if ( !empty($db) &&
  !empty($this->data['name']) &&
  !empty($this->data['library']) &&
  !empty($this->data['content']) &&
  !empty($this->data['date_added']) &&
  !empty($this->data['status'])
){
  unset($this->data['db']);
  if ( $db->update('versions', $this->data) ){
    return $this->data;
  }
  return false;
}

// Delete library's version
else if ( !empty($db) &&
  (count($this->data) === 2) &&
  !empty($this->data['id_ver'])
){
  if ( $db->delete('versions', ['id' => $this->data['id_ver']]) ){
    return ['success' => 1];
    // ELIMINARE DIPENDENZE????
  }
  return false;
}