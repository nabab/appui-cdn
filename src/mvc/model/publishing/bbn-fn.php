<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model*/
function pad(&$arr) {
  $max = [];
  foreach ($arr as $a) {
    if (count($a) > 1) {
      foreach ($a as $n => $v) {
        if ($n !== 'content') {
          $len = strlen($v);
          if (!isset($max[$n]) || ($max[$n] < $len)) {
            $max[$n] = $len;
          }
        }
      }
    }
  }
  foreach ($arr as &$a) {
    foreach ($a as $n => $v) {
      if ($n !== 'content') {
        $a[$n] = str_pad($v, $max[$n] + 1);
      }
    }
  }
  unset($a);
}
if ($model->has_data('fns')) {
  $dir = BBN_CDN_PATH.'lib/bbnjs/1.0.1/src/fn';
  $fs = new \bbn\file\system();
  $fs->cd($dir);
  $files = $fs->get_files('.');
  $res = [];
  foreach( $files as $i => $f ){
    if (!in_array(substr($f, 0, 1), ['.', '_'])) {
      $content = $fs->get_contents($f);
      $p = new \bbn\parsers\doc($content, 'js');
      $parser = $p->get_js();
      sort($parser['methods']);
      foreach ($parser['methods'] as &$m) {
        $m['source'] = $model->data['fns'][$m['name']] ?? '';
      }
      unset($m);
      $src = '/**'.PHP_EOL;
      $head = [];
      if (empty($parser['description'])) {
        $head[] = [
          'tag' => 'todo',
          'content' => 'Add a file header'
        ];
      }
      else {
        $h = $parser['description'][0];
        if (empty($h['text'])) {
          $head[] = [
            'tag' => 'todo',
            'content' => 'Add a file description (file tag)'
          ];
        }
        else{
          $head[] = [
            'tag' => 'file',
            'content' => $h['text']
          ];
        }
        $head[] = [
          'tag' => 'author',
          'content' => 'BBN Solutions <info@bbn.solutions>'
        ];
        $head[] = [
          'tag' => 'since',
          'content' => $h['since'] ?? ($h['created'] ?? date('d/m/Y'))
        ];
      }
      pad($head);
      foreach ($head as $h) {
        $src .= ' * @'.$h['tag'].$h['content'].PHP_EOL;
      }
      $src .= ' */'.PHP_EOL.PHP_EOL;
      $src .= <<<EOD
;((bbn) => {
  "use strict";

  Object.assign(bbn.fn, {

EOD;
      $params = ['param', 'returns', ''];
      foreach ($parser['methods'] as $meth) {
        $src .= '    /**'.PHP_EOL;
        $lines = [];
        $has_desc = false;
        if (!empty($meth['description'])) {
          $desc = \bbn\x::split($meth['description'], PHP_EOL);
          while (count($desc) && empty(trim($desc[0]))) {
            array_shift($desc);
          }
          if (!empty($desc[0])) {
            $tmp = trim(array_shift($desc));
            if (substr($tmp, -1) !== '.') {
              $tmp .= '.';
            }
            $src .= '     * '.$tmp.PHP_EOL.'     *'.PHP_EOL;
            $has_desc = true;
          }
          while (count($desc) && empty(trim($desc[0]))) {
            array_shift($desc);
          }
          $num = 0;
          foreach ($desc as $d) {
            $d = trim($d);
            if ($num || !empty($d)) {
              $num++;
              $src .= '     * '.$d.PHP_EOL;
            }
          }
          if ($num) {
            $src .= '     *'.PHP_EOL;
          }
        }
        $lines[] = [
          'tag' => 'method',
          'content' => $meth['name']
        ];
        if (!$has_desc) {
          $lines[] = [
            'tag' => 'todo',
            'content' => 'Add method description for '.$meth['name']
          ];
        }
        $lines[] = [
          'tag' => 'global'
        ];
        $lines[] = [
          'tag' => 'memberof',
          'content' => 'bbn.fn'
        ];
        if (!empty($meth['fires'])) {
          foreach ($meth['fires'] as $param) {
            $lines[] = [
              'tag' => 'fires',
              'type' => isset($param['type']) ? '{'.$param['type'].'}' : '{*}'
            ];
          }
        }
        if (!empty($meth['param'])) {
          foreach ($meth['param'] as $param) {
            $lines[] = [
              'tag' => 'param',
              'type' => isset($param['type']) ? '{'.$param['type'].'}' : '{*}',
              'name' => $param['name'],
              'content' => $param['text'] ?? ''
            ];
          }
        }
        $return = $meth['return'] ?? ($meth['returns'] ?? ['type' => '*']);
        $lines[] = [
          'tag' => 'returns',
          'type' => $return['type'],
          'content' => $return['text'] ?? ''
        ];
        pad($lines);
        foreach ($lines as $line) {
          $src .= '     * @'.\bbn\x::join($line, '').PHP_EOL;
        }
        $src .= '     */'.PHP_EOL.'    '.(empty($meth['source']) ? '' : $meth['source'].',').PHP_EOL.PHP_EOL;
      }
      $src .= '  });'.PHP_EOL.'})(bbn);'.PHP_EOL;
      file_put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/doc/src/'.$f, $src);
      $parser['new'] = $src;
      $res[$f] = $parser;
    }
  }
  $res['static_path'] = BBN_STATIC_PATH;
  return $res;
}