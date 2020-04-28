<?php
/*
 * Describe what it does!
 *
 * @var $ctrl \bbn\mvc\controller 
 *
 */
$fs = new \bbn\file\system();
$fs->cd(BBN_LIB_PATH.'bbn/bbn/build/phpdox/xml');
$types = ['classes', 'traits', 'interfaces'];
$res = [];
$p = new \bbn\parsers\docblock('php');
foreach ($types as $type) {
  $items = $fs->get_files($type);
  foreach ($items as $it) {
    $name = basename($it, '.xml');
    $bits = \bbn\x::split($name, '_');
    if ($bits[0] === 'bbn') {
      array_shift($bits);
      $class_name = array_pop($bits);
      $current =& $res;
      $ns = '\\bbn\\';
      $path = 'php/';
      foreach ($bits as $i => $b) {
        $ns .= $b.'\\';
        $idx = \bbn\x::find($current, ['value' => $ns, 'type' => 'namespace']);
        $path .= $b.'/';
        if (!\bbn\x::has_prop($res, $idx)) {
          $idx = count($current);
          $current[] = [
            'type' => 'namespace',
            'value' => $ns,
            'text' => $b,
            'url' => $path.'home',
            'items' => []
          ];
        }
        $current =& $current[$idx]['items'];
      }
      $tmp = [
        'type' => $type,
        'value' => $ns.$class_name,
        'text' => $class_name,
        'url' => $path.$class_name,
        'items' => []
      ];
      $content = $fs->get_contents($it);
      $content2 = $fs->get_contents(BBN_LIB_PATH.'bbn/bbn/src/bbn/'.substr($tmp['url'], 4).'.php');
      $tmp['info'] = $content2 ? $p->parse($content2) : [];
      $xml = simplexml_load_string($content);
      $num = 0;
      foreach ($xml->method as $method) {
        $m = (array)$method;
        if ($m['@attributes']['visibility'] === 'public') {
          $tmp['items'][] = [
            'text' => $m['@attributes']['name'],
            'value' => $ns.$class_name.'::'.$m['@attributes']['name'],
          ];
        }
      }
      $current[] = $tmp;
    }
  }
}
/*
$parser = new \bbn\parsers\php();
$full = $parser->analyzeLibrary(BBN_LIB_PATH.'bbn/bbn/src/bbn', 'bbn');
$fs->put_contents(BBN_LIB_PATH.'bbn/bbn/bbn-php-full.json', json_encode($full, JSON_PRETTY_PRINT));
die();
*/
$ctrl->obj = $res;
$fs->put_contents(BBN_LIB_PATH.'bbn/bbn/bbn-php.json', json_encode($res, JSON_PRETTY_PRINT));