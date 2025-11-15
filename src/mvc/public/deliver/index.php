<?php
use bbn\Str;

if ( Str::pos($_SERVER['REQUEST_URI'], BBN_SHARED_PATH) === 0 ){
  $cdn = new \bbn\Cdn($_SERVER['REQUEST_URI'], $ctrl->data['db']);
  $cdn->process();
  if ( $cdn->check() ){
    $cdn->output();
  }
  else{
    if ( BBN_IS_DEV ){
      var_dump($cdn);
    }
    die('<h1>'._('Impossible to find the requested file').'</h1>');
  }
}
