<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 10:24
 */
/** @var $model \bbn\mvc\model */
if ( !empty($model->data['folder']) && !empty($model->data['db']) && \defined('BBN_CDN_PATH') ){
  // Library path
  $model->data['lib_path'] = BBN_CDN_PATH . 'lib/' . $model->data['folder'] . '/';

  // GitHub
  if ( !empty($model->data['git_user']) &&
    !empty($model->data['git_repo']) &&
    (!empty($model->data['git_id_ver']) || !empty($model->data['git_latest_ver']))
  ){
    if ( !is_dir($model->data['lib_path']) ){
      \bbn\file\dir::create_path($model->data['lib_path']);
    }
    if ( is_dir($model->data['lib_path']) ){
      $github = $model->get_model('./../../github/version', $model->data);
    }

  }

  // Check if the library's subfolders are already inserted into db and use the first not included as version
  if ( is_dir($model->data['lib_path']) && ($dirs = \bbn\file\dir::get_dirs($model->data['lib_path'])) ){
    $ver = [];
    if ( !empty($dirs) ){
      foreach ( $dirs as $dir ){
        if ( empty($model->data['db']->select('versions', [], [
          'name' => basename($dir),
          'library' => $model->data['folder']
        ])) ){
          if ( empty($github) ){
            array_push($ver, $dir);
          }
          else if ( !empty($github['success']) && (basename($dir) === $github['version']) ){
            array_push($ver, $dir);
          }
        }
      }
    }
    if ( empty($ver) ){
      if ( $g = $model->data['db']->select_one('libraries', 'git', ['name' => $model->data['folder']]) ){
        return ['github' => $g];
      }
      return [];
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
        $pa = substr($p, \strlen($ver_path), \strlen($p));
        $r = [
          'text' => basename($p),
          'path' => (strpos($pa, '/') === 0) ? substr($pa, 1, \strlen($pa)) : $pa
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
    'files_tree' => tree($ver[0], $ver[0]),
    // Files' tree for languages
    'languages_tree' => tree($ver[0], $ver[0], 'js'),
    // Version name
    'version' => basename($ver[0]),
    // All libraries list
    'lib_ver' => $model->data['db']->get_rows("
      SELECT libraries.title AS lib_title, libraries.name AS lib_name, versions.name AS version, versions.id AS id_ver
      FROM libraries
      JOIN versions
        ON versions.library = libraries.name
      ORDER BY lib_title COLLATE NOCASE ASC, internal DESC
    "),
    // Dependencies from latest version
    'dependencies' => $model->data['db']->get_rows('
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
      $model->data['folder']
    ),
    // All slave dependencies
    'slave_dependencies' => $model->data['db']->get_rows("
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
      $model->data['folder']
    ),/*
    'internal' => $model->data['db']->get_rows("
      SELECT internal AS text, internal AS value
      FROM versions
      WHERE library = ?",
      $model->data['folder']
    ),*/
    'title' => $model->data['db']->select_one('libraries', 'title', ['name' => $model->data['folder']]),
    'dependencies_html' => !empty($github['dependencies']) ? $github['dependencies'] : ''
  ];
}
