<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/12/2016
 * Time: 18:59
 */
/** @var $model \bbn\mvc\model */


if ( !empty($model->data['db']) &&
  !empty($model->data['name']) &&
  !empty($model->data['new_name']) &&
  !empty($model->data['title']) &&
  !empty($model->data['edit'])
){
  $id = $model->data['name'];
  $model->data['name'] = $model->data['new_name'];
  unset($model->data['new_name']);
  unset($model->data['edit']);
  $columns = $model->data['db']->get_columns('libraries');
  $change = [];
  foreach ( $model->data as $n => $v ){
    if ( ($n !== 'db') && isset($columns[$n]) ){
      $change[$n] = $v;
    }
  }
  if ( $model->data['db']->update('libraries', $change, ['name' => $id]) ){
    return $model->data;
  }
  return false;
}