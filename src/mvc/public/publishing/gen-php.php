<?php
/**
 * Describe what it does!
 */
use bbn\x;
/** @var $ctrl \bbn\mvc\controller */
$fs = new \bbn\file\system();
$fs->cd(BBN_LIB_PATH.'bbn/bbn/build/phpdox/xml');
$types = [
  'class' => 'classes',
  'trait' => 'traits',
  'interface' => 'interfaces'
];
$res = [];
$p = new \bbn\parsers\docblock('php');
$parser = new \bbn\parsers\php();
$full = $parser->analyzeLibrary(BBN_LIB_PATH.'bbn/bbn/src/bbn', 'bbn');
$namespaces = [];
foreach ($types as $singular => $type) {
  $items = $fs->get_files($type);
  foreach ($items as $it) {
    $name = basename($it, '.xml');
    $bits = x::split($name, '_');
    if ($bits[0] === 'bbn') {
      array_shift($bits);
      $class_name = array_pop($bits);
      $current =& $res;
      foreach ($bits as $i => $b) {
        $idx = x::find($current, ['value' => $b.'/']);
        if (!x::has_prop($res, $idx)) {
          $idx = count($current);
          $current[] = [
            'type' => 'namespace',
            'value' => $b.'/',
            'text' => $b.'\\',
            'items' => []
          ];
        }
        $current =& $current[$idx]['items'];
      }
    }
  }
  foreach ($items as $it) {
    $name = basename($it, '.xml');
    $bits = x::split($name, '_');
    if ($bits[0] === 'bbn') {
      array_shift($bits);
      $class_name = array_pop($bits);
      $current =& $res;
      $path_bbnio = 'bbn-php/doc/'.$singular.'/';
      $path = '';
      foreach ($bits as $i => $b) {
        $idx = x::find($current, ['value' => $b.'/']);
        if ($idx === null) {
          throw new Error("Impossible to find path $b/");
        }
        $path .= $b.'/';
        $current =& $current[$idx]['items'];
      }
      $content = $fs->get_contents($it);
      $content2 = $fs->get_contents(BBN_LIB_PATH.'bbn/bbn/src/bbn/'.$path.$class_name.'.php');
      $info = $content2 ? $p->parse($content2) : [];
      $tmp = [
        'type' => $singular,
        'value' => $class_name,
        'text' => $class_name,
        'url' => $path_bbnio.$path.$class_name,
        'desc' => $info['summary'] ?? '',
        'items' => []
      ];
      $xml = simplexml_load_string($content);
      $path_bbnio = str_replace('php/doc/'.$singular.'/', 'php/doc/method/', $path_bbnio);
      $num = 0;
      $php_doc = $full[$path.$class_name.'.php'] ?? false;
      if ($xml->constructor) {
        $m = (array)$xml->constructor[0];
        $desc = '';
        if ($php_doc && $php_doc['methods'][$m['@attributes']['name']]) {
          $desc = $php_doc['methods'][$m['@attributes']['name']]['summary'] ?? '';
        }
        $tmp['items'][] = [
          'type' => 'method',
          'class' => $path.$class_name,
          'text' => $m['@attributes']['name'],
          'value' => $m['@attributes']['name'],
          'url' => $path_bbnio.$path.$class_name.'/'.$m['@attributes']['name'],
          'desc' => $desc
        ];
      }
      foreach ($xml->method as $method) {
        $m = (array)$method;
        if ($m['@attributes']['visibility'] === 'public') {
          $desc = '';
          if ($php_doc && $php_doc['methods'][$m['@attributes']['name']]) {
            $desc = $php_doc['methods'][$m['@attributes']['name']]['summary'] ?? '';
          }
          $tmp['items'][] = [
            'type' => 'method',
            'class' => $path.$class_name,
            'text' => $m['@attributes']['name'],
            'value' => $m['@attributes']['name'],
            'url' => $path_bbnio.$path.$class_name.'/'.$m['@attributes']['name'],
            'desc' => $desc
          ];
        }
      }
			x::sort_by($tmp['items'], 'text');
      $current[] = $tmp;
    }
  }
}

foreach ($full as $filename => $cls) {
  $fs->create_path(BBN_LIB_PATH.'bbn/bbn/json-doc/'.dirname($filename));
  $fs->put_contents(BBN_LIB_PATH.'bbn/bbn/json-doc/'.substr($filename, 0, -4).'.json', json_encode($cls, JSON_PRETTY_PRINT));
}
$ctrl->obj = $res;
$fs->put_contents(BBN_LIB_PATH.'bbn/bbn/bbn-php.json', json_encode($res, JSON_PRETTY_PRINT));