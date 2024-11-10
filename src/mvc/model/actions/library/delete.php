<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/12/2016
 * Time: 18:59
 */
/** @var bbn\Mvc\Model $model */

if ( !empty($model->data['db']) && !empty($model->data['name']) ){
  if ( !empty($model->data['removeFolder']) ){
    $path_folder = BBN_CDN_PATH.'lib/'.$model->data['name'];
    if( empty(\bbn\File\Dir::delete($path_folder)) ){
      return ['error' => _('Error while deleting folder')];
    }
  }
  if ( $model->data['db']->delete('libraries', ['name' => $model->data['name']]) ){
    // Get all library's versions' id
    $versions = $model->data['db']->rselectAll('versions', ['id'], ['library' => $model->data['name']]);
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
