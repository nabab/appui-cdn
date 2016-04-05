<?php
/** @var $this \bbn\mvc\controller */

// SQLITE connection
$this->data['db'] = new \bbn\db([
  'engine' => 'sqlite',
  'db' => '/home/mybbn/domains/cdn.mybbn.so/_appui/current/data/db/cdn.sqlite'
]);

