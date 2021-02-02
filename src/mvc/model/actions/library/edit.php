<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/12/2016
 * Time: 18:59
 */
/** @var $model \bbn\Mvc\Model */


if ( !empty($model->data['db']) &&
  !empty($model->data['name']) &&
  !empty($model->data['new_name']) &&
  !empty($model->data['title'])
//  !empty($model->data['edit'])
){
  $id = $model->data['name'];

  $old_path = BBN_CDN_PATH.'lib/'.$model->data['name'];
  $new_path = BBN_CDN_PATH.'lib/'.$model->data['new_name'];

  if ( $old_path !== $new_path  && !file_exists($new_path) && file_exists($old_path) ){
    $rename_foder = \bbn\File\Dir::move($old_path,$new_path);
    if ( empty($model->data['db']->update('versions', ['library' => $model->data['new_name'] ], ['library' => $model->data['name']])) && empty($rename_folder) ){

      return ['error' => _("error rename")];
    }
  }
  $model->data['name'] = $model->data['new_name'];

  unset($model->data['new_name']);
  unset($model->data['edit']);
  $columns = $model->data['db']->getColumns('libraries');
  $change = [];
  foreach ( $model->data as $n => $v ){
    if ( ($n !== 'db') && isset($columns[$n]) ){
      $change[$n] = $v;
    }
  }
  if ( $model->data['db']->update('libraries', $change, ['name' => $id]) ){
    return $change;
  }


  return false;
}
