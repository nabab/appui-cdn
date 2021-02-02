<?php
$res = [
 'success' => false,
 'data' => []
];

$file_contents = [];

if ( !empty($model->data['file'])  &&
 !empty($model->data['library']) &&
 !empty($model->data['version'])
){

  $path = BBN_CDN_PATH.'lib'. '/'. $model->data['library'].'/'.$model->data['version'];
  $content = \bbn\File\Dir::scan($path,'file',true);

  if( !empty($content) ){
    foreach ($content as $i => $file){
      if( !empty(strpos($file, $model->data['file']))  ){        
        $file_contents = json_decode(file_get_contents($file));
        break;
      }
    }
  }

  $res['success'] = true;
  $res['data'] = $file_contents;

}
return $res;
