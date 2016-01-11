<?php
/** @var $this \bbn\mvc\controller */

// SQLITE connection
$this->data['db'] = new \bbn\db\connection([
  'engine' => 'sqlite',
  'db' => '/home/mybbn/domains/cdn.mybbn.so/_appui/current/data/db/cdn.sqlite'
]);

