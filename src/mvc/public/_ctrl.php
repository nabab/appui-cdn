<?php
/** @var $ctrl \bbn\Mvc\Controller */
// SQLITE connection
$path = $ctrl->dataPath('appui-cdn');
$fs = new \bbn\File\System();
if ( !\defined('BBN_CDN_DB') && file_exists($path.'db/cdn.sqlite') ){
  define('BBN_CDN_DB', $path.'db/cdn.sqlite');
}
$ctrl->data['db'] = new \bbn\Db([
  'engine' => 'sqlite',
  'db' => BBN_CDN_DB
]);
if ( !\defined('APPUI_CDN_ROOT') ){
  define('APPUI_CDN_ROOT', $ctrl->pluginUrl('appui-cdn').'/');
}
return 1;