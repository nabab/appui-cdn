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

$ctrl->init_locale('it', 'bbn-cdn');