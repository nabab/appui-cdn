<?php
if ( !empty($ctrl->post) ){
  $ctrl->obj = $ctrl->getModel($ctrl->post);
}
