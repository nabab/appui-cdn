<?php

/** @var bbn\Mvc\Controller $ctrl */

if (isset($ctrl->post['components'])) {
  $ctrl->action();
}
else {
  $ctrl->combo("Static files creator", true);
}

