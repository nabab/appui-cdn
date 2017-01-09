<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 09:50
 */
/** @var $model \bbn\mvc\model */

if ( !empty($model->data['db']) &&
  !empty($model->data['id_ver']) &&
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
  if ( $model->data['db']->update('versions', ['content' => json_encode($content)], ['id' => $model->data['id_ver']]) ){
    if ( !empty($model->data['latest']) ){
      $ver_lib = $model->data['db']->rselect('versions', ['name', 'library'], ['id' => $model->data['id_ver']]);
      if ( !$model->data['db']->update('libraries', ['latest' => $ver_lib['name']], ['name' => $ver_lib['library']])){
        return ['error' => 'Error to update latest library\'s version'];
      }
    }
    if ( !empty($model->data['dependencies']) ){
      $dependencies = [];
      foreach ( $model->data['dependencies'] as $dep ){
        $dependencies[$dep['id_ver']] = $dep;
      }
      $old_dep = $model->data['db']->get_col_array('
        SELECT dependencies.id_master
        FROM dependencies
        JOIN versions
          ON dependencies.id_master = versions.id
        WHERE dependencies.id_slave = ?
        GROUP BY versions.library
        ORDER BY versions.internal',
        $model->data['id_ver']
      );
      foreach ( $old_dep as $old ){
        if ( !in_array($old, array_keys($dependencies)) ){
          if ( !$model->data['db']->delete('dependencies', [
            'id_slave' => $model->data['id_ver'],
            'id_master' => $old
          ]) ){
            return ['error' => _('Error to delete a version\'s dependency')];
          };
        }
      }
      foreach ( $dependencies as $idd => $dep ){
        if ( !in_array($idd, $old_dep) ){
          if ( !$model->data['db']->insert('dependencies', [
            'id_slave' => $model->data['id_ver'],
            'id_master' => $idd,
            'order' => $dep['order']
          ]) ){
            return ['error' => _('Error to insert a new version\'s dependency') ];
          }
        }
        else {
          if ( !$model->data['db']->update('dependencies', [
            'order' => $dep['order']
          ], [
            'id_slave' => $model->data['id_ver'],
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