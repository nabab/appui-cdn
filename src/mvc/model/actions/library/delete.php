<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/12/2016
 * Time: 18:59
 */
/** @var $model \bbn\mvc\model */

if ( !empty($model->data['db']) && !empty($model->data['name']) ){
  if ( $model->data['db']->delete('libraries', ['name' => $model->data['name']]) ){
    // Get all library's versions' id
    $versions = $model->data['db']->rselect_all('versions', ['id'], ['library' => $model->data['name']]);
    foreach ( $versions as $ver ){
      // Delete versions
      $model->data['db']->delete('versions', ['id' => $ver['id']]);
      // Delete dependecies
      $model->data['db']->delete('dependencies', ['id_slave' => $ver['id']]);
      $model->data['db']->delete('dependencies', ['id_master' => $ver['id']]);
    }
    return ['success' => 1];
  }
}