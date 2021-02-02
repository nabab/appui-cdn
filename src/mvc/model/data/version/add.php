<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 10:24
 */
/** @var $model \bbn\Mvc\Model */
if ( !empty($model->data['folder']) && !empty($model->data['db']) && \defined('BBN_CDN_PATH') ){
  // Library path
  $model->data['lib_path'] = \bbn\File\Dir::createPath(BBN_CDN_PATH . 'lib/' . $model->data['folder']);

  if ( $model->data['lib_path'] ){
    $model->data['lib_path'] .= '/';
  }

  // GitHub
  if ( !empty($model->data['git_user']) &&
    !empty($model->data['git_repo']) &&
    (!empty($model->data['git_id_ver']) || !empty($model->data['git_latest_ver']) || !empty($model->data['tags']))
  ){
    if ( is_dir($model->data['lib_path']) ){
      $github = $model->getModel(APPUI_CDN_ROOT.'github/version', $model->data);
    }
  }

  // Check if the library's subfolders are already inserted into db and use the first not included as version
  if ( is_dir($model->data['lib_path']) && ($dirs = \bbn\File\Dir::getDirs($model->data['lib_path'])) ){
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
      if ( $g = $model->data['db']->selectOne('libraries', 'git', ['name' => $model->data['folder']]) ){
        return ['github' => $g];
      }
      return [];
    }
  }
  else {
    return ['error' => _("The library's directory isn't existing or you don't have a version folder inserted.")];
  }
  // Make the tree data
  $tree = function($path, $ver_path, $ext=false)use(&$tree){
    $res = [];
    foreach ( \bbn\File\Dir::getFiles($path, 1) as $p ){
      if ( empty($ext) || (!empty($ext) && ( (\bbn\Str::fileExt($p) === $ext) || (\bbn\Str::fileExt($p) === '') ) ) ){
        $pa = substr($p, \strlen($ver_path), \strlen($p));
        $r = [
          'text' => basename($p),
          'fpath' => (strpos($pa, '/') === 0) ? substr($pa, 1, \strlen($pa)) : $pa
        ];
        if ( is_dir($p) ){
          $r['items'] = $tree($p, $ver_path, $ext);
        }
        if ( !is_dir($p) || (is_dir($p) && !empty($r['items'])) ){
          array_push($res, $r);
        }
      }
    }
    return $res;
  };

  foreach( $ver as $v ){
    $all['folders_versions'][] = [
      // Files' tree
      'files_tree' => $tree($v, $v),
      // Files' tree for languages
      'languages_tree' => $tree($v, $v, 'js'),
      // Version name
      'version' => basename($v),
      // All libraries list
      'lib_ver' => $model->data['db']->getRows("
        SELECT libraries.title AS lib_title, libraries.name AS lib_name, Versions.name AS version, Versions.id AS id_ver
        FROM libraries
        JOIN versions
          ON versions.library = libraries.name
        ORDER BY lib_title COLLATE NOCASE ASC, internal DESC
      "),
      // Dependencies from latest version
      'dependencies' => $model->data['db']->getRows('
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
      'slave_dependencies' =>  $model->data['db']->getRows("
        SELECT vers.id AS id_slave, libr.name, libr.title
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
      'internal' => $model->data['db']->getRows("
        SELECT internal AS text, internal AS value
        FROM versions
        WHERE library = ?",
        $model->data['folder']
      ),*/
      'title' => $model->data['db']->selectOne('libraries', 'title', ['name' => $model->data['folder']]),
      'dependencies_html' => !empty($github['dependencies']) ? $github['dependencies'] : ''
    ];
  }
  return [
    'folders_versions' => $all['folders_versions'],
    'github' => $model->data['db']->selectOne('libraries', 'git', ['name' => $model->data['folder']])
  ];
}