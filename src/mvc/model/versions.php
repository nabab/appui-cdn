<?php
/** @var $this \bbn\mvc\controller */

// DB connection
$db =& $this->data['db'];

if ( !empty($db) && !empty($this->data['id_lib']) ){
  // Get all library's versions
  $versions =  $db->rselect_all('versions', [], ['library' => $this->data['id_lib']], ['date_added' => 'DESC']);

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
    $versions[$i]['files_tree'] = \bbn\x::make_tree((array)json_decode($ver['content']));
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
      return ['error' => _("All library's versions (subfolders) are existing into database.")];
    }
  }
  else {
    return ['error' => _("The library's directory isn't existing or you don't have a version folder inserted.")];
  }
  // Make the tree data
  function tree($path, $ver_path, $ext=false){
    $res = [];
    foreach ( \bbn\file\dir::get_files($path, 1) as $p ){
      if ( empty($ext) || (!empty($ext) && ( (\bbn\str::file_ext($p) === $ext) || (\bbn\str::file_ext($p) === '') ) ) ){
        $pa = substr($p, strlen($ver_path), strlen($p));
        $r = [
          'text' => basename($p),
          'path' => (strpos($pa, '/') === 0) ? substr($pa, 1, strlen($pa)) : $pa
        ];
        if ( is_dir($p) ){
          $r['items'] = tree($p, $ver_path, $ext);
        }
        if ( !is_dir($p) || (is_dir($p) && !empty($r['items'])) ){
          array_push($res, $r);
        }
      }
    }
    return $res;
  }
  // Files' tree
  $ret['tree'] = tree($ver[0], $ver[0]);
  // Files' tree for languages
  $ret['languages_tree'] = tree($ver[0], $ver[0], 'js');
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

