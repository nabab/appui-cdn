<?php
$res =[
  'success' => false,
  'folders' => false
];
if ( !empty($model->data['folder']) && !empty($model->data['version_folder']) ){
  $path = BBN_CDN_PATH.'lib/'.$model->data['folder'].'/'.$model->data['version_folder'];
  //die(var_dump($path, \is_dir($path)));
  if ( is_dir($path) ){
    if ( !empty(\bbn\file\dir::delete($path)) ){
      $info = $model->get_model('./../../data/version/add', array_merge($ctrl->data, ['folder' => $model->data['folder']]));
      $res['success'] = true;
      $res['folders'] = isset($info['folders_versions']) ? $info['folders_versions'] : false;
    }
  }
}
 return $res;
