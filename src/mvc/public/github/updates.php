<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 30/12/2016
 * Time: 17:53
 */
/** @var bbn\Mvc\Controller $ctrl */
if ( !empty($ctrl->data['db']) ){
  $ctrl->obj->data = $ctrl->getModel();
}