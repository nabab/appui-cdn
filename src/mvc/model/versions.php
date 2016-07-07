<?php
/** @var $this \bbn\mvc\controller */

// DB connection
$db =& $this->data['db'];

if ( !empty($db) && !empty($this->data['id_lib']) ){
  // Get all library's versions
  $versions =  $db->get_rows("
    SELECT versions.*, 
      CASE WHEN versions.name = libraries.latest THEN 1 ELSE 0 END AS is_latest
    FROM versions
    JOIN libraries
      ON versions.library = libraries.name
    WHERE versions.library = ?
    ORDER BY internal DESC",
    $this->data['id_lib']
  );

  foreach( $versions as $i => $ver ){
    // Get all version's dependencies
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

    // Get all version's slave dependencies
    $versions[$i]['slave_dependencies'] = $db->get_rows("
      SELECT libr.name, libr.title, vers.name AS version
      FROM versions
      JOIN libraries
        ON versions.library = libraries.name
        AND versions.name = libraries.latest
      JOIN dependencies 
        ON versions.id = dependencies.id_master
      JOIN versions AS vers
        ON dependencies.id_slave = vers.id
      JOIN libraries AS libr
        ON vers.library = libr.name
      WHERE versions.id = ?
      GROUP BY libr.name
      ORDER BY libr.title COLLATE NOCASE ASC, vers.internal DESC",
      $ver['id']
    );

    // Make version's files TreeView
    $versions[$i]['files_tree'] = \bbn\x::make_tree((array)json_decode($ver['content']));
  }
  return $versions;
}

