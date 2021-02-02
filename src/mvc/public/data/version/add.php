<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 10:26
 */
/** @var $ctrl \bbn\Mvc\Controller */

if ( !empty($ctrl->post) ){
  $ctrl->data = array_merge($ctrl->data, $ctrl->post);
  $model = $ctrl->getModel();
  if ( !empty($model['error']) ){
    $ctrl->obj->error = $model['error'];
  }
  else {
    $ctrl->obj->data = $model;
  }
}
