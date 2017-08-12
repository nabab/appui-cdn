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
  if ( substr($ctrl->post['url'], -4) === '.git' ){
    $ctrl->post['url'] = substr($ctrl->post['url'], 0, -4);
  }
  $ctrl->post['url'] = explode('/', $ctrl->post['url']);
  $ctrl->data = \bbn\x::merge_arrays($ctrl->data, [
    'git_user' =>  $ctrl->post['url'][0],
    'git_repo' => $ctrl->post['url'][1]
  ]);
  if ( isset($ctrl->post['only_info']) ){
    $ctrl->data['only_info'] = $ctrl->post['only_info'];
  }
  $ctrl->obj = $ctrl->get_model();
}
else {
 $ctrl->obj->error = _("You must insert a valid URL");
}