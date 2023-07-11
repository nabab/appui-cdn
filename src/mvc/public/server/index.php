<?php
use bbn\Cdn;
use bbn\Db;

/** @var bbn\Mvc\Controller  */
$db = new Db([
  'engine' => 'sqlite',
  'db' => $ctrl->dataPath() . 'db/cdn.sqlite'
]);
$cdn = new Cdn($_SERVER['REQUEST_URI'], $db);

$cdn->process();
if ( $cdn->check() ){
  $cdn->output();
  die();
}
else {
  die('Impossible to find the requested file.');
}
