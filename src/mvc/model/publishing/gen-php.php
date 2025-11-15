<?php
/**
 * Describe what it does!
 */
use bbn\X;
use bbn\Str;
use bbn\Parsers\Docblock;
use bbn\Parsers\Php;
use bbn\File\System;

/** @var bbn\Mvc\Model $model */

$parser = new Php();
$path = constant('BBN_LIB_PATH') . 'bbn/bbn/src/bbn';
$all = $parser->getLibraryClasses($path, 'bbn');
$res = [];

/** @var System Filesystem object */
$fs = new System();
// Cding in documentation folder
$fs->delete(constant('BBN_LIB_PATH') . 'bbn/bbn/json-doc', true);
$docRoot = constant('BBN_LIB_PATH') . 'bbn/bbn/json-doc/';
foreach ($all as $a) {
  $cls = $parser->analyzeClass($a['class'], $path);
  $spath = dirname($docRoot . $a['file']);
  $fs->createPath($spath);
  $spath .= '/';
  $fs->putContents($spath . $a['name'] . '.json', json_encode($cls, JSON_PRETTY_PRINT));
  if (empty($cls['methods'])) {
    continue;
  }

  //X::ddump($cls);
  $cur =& $res;
  $bits = X::split(Str::sub($a['class'], 5), '\\');
  while (count($bits) > 1) {
    $namespace = array_shift($bits);
    $idx = X::search($cur, ['type' => 'namespace', 'value' => $namespace . '/']);
    if ($idx !== null) {
      $cur =& $cur[$idx]['items'];
    }
    else {
      $idx = count($cur);
      $cur[] = [
        'type' => 'namespace',
        'value' => $namespace . '/',
        'text' => $namespace . '\\',
        'desc' => $a['summary'] ?? $a['description'] ?? '',
        'items' => [],
      ];
      $cur =& $cur[$idx]['items'];
    }
  }

  $lastmod = filemtime($path . '/' . $a['file']);
  $items = [];
  foreach ($cls['methods'] as $meth) {
    $items[] = [
      'type' => 'method',
      'class' => $a['class'],
      'lastmod' => $lastmod,
      'text' => $meth['name'],
      'desc' => $meht['summary'] ?? $meth['description'] ?? '',
      'value' => $meth['name'],
    ];
  }
  $cur[] = [
    'type' => $a['type'],
    'value' => $a['name'],
    'text' => $a['name'],
    'lastmod' => $lastmod,
    'items' => $items,
  ];
}

$fs->putContents(constant('BBN_LIB_PATH') . 'bbn/bbn/bbn-php.json', json_encode($res, JSON_PRETTY_PRINT));

/*
$types = [
  'class' => 'classes',
  'trait' => 'traits',
  'interface' => 'interfaces'
];
*/

return [
  'num' => count($all),
  'success' => true,
];
