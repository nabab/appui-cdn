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

// Get library's version name and the files data for the content treeviews
else if ( !empty($this->data['folder']) && !empty(BBN_CDN_PATH) ){
  // Library path
  $lib_path = BBN_CDN_PATH . 'lib/' . $this->data['folder'];
  if ( is_dir($lib_path) && (count($dirs = \bbn\file\dir::get_dirs($lib_path)) === 1) ){
    // Library's version name
    $ret['version'] = basename($dirs[0]);
    // Library's version path
    $ver_path = $lib_path . '/' . $ret['version'];
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
    $ret['tree'] = tree($dirs[0], $ver_path);
    return $ret;
  }
  else {
    return ['error' => "The library's directory isn't existing or you have two or more subfolders (library's version name) into library's directory."];
  }
}