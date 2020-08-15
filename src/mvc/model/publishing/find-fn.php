<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model*/
if (isset($model->data['fns'])) {
  $fs = new \bbn\file\system();
  $sources = [
    // bbnjs
    BBN_CDN_PATH.'lib/bbn-js/1.0.1/src',
    // bbn-vue
    BBN_CDN_PATH.'lib/bbn-vue/2.0.2/src',
    BBN_APP_PATH.'src',
    BBN_LIB_PATH.'bbn'
  ];
  $res = [];
  $num = [];
  $fns = array_map(function($a) use (&$res, &$num) {
    $res['bbn.fn.'.$a] = [];
    $num['bbn.fn.'.$a] = 0;
    return 'bbn.fn.'.$a;
  }, $model->data['fns']);
  foreach ($sources as $src) {
    if ($found = $fs->search($fns, $src, true, false, 'js|php')) {
      foreach ($found as $fn => $files) {
        $res[$fn] = array_merge($res[$fn], $files);
        foreach ($files as $f) {
          $num[$fn] += count($f);
        }
      }
    }
  }
  /*
  foreach ($num as $fn => $n) {
    if ($n >= 5) {
      unset($res[$fn]);
    }
  }
  foreach ($sources as $src) {
    \bbn\x::dump(
      $src,
      $fs->search('bbn.fn.isFunction', $src, true, false, 'js|php'),
      '+++++++++++++++++++++++',
      $fs->search(['bbn.fn.ajax', 'bbn.fn.isFunction'], $src, true, false, 'js|php'),
      '------------------------------'
    );
  }
  die();
  */
  return $res;
}