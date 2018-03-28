<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 09:52
 */
/** @var $model \bbn\mvc\model */

if ( !empty($model->data['db']) &&
  !empty($model->data['id_ver']) &&
  !empty($model->data['library'])
){
  // Get version's name and library's name
  $ver = $model->data['db']->rselect('versions', ['name', 'library'], ['id' => $model->data['id_ver']]);
  // Delete version
  if ( $model->data['db']->delete('versions', ['id' => $model->data['id_ver']]) ){
    // Delete dependences
    $model->data['db']->delete('dependencies', ['id_slave' => $model->data['id_ver']]);
    $model->data['db']->delete('dependencies', ['id_master' => $model->data['id_ver']]);
    //add for delete material folder
    $name_folder_version = $ver['name'];
    $lib_path = BBN_CDN_PATH . 'lib/' . $model->data['library'] . '/'.$name_folder_version;

    // Check if it's the latest library's version
    if ( $ver['name'] === $model->data['db']->select_one('libraries', 'latest', ['name' => $ver['library']]) ){
      // Get previous version's name
      $prev = $model->data['db']->get_one("
        SELECT name
        FROM versions
        WHERE library = ?
        ORDER BY internal DESC
        LIMIT 1",
        $ver['library']
      );

      // Set new latest
      if ( !empty($prev) ){
        $model->data['db']->update('libraries', ['latest' => $prev], ['name' => $ver['library']]);
      }
    }

    return [
      'success' => 1,
    //'delete_folder' => \bbn\file\dir::delete($lib_path),
      'latest' => $model->data['db']->get_one("
        SELECT name, MAX(internal)
        FROM versions
        WHERE library = ?",
        $model->data['library']
      )
    ];
  }
  return false;
}
