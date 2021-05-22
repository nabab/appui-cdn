<?php

/** @var $ctrl \bbn\Mvc\Controller */

if (isset($ctrl->post['components'])) {
  $ctrl->action();
}
else {
  $ctrl->combo("Static files creator", true);
}

