<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\Mvc\Controller 
 *
 */
use bbn\X;

if ($ctrl->hasArguments()) {
  $path = dirname($ctrl->getPath()).'/';
  if ((strpos($ctrl->arguments[0], '/') === false) && $ctrl->modelExists($path.$ctrl->arguments[0])) {
    $ctrl->obj = $ctrl->addData($ctrl->post)
      ->getObjectModel($path.$ctrl->arguments[0]);
    if (!X::countProperties($ctrl->obj)) {
      $ctrl->obj->success = false;
    }
  }
}
else {
  $ctrl->combo(_("Help for our libraries"));
}

