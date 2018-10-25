<?php
if ( !empty($ctrl->post) ){
  $ctrl->obj = $ctrl->get_model($ctrl->post);
}
