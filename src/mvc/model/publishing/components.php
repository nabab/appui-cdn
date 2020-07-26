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
$expressions = [];
$less = new \lessc();
$fs->delete('dist/js');
$fs->delete('dist/vue');
$fs->create_path('dist/js/components');
$fs->delete('dist/vue/components');
foreach ($components as $component) {
  $cp = basename($component);
  $html = $fs->is_file($p.$cp.'/'.$cp.'.html') ? $fs->get_contents($p.$cp.'/'.$cp.'.html') : '';
  $css = $fs->is_file($p.$cp.'/'.$cp.'.less') ? $fs->get_contents($p.$cp.'/'.$cp.'.less') : '';
  $js = $fs->is_file($p.$cp.'/'.$cp.'.js') ? $fs->get_contents($p.$cp.'/'.$cp.'.js') : '';
  $cfg = $fs->is_file($p.$cp.'/bbn.json') ? json_decode($fs->get_contents($p.$cp.'/bbn.json'), true) : '';
  if ($js) {
    $cp_files = array_filter($fs->get_files($p.$cp, true, false), function ($a) use ($cp, $p) {
      $ext = \bbn\str::file_ext($a);
      return !in_array($ext, ['md', 'lang', 'pdf', 'bak', 'json']) &&
        !in_array($a, [$p.$cp.'/'.$cp.'.html', $p.$cp.'/'.$cp.'.less', $p.$cp.'/'.$cp.'.js']);
    });
    $ar_cfg = false;
    if (!empty($cfg['dependencies'])) {
      $cdn_cfg = new \bbn\cdn\config(
        BBN_SHARED_PATH.'?lib='
        .\bbn\x::join(array_keys($cfg['dependencies']), ',')
      );
      $ar_cfg = $cdn_cfg->get();
    }
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
        if (isset($item['type']) && ($item['type'] === 'mixins')) {
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
            'url' => 'bbn-vue/component/'. $cp .'/doc/'.$i.'#'.$name,
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
    $fs->create_path('dist/js/components/'.$cp);
    $fs->create_path('dist/vue/components/'.$cp);
    $st_vue = '';
    $css_dependencies = [];
    $st_js = '(bbn_resolve) => { ((bbn) => {'.PHP_EOL;
    $dep_st = false;
    if ($ar_cfg && !empty($ar_cfg['content']) && !empty($ar_cfg['content']['includes'])) {
      $files_js = [];
      foreach ($ar_cfg['content']['includes'] as $nc) {
        $files_css = [];
        foreach ($nc['js'] as $ncjs) {
          $files_js[] = 'gh/'.$nc['git'].'@'.$nc['version'].'/'.$ncjs;
        }
        foreach ($nc['css'] as $nccss) {
          $files_css[] = 'gh/'.$nc['git'].'@'.$nc['version'].'/'.$nccss;
        }
        if (count($files_css)) {
          $css_dependencies[] = 'https://cdn.jsdelivr.net/combine/'.\bbn\x::join($files_css, ',');
        }
      }
      if (count($files_js)) {
        $dep_st = 'https://cdn.jsdelivr.net/combine/'.\bbn\x::join($files_js, ',');
        $st_js .= <<<JAVASCRIPT
let script_dep = document.createElement('script');
script_dep.setAttribute('src', "$dep_st");
script_dep.onload = () => {

JAVASCRIPT;
      }
      if (count($css_dependencies)) {
        $st_js .= PHP_EOL.'let css_dependency;'.PHP_EOL;
        foreach ($css_dependencies as $css_d) {
          $st_js .= <<<JAVASCRIPT
css_dependency = document.createElement('link');
css_dependency.setAttribute('rel', "stylesheet");
css_dependency.setAttribute('href', "$css_d");
document.head.insertAdjacentElement('beforeend', css_dependency);

JAVASCRIPT;
        }
      }
    }
    if ($html) {
      $st_vue .= '<template>'.PHP_EOL.$html.PHP_EOL.'</template>'.PHP_EOL;
      $content = str_replace('`', '\\`', str_replace('\\', '\\\\', $html));
      $st_js .= <<<JAVASCRIPT
let script = document.createElement('script');
script.innerHTML = `$content`;
script.setAttribute('id', 'bbn-tpl-component-$cp');
script.setAttribute('type', 'text/x-template');
document.body.insertAdjacentElement('beforeend', script);

JAVASCRIPT;
    }
    $st_vue .= '<script>'.PHP_EOL.'  module.exports = '.$js.PHP_EOL.'</script>'.PHP_EOL;
    if ($css && ($css = $less->compile($css))) {
      $st_vue .= '<style scoped>'.PHP_EOL.$css.PHP_EOL.'</style>'.PHP_EOL;
      $content = str_replace('`', '\\`', $css);
      $fs->put_contents('dist/js/components/'.$cp.'/'.$cp.'.css', $content);
      $st_js .= <<<JAVASCRIPT
let css = document.createElement('link');
css.setAttribute('rel', "stylesheet");
css.setAttribute('href', bbn.vue.libURL + "dist/js/components/$cp/$cp.css");
document.head.insertAdjacentElement('beforeend', css);

JAVASCRIPT;
    }
    $st_js .= $js.PHP_EOL.'if (bbn_resolve) {bbn_resolve("ok");}'.PHP_EOL;
    if ($dep_st) {
      $st_js .= '};'.PHP_EOL.'document.head.insertAdjacentElement("beforeend", script_dep);'.PHP_EOL;
    }
    $st_js .= '})(bbn); }';
    foreach ($cp_files as $cp_file) {
      $fs->copy($cp_file, 'dist/js/components/'.$cp.'/'.basename($cp_file));
      $fs->copy($cp_file, 'dist/vue/components/'.$cp.'/'.basename($cp_file));
    }
    $fs->put_contents('dist/js/components/'.$cp.'/'.$cp.'.js', $st_js);
    $fs->put_contents('dist/vue/components/'.$cp.'/'.$cp.'.vue', $st_vue);
    if ($fs->is_file('dist/vue/components/'.$cp.'/'.$cp.'.vue')) {
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
      'desc' => $methods[$method_name]['summary']
    ];
  }
}
$res[1]['items'] = $fns;
$fs->put_contents($dir.'/bbn-vue.json', json_encode($res, JSON_PRETTY_PRINT));
$files = json_decode('["src\/vars.js","src\/methods.js","src\/mixins\/basic.js","src\/mixins\/empty.js","src\/mixins\/dimensions.js","src\/mixins\/position.js","src\/mixins\/dropdown.js","src\/mixins\/keynav.js","src\/mixins\/toggle.js","src\/mixins\/localStorage.js","src\/mixins\/data.js","src\/mixins\/dataEditor.js","src\/mixins\/events.js","src\/mixins\/list.js","src\/mixins\/memory.js","src\/mixins\/input.js","src\/mixins\/resizer.js","src\/mixins\/close.js","src\/mixins\/field.js","src\/mixins\/view.js","src\/mixins\/observer.js","src\/mixins\/keepCool.js","src\/mixins\/url.js","src\/mixins.js","src\/defaults.js","src\/init.js"]');
$st = '';
foreach ($files as $f) {
  $st .= $fs->get_contents($f).PHP_EOL.PHP_EOL.PHP_EOL;
}
$fs->put_contents('dist/js/bbn-vue.js', $st);

$fs->put_contents('dist/js/bbn-vue.min.js', JShrink\Minifier::minify($st, ['flaggedComments' => false]));
return $res;