<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 09:48
 */
/** @var bbn\Mvc\Model $model */
if ( !empty($model->data['db']) &&
  !empty($model->data['name']) &&
  !empty($model->data['vname']) &&
  ( !empty($model->data['files']) ||
    !empty($model->data['languages']) ||
    !empty($model->data['themes']) )
){
  $languages = [];
  $themes = [];


  foreach ( $model->data['languages'] as $l ){
    array_push($languages, $l['path']);
  }
  foreach ( $model->data['themes'] as $l ){
    array_push($themes, $l['path']);
  }
  $content = [
    'files' => !empty($model->data['files']) ? $model->data['files']  : [],
    'lang' => $languages,
    'theme_files' => $themes
  ];

  
  if ( !empty($content['theme_files']) ){
    $content['theme_prepend'] = $model->data['theme_prepend'];
  }

  if ( !empty($model->data['is_latest']) ){
    $internal = $model->data['db']->getOne(<<<'SQLITE'
    SELECT MAX(internal)
    FROM versions
    WHERE library = ?
SQLITE
,
      $model->data['name']
    );
    if ( \is_null($internal) ){
      $internal = 0;
    }
    else {
      $internal++;
    }
  }
  else if ( isset($model->data['internal']) && \bbn\Str::isInteger($model->data['internal']) ){
    $internal = $model->data['internal'];
    if ( $model->data['db']->selectOne('versions', 'internal', ['internal' => $internal]) ){
      $model->data['db']->query(<<<SQLITE
      UPDATE versions SET internal = internal+1
      WHERE internal >= ?
        AND library = ?
SQLITE
,
        $internal,
        $model->data['name']
      );
    }
  }
  if ( $model->data['db']->insert('versions', [
    'name' => $model->data['vname'],
    'library' => $model->data['name'],
    'content' => json_encode($content),
    'date_added' => date('Y-m-d H:i:s', Time()),
    'internal' => $internal
  ]) ) {
    $id = $model->data['db']->lastId();
    if ( !empty($model->data['is_latest']) ){
      $model->data['db']->update('libraries', ['latest' => $model->data['vname']], ['name' => $model->data['name']]);
    }


    if ( !empty($model->data['dependencies']) ){
      foreach ( $model->data['dependencies'] as $dep ){
        if ( $model->data['db']->selectOne('dependencies', 'order', ['order' => $dep['order']]) ){
          $model->data['db']->query(<<<'SQLITE'
          UPDATE "dependencies" SET "order" = "order"+1
          WHERE "order" >= ?
            AND "id_slave" = ?
SQLITE
,
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


    if ( !empty($model->data['slave_dependencies']) ){
      if ( !empty($model->data['no_update_dependents']) ){
        $no_update = $model->data['no_update_dependents'];
        $updates = array_map(function($lib)use($no_update){
          foreach($no_update as $ele){
            if ( $ele['id_slave'] !== $lib['id_slave']){
              return $lib['id_slave'];
            }
          }
        }, $model->data['slave_dependencies']);
      }
      else{
        $updates= array_map(function($lib){
          return $lib['id_slave'];
        }, $model->data['slave_dependencies']);
      }
    }

    if( !empty($updates) ){
      foreach ( $updates AS $id_slave ){
        if ( !empty($id_slave) ){
          $model->data['db']->insert('dependencies', [
            'id_master' => $id,
            'id_slave' => $id_slave
          ]);
        }
      }
    }
    return ['success' => true];
  }
}
return ['success' => false];

/*
      foreach ( $model->data['slave_dependencies'] AS $lib => $yes ){
        if ( !empty($yes) ){
          $id_slave = $model->data['db']->getOne(<<<SQLITE
            SELECT versions.id
            FROM versions
            JOIN libraries
              ON versions.library = libraries.name
              AND versions.name = libraries.latest
            WHERE libraries.name = ?
            LIMIT 1
SQLITE
,
            $lib
          );
          if ( !empty($id_slave) ){
            $model->data['db']->insert('dependencies', [
              'id_master' => $id,
              'id_slave' => $id_slave
            ]);
          }
        }
      }*/
