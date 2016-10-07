<?php
/** @var $ctrl \bbn\mvc\controller */

// SQLITE connection
if ( !defined('BBN_CDN_DB') ){
  die("You need to define in order to use this plugin.");
}
$ctrl->data['db'] = new \bbn\db([
  'engine' => 'sqlite',
  'db' => BBN_CDN_DB
]);

$ctrl->data['root'] = $ctrl->say_dir().'/';
bindtextdomain('appui-cdn', BBN_LIB_PATH.'bbn/appui-cdn/src/locale');
setlocale(LC_ALL, "it_IT.utf8");
textdomain('appui-cdn');