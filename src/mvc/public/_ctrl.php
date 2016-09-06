<?php
/** @var $ctrl \bbn\mvc\controller */

// SQLITE connection
$ctrl->data['db'] = new \bbn\db([
  'engine' => 'sqlite',
  'db' => '/home/mybbn/domains/cdn.mybbn.so/_appui/current/data/db/cdn.sqlite'
]);

$ctrl->data['root'] = $ctrl->say_dir().'/';
bindtextdomain('appui-cdn', BBN_LIB_PATH.'bbn/appui-cdn/src/locale');
setlocale(LC_ALL, "it_IT.utf8");
textdomain('appui-cdn');