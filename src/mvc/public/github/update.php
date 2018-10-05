<?php
if ( !empty($ctrl->data['db']) ){
  $ctrl->data = array_merge($ctrl->data, $ctrl->post);
  $ctrl->obj->data = $ctrl->get_model();
}