// Get library's version name, the files data for the content treeviews and the list of all libraries with them versions (INSERT MODE)
else if ( !empty($this->data['folder']) && !empty(BBN_CDN_PATH) ){
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
  return [
    // Files' tree
    'tree' => tree($ver[0], $ver[0]),
    // Files' tree for languages
    'languages_tree' => tree($ver[0], $ver[0], 'js'),
    // Version name
    'version' => basename($ver[0]),
    // All libraries list
    'lib_ver' => $db->get_rows("
      SELECT libraries.title AS lib_title, libraries.name AS lib_name, versions.name AS version, versions.id AS id_ver
      FROM libraries
      JOIN versions
        ON versions.library = libraries.name
      ORDER BY lib_title COLLATE NOCASE ASC, internal DESC
    "),
    // Dependencies from latest version
    'dependencies' => $db->get_rows('
      SELECT "vers"."id" AS id_ver, "vers"."name" AS version, "libr"."name" AS lib_name, 
        "libr"."title" AS lib_title, "dependencies"."order"
      FROM "versions"
      JOIN "libraries"
        ON "versions"."library" = "libraries"."name"
        AND "versions"."name" = "libraries"."latest"
      JOIN "dependencies" 
        ON "versions"."id" = "dependencies"."id_slave"
      JOIN "versions" AS vers
        ON "dependencies"."id_master" = "vers"."id"
      JOIN "libraries" AS libr
        ON "vers"."library" = "libr"."name"
      WHERE "libraries"."name" = ?
      ORDER BY "libr"."title" COLLATE NOCASE ASC',
      $this->data['folder']
      ),
    // All slave dependencies
    'slave_dependencies' => $db->get_rows("
      SELECT libr.name, libr.title
      FROM versions
      JOIN libraries
        ON versions.library = libraries.name
        AND versions.name = libraries.latest
      JOIN dependencies 
        ON versions.id = dependencies.id_master
      JOIN versions AS vers
        ON dependencies.id_slave = vers.id
      JOIN libraries AS libr
        ON vers.library = libr.name
      WHERE libraries.name = ?
      ORDER BY libr.name ASC",
      $this->data['folder']
    ),
    'internal' => $db->get_rows("
      SELECT internal AS text, internal AS value
      FROM versions
      WHERE library = ?",
      $this->data['folder']
    )
  ];
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
  if ( !empty($this->data['latest']) ){
    $internal = $db->get_one("
    SELECT MAX(internal)
    FROM versions
    WHERE library = ?",
      $this->data['name']
    );
    if ( is_null($internal) ){
      $internal = 0;
    }
    else {
      $internal++;
    }
  }
  else if ( isset($this->data['internal']) && \bbn\str::is_integer($this->data['internal']) ){
    $internal = $this->data['internal'];
    if ( $db->select_one('versions', 'internal', ['internal' => $internal]) ){
      $db->query("
      UPDATE versions SET internal = internal+1
      WHERE internal >= ?
        AND library = ?",
        $internal,
        $this->data['name']
      );
    }
  }
  if ( $db->insert('versions', [
    'name' => $this->data['vname'],
    'library' => $this->data['name'],
    'content' => json_encode($content),
    'date_added' => date('Y-m-d H:i:s', time()),
    'internal' => $internal
  ]) ) {
    $id = $db->last_id();
    if ( !empty($this->data['dependencies']) ){
      foreach ( $this->data['dependencies'] as $dep ){
        if ( $db->select_one('dependencies', 'order', ['order' => $dep['order']]) ){
          $db->query('
          UPDATE "dependencies" SET "order" = "order"+1
          WHERE "order" >= ?
            AND "id_slave" = ?',
            $dep['order'],
            $id
          );
        }
        $db->insert('dependencies', [
          'id_master' => $dep['id_ver'],
          'id_slave' => $id
        ]);
      }
    }
    if ( !empty($this->data['latest']) ){
      $db->update('libraries', ['latest' => $this->data['vname']], ['name' => $this->data['name']]);
    }
    if ( !empty($this->data['slave_dependencies']) ){
      foreach ( $this->data['slave_dependencies'] AS $lib => $yes ){
        if ( !empty($yes) ){
          $id_slave = $db->get_one("
            SELECT versions.id
            FROM versions
            JOIN libraries
              ON versions.library = libraries.name
              AND versions.name = libraries.latest
            WHERE libraries.name = ?
            LIMIT 1",
            $lib
          );
          if ( !empty($id_slave) ){
            $db->insert('dependencies', [
              'id_master' => $id,
              'id_slave' => $id_slave
            ]);
          }
        }
      }
    }
  }
  return ['success' => 1];
}

// Returns the files data for the content treeviews with checked, all libraries list and if the version is latest. (EDIT MODE)
else if ( !empty($db) &&
  !empty($this->data['version'])
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
  if ( !empty($cont['files']) ){
    foreach ( $cont['files'] as $f ){
      array_push($files, ['path' => $f]);
    }
  }
  if ( !empty($cont['lang']) ){
    foreach ( $cont['lang'] as $l ){
      array_push($languages, ['path' => $l]);
    }
  }
  if ( !empty($cont['theme_files']) ){
    foreach ( $cont['theme_files'] as $t ){
      array_push($themes, ['path' => $t]);
    }
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
      SELECT libraries.title AS lib_title, libraries.name AS lib_name, versions.name AS version, versions.id AS id_ver
      FROM libraries
      JOIN versions
        ON versions.library = libraries.name
      ORDER BY lib_title COLLATE NOCASE ASC, internal DESC
    "),
    // all versions' dependencies
    'dependencies' => $db->get_rows('
      SELECT "libraries"."title" AS lib_title, "libraries"."name" AS lib_name, 
        "versions"."name" AS version, "versions"."id" AS id_ver,
        MAX("versions"."internal") AS internal, "dependencies"."order" 
      FROM "versions"
      JOIN "dependencies"
        ON "versions"."id" = "dependencies"."id_master"
      JOIN "libraries"
        ON "versions"."library" = "libraries"."name"
      WHERE "dependencies"."id_slave" = ?
      GROUP BY "versions"."library"
      ORDER BY "libraries"."title" COLLATE NOCASE ASC',
      $this->data['version']
    ),
    //'dependencies' => $db->get_column_values('dependencies', 'id_master', ['id_slave' => $this->data['version']])
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
      $dependencies = [];
      foreach ( $this->data['dependencies'] as $dep ){
        $dependencies[$dep['id_ver']] = $dep;
      }
      $old_dep = $db->get_col_array('
        SELECT dependencies.id_master
        FROM dependencies
        JOIN versions
          ON dependencies.id_master = versions.id
        WHERE dependencies.id_slave = ?
        GROUP BY versions.library
        ORDER BY versions.internal',
        $this->data['id_ver']
      );
      foreach ( $old_dep as $old ){
        if ( !in_array($old, array_keys($dependencies)) ){
          if ( !$db->delete('dependencies', [
            'id_slave' => $this->data['id_ver'],
            'id_master' => $old
          ]) ){
            return ['error' => _('Error to delete a version\'s dependency')];
          };
        }
      }
      foreach ( $dependencies as $idd => $dep ){
        if ( !in_array($idd, $old_dep) ){
          if ( !$db->insert('dependencies', [
            'id_slave' => $this->data['id_ver'],
            'id_master' => $idd,
            'order' => $dep['order']
          ]) ){
            return ['error' => _('Error to insert a new version\'s dependency') ];
          }
        }
        else {
          if ( !$db->update('dependencies', [
            'order' => $dep['order']
          ], [
            'id_slave' => $this->data['id_ver'],
            'id_master' => $idd
          ]) ){
            return ['error' => _('Error to update a version\'s dependency') ];
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
  !empty($this->data['id_ver']) &&
  !empty($this->data['library'])
){
  // Get version's name and library's name
  $ver = $db->rselect('versions', ['name', 'library'], ['id' => $this->data['id_ver']]);
  // Delete version
  if ( $db->delete('versions', ['id' => $this->data['id_ver']]) ){
    // Delete dependences
    $db->delete('dependencies', ['id_slave' => $this->data['id_ver']]);
    $db->delete('dependencies', ['id_master' => $this->data['id_ver']]);
    // Check if it's the latest library's version
    if ( $ver['name'] === $db->select_one('libraries', 'latest', ['name' => $ver['library']]) ){
      // Get previous version's name
      $prev = $db->get_one("
        SELECT name
        FROM versions
        WHERE library = ?
        ORDER BY internal DESC
        LIMIT 1",
        $ver['library']
      );
      // Set new latest
      if ( !empty($prev) ){
        $db->update('libraries', ['latest' => $prev], ['name' => $ver['library']]);
      }
    }
    return [
      'success' => 1,
      'latest' => $db->get_one("
        SELECT name, MAX(internal)
        FROM versions
        WHERE library = ?",
        $this->data['library']
      )
    ];
  }
  return false;
}