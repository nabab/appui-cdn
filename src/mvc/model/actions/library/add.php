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
  !empty($model->data['title']) &&
  !empty($model->data['vname']) &&
  ( !empty($model->data['files']) ||
    !empty($model->data['languages']) ||
    !empty($model->data['themes']) )
){
  if ( $model->data['db']->insert('libraries', [
    'name' => $model->data['name'],
    'fname' => $model->data['fname'],
    'title' => $model->data['title'],
    'latest' => $model->data['vname'],
    'description' => $model->data['description'],
    'website' => $model->data['website'],
    'author' => $model->data['author'],
    'licence' => $model->data['licence'],
    'download_link' => $model->data['download_link'],
    'doc_link' => $model->data['doc_link'],
    'git' => $model->data['git'],
    'support_link' => $model->data['support_link'],
    'last_update' => date('Y-m-d H:i:s', Time()),
    'last_check' => date('Y-m-d H:i:s', Time())
  ]) ){
    $ver = $model->getModel('./../version/add', $model->data);
    if ( !empty($ver['success']) ){
      return $model->data['db']->getRows("
        SELECT *
        FROM libraries
        ORDER BY title COLLATE NOCASE ASC
      ");
    }
    return ['error' => _("Error to add new library's version.")];
  }
  return false;
}
