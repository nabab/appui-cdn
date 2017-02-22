<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 10:26
 */
/** @var $ctrl \bbn\mvc\controller */

if ( !empty($ctrl->post) ){
  $ctrl->data = array_merge($ctrl->data, $ctrl->post);
  $model = $ctrl->get_model();
  if ( !empty($model['error']) ){
    $ctrl->obj->error = $model['error'];
  }
  else {
    $ctrl->obj->data = $model;
  }
}