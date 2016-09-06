<?php
/** @var $ctrl \bbn\mvc\controller */

if ( !empty($ctrl->post) ){
  $ctrl->data = array_merge($ctrl->data, $ctrl->post);
  $ctrl->obj->data = $ctrl->get_model();
}