// Insert new library's version
else if ( !empty($db) &&
  !empty($this->data['name']) &&
  !empty($this->data['vname']) &&
  ( !empty($this->data['files']) ||
    !empty($this->data['languages']) ||
    !empty($this->data['themes']) )
){
  $languages = [];
  $themes = [];
  foreach ( json_decode($this->data['languages'], 1) as $l ){
    array_push($languages, $l['path']);
  }
  foreach ( json_decode($this->data['themes'], 1) as $l ){
    array_push($themes, $l['path']);
  }
  $content = [
    'files' => !empty($this->data['files']) ? json_decode($this->data['files'], 1) : [],
    'lang' => $languages,
    'theme_files' => $themes
  ];
  if ( $db->insert('versions', [
    'name' => $this->data['vname'],
    'library' => $this->data['name'],
    'content' => json_encode($content),
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
    if ( !empty($this->data['latest']) ){
      $db->update('libraries', ['latest' => $this->data['vname']], ['name' => $this->data['name']]);
    }
  }
  return ['success' => 1];
}

// Returns the files data for the content treeviews with checked, all libraries list and if the version is latest. (Edit version)
else if ( !empty($db) &&
  !empty($this->data['version']) &&
  (count($this->data) === 2)
){
  $ver = $db->rselect('versions', ['name', 'library', 'content'], ['id' => $this->data['version']]);
  $p = BBN_CDN_PATH . 'lib/' . $ver['library'] . '/' . $ver['name'];
  $cont = json_decode($ver['content'], 1);
  // Make the tree data
  function tree($path, $ver_path, $c=false, $ext=false){
    $res = [];
    foreach ( \bbn\file\dir::get_files($path, 1) as $p ){
      if ( empty($ext) || (!empty($ext) && ( (\bbn\str::file_ext($p) === $ext) || (\bbn\str::file_ext($p) === '') ) ) ){
        $pa = substr($p, strlen($ver_path), strlen($p));
        $r = [
          'text' => basename($p),
          'path' => (strpos($pa, '/') === 0) ? substr($pa, 1, strlen($pa)) : $pa
        ];
        if ( !empty($c) && in_array($r['path'], $c) ){
          $r['checked'] = 1;
        }
        if ( is_dir($p) ){
          $r['items'] = tree($p, $ver_path, $c, $ext);
        }
        if ( !is_dir($p) || (is_dir($p) && !empty($r['items'])) ){
          array_push($res, $r);
        }
      }
    }
    return $res;
  }
  $files = [];
  $languages = [];
  $themes = [];
  foreach ( $cont['files'] as $f ){
    array_push($files, ['path' => $f]);
  }
  foreach ( $cont['lang'] as $l ){
    array_push($languages, ['path' => $l]);
  }
  foreach ( $cont['theme_files'] as $t ){
    array_push($themes, ['path' => $t]);
  }
  $ret = [
    'files' => $files,
    'files_tree' => tree($p, $p, $cont['files']),
    'languages' => $languages,
    'languages_tree' => tree($p, $p, 0, 'js'),
    'themes' => $themes,
    'themes_tree' => tree($p, $p),
    // all libraries list
    'lib_ver' => $db->get_rows("
      SELECT libraries.title || ' - ' || versions.name AS name, libraries.name AS lib,
        versions.name AS ver, versions.id AS id_ver
      FROM libraries
      LEFT JOIN versions
        ON versions.library = libraries.name
      ORDER BY lib ASC
    "),
    // all versions' dependencies
    'dependencies' => $db->get_column_values('dependencies', 'id_master', ['id_slave' => $this->data['version']])
  ];
  if ( $db->select_one('libraries', 'latest', ['name' => $ver['library']]) === $ver['name'] ){
    $ret['latest'] = 1;
  }
  return $ret;
}

// Update library's version
else if ( !empty($db) &&
  !empty($this->data['id_ver']) &&
  ( !empty($this->data['files']) ||
    !empty($this->data['languages']) ||
    !empty($this->data['themes']) )
){
  $languages = [];
  $themes = [];
  foreach ( json_decode($this->data['languages'], 1) as $l ){
    array_push($languages, $l['path']);
  }
  foreach ( json_decode($this->data['themes'], 1) as $l ){
    array_push($themes, $l['path']);
  }
  $content = [
    'files' => !empty($this->data['files']) ? json_decode($this->data['files'], 1) : [],
    'lang' => $languages,
    'theme_files' => $themes
  ];
  if ( $db->update('versions', ['content' => json_encode($content)], ['id' => $this->data['id_ver']]) ){
    if ( !empty($this->data['latest']) ){
      $ver_lib = $db->rselect('versions', ['name', 'library'], ['id' => $this->data['id_ver']]);
      if ( !$db->update('libraries', ['latest' => $ver_lib['name']], ['name' => $ver_lib['library']])){
        return ['error' => 'Error to update latest library\'s version'];
      }
    }
    if ( !empty($this->data['dependencies']) ){
      $old_dep = $db->get_column_values('dependencies', 'id_master', ['id_slave' => $this->data['id_ver']]);
      foreach ( $old_dep as $old ){
        if ( !in_array($old, $this->data['dependencies']) ){
          if ( !$db->delete('dependencies', [
            'id_slave' => $this->data['id_ver'],
            'id_master' => $old
          ]) ){
            return ['error' => _('Error to delete a version\'s dependency')];
          };
        }
        else {
          unset($this->data['dependencies'][array_search($old, $this->data['dependencies'])]);
        }
      }
      foreach ( $this->data['dependencies'] as $dep ){
        if ( !in_array($dep, $old_dep) ){
          if ( !$db->insert('dependencies', [
            'id_slave' => $this->data['id_ver'],
            'id_master' => $dep
          ]) ){
            return ['error' => _('Error to insert a new version\'s dependency') ];
          }
        }
      }
    }
    return ['success' => 1];
  }
  return false;
}

// Delete library's version
else if ( !empty($db) &&
  (count($this->data) === 2) &&
  !empty($this->data['id_ver'])
){
  if ( $db->delete('versions', ['id' => $this->data['id_ver']]) ){
    // Delete dependences
    $db->delete('dependencies', ['id_slave' => $this->data['id_ver']]);
    $db->delete('dependencies', ['id_master' => $this->data['id_ver']]);
    return ['success' => 1];
  }
  return false;
}