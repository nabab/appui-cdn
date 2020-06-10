<?php
/** @var $ctrl \bbn\mvc\controller */
// SQLITE connection
$path = $ctrl->data_path('appui-cdn');
$fs = new \bbn\file\system();
if ( !\defined('BBN_CDN_DB') && file_exists($path.'db/cdn.sqlite') ){
  define('BBN_CDN_DB', $path.'db/cdn.sqlite');
}
$ctrl->data['db'] = new \bbn\db([
  'engine' => 'sqlite',
  'db' => BBN_CDN_DB
]);
if ( !\defined('APPUI_CDN_ROOT') ){
  define('APPUI_CDN_ROOT', $ctrl->plugin_url('appui-cdn').'/');
}
return 1;