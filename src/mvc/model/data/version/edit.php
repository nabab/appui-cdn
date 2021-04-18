<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 10:24
 */
/** @var $model \bbn\Mvc\Model */

// Returns the files data for the content treeviews with checked, all libraries list and if the version is latest. (EDIT MODE)
if ( !empty($model->data['db']) && !empty($model->data['version']) && \defined('BBN_CDN_PATH') ){
  $ver = $model->data['db']->rselect('versions', ['name', 'library', 'content'], ['id' => $model->data['version']]);
  $p = BBN_CDN_PATH . 'lib/' . $ver['library'] . '/' . $ver['name'];
  $cont = json_decode($ver['content'], 1);
  // Make the tree data
  function tree($path, $ver_path, $c=false, $ext=false){
    $res = [];
    $paths = \bbn\File\Dir::getFiles($path, 1);
    if ( !empty($paths) ){
      foreach ( $paths as $p ){
        if ( empty($ext) || (!empty($ext) && ( (\bbn\Str::fileExt($p) === $ext) || (\bbn\Str::fileExt($p) === '') ) ) ){
          $pa = substr($p, \strlen($ver_path), \strlen($p));
          $r = [
            'text' => basename($p),
            'fpath' => (strpos($pa, '/') === 0) ? substr($pa, 1, \strlen($pa)) : $pa
          ];
          if ( !empty($c) && \in_array($r['fpath'], $c) ){
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
    }
    return $res;
  }
  $files = [];
  $languages = [];
  $themes = [];
  if ( !empty($cont['files']) ){
    foreach ( $cont['files'] as $f ){
      array_push($files, ['fpath' => $f]);
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
    'theme_prepend' => !empty($cont['theme_prepend']),
    // all libraries list
    'lib_ver' => $model->data['db']->getRows("
      SELECT libraries.title AS lib_title, libraries.name AS lib_name, Versions.name AS version, Versions.id AS id_ver
      FROM libraries
      JOIN versions
        ON versions.library = libraries.name
      ORDER BY lib_title COLLATE NOCASE ASC, internal DESC
    "),
    // all versions' dependencies
    'dependencies' => $model->data['db']->getRows('
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
      $model->data['version']
    ),//temporaeny add versions
    'versions' =>  $model->data['db']->getRows("
        SELECT versions.*,
          CASE WHEN versions.name = libraries.latest THEN 1 ELSE 0 END AS is_latest
        FROM versions
        JOIN libraries
          ON versions.library = libraries.name
        WHERE versions.library = ?
        ORDER BY internal DESC",
        $model->data['library']
      )
    //'dependencies' => $model->data['db']->getColumnValues('dependencies', 'id_master', ['id_slave' => $model->data['version']])
  ];
  if ( $model->data['db']->selectOne('libraries', 'latest', ['name' => $ver['library']]) === $ver['name'] ){
    $ret['latest'] = 1;
  }
  return ['data' => $ret];
}
