<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 17:41
 */
/** @var $ctrl \bbn\mvc\controller */
if ( !empty($ctrl->post['url']) &&
  \bbn\str::is_url($ctrl->post['url']) &&
  ((strpos($ctrl->post['url'], 'http://github.com/') === 0) || (strpos($ctrl->post['url'], 'https://github.com/') === 0))
){
  $ctrl->post['url'] = str_replace('http://github.com/', '', str_replace('https://github.com/', '', $ctrl->post['url']));
  $ctrl->post['url'] = explode('/', $ctrl->post['url']);
  $ctrl->data = \bbn\x::merge_arrays($ctrl->data, [
    'git_user' =>  $ctrl->post['url'][0],
    'git_repo' => $ctrl->post['url'][1]
  ]);
  $ctrl->obj->data = $ctrl->get_model();
}
else {
 $ctrl->obj->error = _("You don't a valid GitHub URL for this library.");
}