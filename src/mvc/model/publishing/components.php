<?php
/*
 * Describe what it does!
 *
 **/

/** @var $model \bbn\mvc\model*/
$dir = BBN_CDN_PATH.'lib/bbn-vue/2.0.2';
$fs = new \bbn\file\system();
$fs->cd($dir);
$p = 'src/components/';
$components = $fs->get_dirs($p);
$res = ['num' => 0];
foreach ($components as $component) {
  $cp = basename($component);
  $html = $fs->is_file($p.$cp.'/'.$cp.'.html') ? $fs->get_contents($p.$cp.'/'.$cp.'.html') : '';
  $css = $fs->is_file($p.$cp.'/'.$cp.'.less') ? $fs->get_contents($p.$cp.'/'.$cp.'.less') : '';
  $js = $fs->is_file($p.$cp.'/'.$cp.'.js') ? $fs->get_contents($p.$cp.'/'.$cp.'.js') : '';
  if ($js) {
    $st = '';
    if ($html) {
      $st .= '<template>'.PHP_EOL.$html.PHP_EOL.'</template>'.PHP_EOL;
    }
    $st .= '<script>'.PHP_EOL.'  module.exports = '.$js.PHP_EOL.'</script>'.PHP_EOL;
    if ($css) {
      $less = new \lessc();
      $css = $less->compile($css);
      $st .= '<style scoped>'.PHP_EOL.$css.PHP_EOL.'</style>'.PHP_EOL;
    }
    $fs->put_contents('dist/components/'.$cp.'.vue', $st);
    if ($fs->is_file('dist/components/'.$cp.'.vue')) {
      $res['num']++;
    }
  }
}
return $res;