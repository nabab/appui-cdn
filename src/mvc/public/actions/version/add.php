<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/12/2016
 * Time: 18:59
 */
/** @var $ctrl \bbn\Mvc\Controller */
if ( !empty($ctrl->post) ){
  $ctrl->data = array_merge($ctrl->data, $ctrl->post);
  $ctrl->obj->data = $ctrl->getModel();
}
