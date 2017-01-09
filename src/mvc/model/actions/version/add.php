<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 09:48
 */
/** @var $model \bbn\mvc\model */
if ( !empty($model->data['db']) &&
  !empty($model->data['name']) &&
  !empty($model->data['vname']) &&
  ( !empty($model->data['files']) ||
    !empty($model->data['languages']) ||
    !empty($model->data['themes']) )
){
  $languages = [];
  $themes = [];
  foreach ( json_decode($model->data['languages'], 1) as $l ){
    array_push($languages, $l['path']);
  }
  foreach ( json_decode($model->data['themes'], 1) as $l ){
    array_push($themes, $l['path']);
  }
  $content = [
    'files' => !empty($model->data['files']) ? json_decode($model->data['files'], 1) : [],
    'lang' => $languages,
    'theme_files' => $themes
  ];
  if ( !empty($model->data['latest']) ){
    $internal = $model->data['db']->get_one("
    SELECT MAX(internal)
    FROM versions
    WHERE library = ?",
      $model->data['name']
    );
    if ( is_null($internal) ){
      $internal = 0;
    }
    else {
      $internal++;
    }
  }
  else if ( isset($model->data['internal']) && \bbn\str::is_integer($model->data['internal']) ){
    $internal = $model->data['internal'];
    if ( $model->data['db']->select_one('versions', 'internal', ['internal' => $internal]) ){
      $model->data['db']->query("
      UPDATE versions SET internal = internal+1
      WHERE internal >= ?
        AND library = ?",
        $internal,
        $model->data['name']
      );
    }
  }
  if ( $model->data['db']->insert('versions', [
    'name' => $model->data['vname'],
    'library' => $model->data['name'],
    'content' => json_encode($content),
    'date_added' => date('Y-m-d H:i:s', time()),
    'internal' => $internal
  ]) ) {
    $id = $model->data['db']->last_id();
    if ( !empty($model->data['dependencies']) ){
      foreach ( $model->data['dependencies'] as $dep ){
        if ( $model->data['db']->select_one('dependencies', 'order', ['order' => $dep['order']]) ){
          $model->data['db']->query('
          UPDATE "dependencies" SET "order" = "order"+1
          WHERE "order" >= ?
            AND "id_slave" = ?',
            $dep['order'],
            $id
          );
        }
        $model->data['db']->insert('dependencies', [
          'id_master' => $dep['id_ver'],
          'id_slave' => $id
        ]);
      }
    }
    if ( !empty($model->data['latest']) ){
      $model->data['db']->update('libraries', ['latest' => $model->data['vname']], ['name' => $model->data['name']]);
    }
    if ( !empty($model->data['slave_dependencies']) ){
      foreach ( $model->data['slave_dependencies'] AS $lib => $yes ){
        if ( !empty($yes) ){
          $id_slave = $model->data['db']->get_one("
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
            $model->data['db']->insert('dependencies', [
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