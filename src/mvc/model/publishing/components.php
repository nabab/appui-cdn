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
$num = 0;
$vue = [];
$mixins = [];
foreach ($components as $component) {
  $cp = basename($component);
  $html = $fs->is_file($p.$cp.'/'.$cp.'.html') ? $fs->get_contents($p.$cp.'/'.$cp.'.html') : '';
  $css = $fs->is_file($p.$cp.'/'.$cp.'.less') ? $fs->get_contents($p.$cp.'/'.$cp.'.less') : '';
  $js = $fs->is_file($p.$cp.'/'.$cp.'.js') ? $fs->get_contents($p.$cp.'/'.$cp.'.js') : '';
  if ($js) {
    $parser = new \bbn\parsers\doc($js, 'vue');
    $doc = $parser->get_vue();
    // bbn.io
    $tmp = [
      'text' => $cp,
      'url' => 'bbn-vue/component/'. $cp .'/overview',
      'props' => [],
      'methods' => [],
      'mixins' => []
    ];
    $to_remove = [];
    foreach (['props', 'methods', 'mixins'] as $i) {
      $item =& $tmp[$i];
      foreach ($doc[$i] as $d) {
        if (empty($d['name'])) {
          \bbn\x::log($d);
          continue;
        }
        if (substr($d['name'], 0, 1) === '_') {
          continue;
        }
        $bits = \bbn\x::split($d['name'], '.');
        $name = end($bits);
        if ($item['type'] === 'mixins') {
          $mixin = substr($name, 0, -9);
          if (!isset($mixins[$mixin])) {
            $c2 = $fs->get_contents($dir.'/src/mixins/'.$mixin.'.js');
            if (!$c2) {
              die(var_dump($cp, $dir.'/src/mixins/'.$mixin.'.js'));
            }
            $parser2 = new \bbn\parsers\doc($c2, 'vue');
            if ($doc2 = $parser2->get_vue()) {
              if (!empty($doc2['components'])) {
                $doc2 = $doc2['components'][0];
              }
              $mixins[$mixin] = [
                'props' => [],
                'methods' => []
              ];
              foreach (['props', 'methods'] as $k) {
                $item2 =& $mixins[$mixin][$k];
                foreach ($doc2[$k] as $d2) {
                  if (substr($d2['name'], 0, 1) !== '_') {
                    $item2[] = [
                      'text' => $d2['name'],
                      'desc' => $d2['description'] ?? '',
                      'url' => 'bbn-vue/mixins/'. $name .'/'.$item2['type'].'/'.$d2['name'],
                    ];
                  }
                }
              }
              unset($item2);
            }
          }
          $stmp['items'] = $mixins[$mixin];
        }
        else {
          $stmp = [
            'text' => $name,
            'desc' => $d['description'] ?? '',
            'url' => 'bbn-vue/component/'. $cp .'/'.$i.'/'.$name,
          ];
        }
        $item[] = $stmp;
      }
    }
    unset($item);
    foreach ($to_remove as $rem) {
      $idx = \bbn\x::find($tmp['items'], ['value' => $rem]);
      array_splice($tmp['items'], $idx, 1);
    }
    $vue[] = $tmp;
    // .vue files
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
      $num++;
    }
  }
}
$res = [
  [
    'text' => _('Components'),
    'value' => 'vue/component',
    'items' => $vue
  ], [
    'text' => _('Functions'),
    'value' => 'vue/functions',
  ]
];
$fns = [];
$p = new \bbn\parsers\docblock('js');
$content = $fs->get_contents('src/methods.js');
$parser = $p->parse($content);
$params = ['param', 'returns', ''];
$methods = [];
$parser['methods'] = array_filter($parser['methods'], function($a) {
  return substr($a['name'], 0, 1) !== '_';
});
foreach ($parser['methods'] as $meth) {
  $methods[$meth['name']] = $meth;
}
$method_names = array_keys($methods);
sort($method_names);
$toc = '';
foreach ($method_names as $method_name) {
  if (substr($method_name, 0, 1) !== '_') {
    $fns[] = [
      'text' => $method_name,
      'url' => 'bbn-vue/function/'.$method_name,
      'desc' => $methods[$method_name]['description']
    ];
  }
}
$res[1]['items'] = $fns;
$fs->put_contents($dir.'/bbn-vue.json', json_encode($res, JSON_PRETTY_PRINT));
return $res;