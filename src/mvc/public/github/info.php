<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 17:41
 */
use bbn\X;

/** @var $ctrl \bbn\Mvc\Controller */
if ( !empty($ctrl->post['url']) &&
  \bbn\Str::isUrl($ctrl->post['url']) &&
  ((strpos($ctrl->post['url'], 'http://github.com/') === 0) || (strpos($ctrl->post['url'], 'https://github.com/') === 0))
){
  $ctrl->post['url'] = str_replace('http://github.com/', '', Str_replace('https://github.com/', '', $ctrl->post['url']));
  if ( substr($ctrl->post['url'], -4) === '.git' ){
    $ctrl->post['url'] = substr($ctrl->post['url'], 0, -4);
  }
  $ctrl->post['url'] = explode('/', $ctrl->post['url']);
  $ctrl->data = X::mergeArrays($ctrl->data, [
    'git_user' =>  $ctrl->post['url'][0],
    'git_repo' => $ctrl->post['url'][1]
  ]);
  if ( !empty($ctrl->post['info_package_json']) ){
    $ctrl->data = X::mergeArrays($ctrl->data, [
      'info_package_json' => $ctrl->post['info_package_json']
    ]);
  }
  if ( isset($ctrl->post['only_info']) ){
    $ctrl->data['only_info'] = $ctrl->post['only_info'];
  }
  $ctrl->obj = $ctrl->getModel();
}
else {
 $ctrl->obj->error = _("You must insert a valid URL");
}
