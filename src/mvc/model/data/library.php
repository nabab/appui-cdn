<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/12/2016
 * Time: 18:59
 */
/** @var bbn\Mvc\Model $model */
/** @todo check if this file is used */
if ( !empty($model->data['db']) && !empty($model->data['id_lib']) ){
  $ret = [];
  $ret['versions'] = $model->getModel('./versions');
  return $ret;
}