<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\mvc\controller 
 *
 */
if (!empty($ctrl->post['fns'])) {
  $ctrl->action();
}
else {
  $ctrl->combo(_("bbnjs publisher"));
}