<?php
/*
 * Describe what it does!
 *
 **/
use bbn\X;

/** @var $model \bbn\Mvc\Model*/
$dir = BBN_CDN_PATH.'lib/bbn-vue/2.0.2';
$fs  = new \bbn\File\System();
$fs->cd($dir);
$p           = 'src/components/';
$components  = $fs->getDirs($p);
$num         = 0;
$vue         = [];
$mixins      = [];
$expressions = [];
$less        = new \lessc();
$fs->delete('dist/js');
$fs->delete('dist/vue');
$fs->createPath('dist/js/components');
if (!defined('BBN_CDN_DB')) {
  throw new \Exception("The CDN DB path is not defined");
}
$sqlite = new bbn\Db([
  'engine' => 'sqlite',
  'db' => BBN_CDN_DB
]);
$fs->delete('dist/vue/components');
foreach ($components as $component) {
  $cp   = basename($component);
  $html = $fs->isFile($p.$cp.'/'.$cp.'.html') ? $fs->getContents($p.$cp.'/'.$cp.'.html') : '';
  $css  = $fs->isFile($p.$cp.'/'.$cp.'.less') ? $fs->getContents($p.$cp.'/'.$cp.'.less') : '';
  $js   = $fs->isFile($p.$cp.'/'.$cp.'.js') ? $fs->getContents($p.$cp.'/'.$cp.'.js') : '';
  $cfg  = $fs->isFile($p.$cp.'/bbn.json') ? json_decode($fs->getContents($p.$cp.'/bbn.json'), true) : '';
  if ($js) {
    $cp_files = array_filter(
      $fs->getFiles($p.$cp, true, false), function ($a) use ($cp, $p) {
        $ext = \bbn\Str::fileExt($a);
        return !in_array($ext, ['md', 'lang', 'pdf', 'bak', 'json']) &&
        !in_array($a, [$p.$cp.'/'.$cp.'.html', $p.$cp.'/'.$cp.'.less', $p.$cp.'/'.$cp.'.js']);
      }
    );
    $ar_cfg   = false;
    if (!empty($cfg['dependencies'])) {

      $cdn_cfg = new \bbn\Cdn\Config(
        BBN_SHARED_PATH.'?lib='
            .x::join(array_keys($cfg['dependencies']), ','),
        $sqlite
      );
      $ar_cfg  = $cdn_cfg->get();
    }

    $parser = new \bbn\Parsers\Doc($js, 'vue');
    $doc    = $parser->getVue();
    // bbn.io
    $tmp       = [
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
          X::log($d);
          continue;
        }

        if (substr($d['name'], 0, 1) === '_') {
          continue;
        }

        $bits = X::split($d['name'], '.');
        $name = end($bits);
        if (isset($item['type']) && ($item['type'] === 'mixins')) {
          $mixin = substr($name, 0, -9);
          if (!isset($mixins[$mixin])) {
            $c2 = $fs->getContents($dir.'/src/mixins/'.$mixin.'.js');
            if (!$c2) {
              throw new \Exception(
                _("Impossible to find the content of the mixins")
                .' '.$dir.'/src/mixins/'.$mixin.'.js'
              );
            }

            $parser2 = new \bbn\Parsers\Doc($c2, 'vue');
            if ($doc2 = $parser2->getVue()) {
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
      $idx = X::find($tmp['items'], ['value' => $rem]);
      if ($idx === null) {
        throw new Error("Impossible to find item $rem");
      }

      array_splice($tmp['items'], $idx, 1);
    }

    $vue[] = $tmp;
    // .vue files
    $fs->createPath('dist/js/components/'.$cp);
    $fs->createPath('dist/vue/components/'.$cp);
    $st_vue           = '';
    $css_dependencies = [];
    $st_js            = '(bbn_resolve) => { ((bbn) => {'.PHP_EOL;
    $dep_st           = false;
    if ($ar_cfg && !empty($ar_cfg['content']) && !empty($ar_cfg['content']['includes'])) {
      $files_js = [];
      foreach ($ar_cfg['content']['includes'] as $nc) {
        $files_css = [];
        foreach ($nc['js'] as $ncjs) {
          if (($nc['mode'] === 'npm') && !empty($nc['npm'])) {
            $files_js[] = 'npm/'.$nc['npm'].'@'.$nc['version'].'/'.$ncjs;
          }
          elseif (!empty($nc['git'])) {
            $files_js[] = 'gh/'.$nc['git'].'@'.$nc['version'].'/'.$ncjs;
          }
        }

        foreach ($nc['css'] as $nccss) {
          if (($nc['mode'] === 'npm') && !empty($nc['npm'])) {
            $files_css[] = 'npm/'.$nc['npm'].'@'.$nc['version'].'/'.$nccss;
          }
          elseif (!empty($nc['git'])) {
            $files_css[] = 'gh/'.$nc['git'].'@'.$nc['version'].'/'.$nccss;
          }
        }

        if (count($files_css)) {
          $css_dependencies[] = 'https://cdn.jsdelivr.net/combine/'.x::join($files_css, ',');
        }
      }

      if (count($files_js)) {
        $dep_st = 'https://cdn.jsdelivr.net/combine/'.x::join($files_js, ',');
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
      $st_js  .= <<<JAVASCRIPT
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
      $fs->putContents('dist/js/components/'.$cp.'/'.$cp.'.css', $content);
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

    $fs->putContents('dist/js/components/'.$cp.'/'.$cp.'.js', $st_js);
    $fs->putContents('dist/vue/components/'.$cp.'/'.$cp.'.vue', $st_vue);
    if ($fs->isFile('dist/vue/components/'.$cp.'/'.$cp.'.vue')) {
      $num++;
    }
  }
}

$res               = [
  [
    'text' => _('Components'),
    'value' => 'vue/component',
    'items' => $vue
  ], [
    'text' => _('Functions'),
    'value' => 'vue/functions',
  ]
];
$fns               = [];
$p                 = new \bbn\Parsers\Docblock('js');
$content           = $fs->getContents('src/methods.js');
$parser            = $p->parse($content);
$params            = ['param', 'returns', ''];
$methods           = [];
$tern_json = [];
$parser['methods'] = array_filter(
  $parser['methods'], function ($a) {
    return substr($a['name'], 0, 1) !== '_';
  }
);
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
    $tern_json[$method_name] = [
      "!type" => 'fn() -> mixed',
      "!url" => "https://bbn.io/'bbn-vue/function/".$method_name,
      "!doc" => $methods[$method_name]['summary']
    ];
  }
}

$tern_json = [
  'bbn' => [
    'vue' => $tern_json
  ]
];

$res[1]['items'] = $fns;
$fs->putContents($dir.'/bbn-vue.json', Json_encode($res, JSON_PRETTY_PRINT));
$fs->putContents($dir.'/tern.json', Json_encode($tern_json, JSON_PRETTY_PRINT));
$files = json_decode('["src\/vars.js","src\/methods.js","src\/mixins\/basic.js","src\/mixins\/empty.js","src\/mixins\/dimensions.js","src\/mixins\/position.js","src\/mixins\/dropdown.js","src\/mixins\/keynav.js","src\/mixins\/toggle.js","src\/mixins\/localStorage.js","src\/mixins\/data.js","src\/mixins\/dataEditor.js","src\/mixins\/events.js","src\/mixins\/list.js","src\/mixins\/memory.js","src\/mixins\/input.js","src\/mixins\/resizer.js","src\/mixins\/close.js","src\/mixins\/field.js","src\/mixins\/view.js","src\/mixins\/observer.js","src\/mixins\/keepCool.js","src\/mixins\/url.js","src\/mixins.js","src\/defaults.js","src\/init.js"]');
$st    = '';
foreach ($files as $f) {
  $st .= $fs->getContents($f).PHP_EOL.PHP_EOL.PHP_EOL;
}

$fs->putContents('dist/js/bbn-vue.js', $st);

$fs->putContents('dist/js/bbn-vue.min.js', JShrink\Minifier::minify($st, ['flaggedComments' => false]));
return ['data' => $res];
