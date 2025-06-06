<?php
/*
 * Describe what it does!
 *
 **/
use bbn\X;
use bbn\Str;
use bbn\Db;
use bbn\File\System;
use bbn\Cdn\Config;
use bbn\Compilers\Less;
use bbn\Parsers\Doc;
use bbn\Parsers\Docblock;
use JShrink\Minifier;

/** @var bbn\Mvc\Model $model */
$asSingleFiles = $model->hasData('single', true);
$dir = BBN_CDN_PATH.'lib/bbn-vue/master';
$fs  = new System();
$fs->cd($dir);
$p           = 'src/components/';
$components  = $fs->getDirs($p);
$num         = 0;
$vue         = [];
$mixins      = [];
$expressions = [];
$less        = new Less();
// in dist: deletes folders js, js_single_files and vue
$distPath = 'dist/js' . ($asSingleFiles ? '_single_files' : '');
$fs->delete($distPath);
$fs->delete('dist/vue');
// Create folder js/components, js_single_files/components
$fs->createPath($distPath . '/components');
if (!defined('BBN_CDN_DB')) {
  throw new Exception("The CDN DB path is not defined");
}

// Connects to a Sqlite database
$sqlite = new Db([
  'engine' => 'sqlite',
  'db' => BBN_CDN_DB
]);

// For each directory in src/components gets the content of js, html and less file
foreach ($components as $component) {
  $cp    = basename($component);
  $flist = $fs->getFiles($p.$cp, false, false, null, 'mdt');
  $last  = X::sortBy($flist, 'mtime', 'desc')[0]['mtime'];
  $html  = X::getRow($flist, ['name' => $p.$cp.'/'.$cp.'.html']) ? $fs->getContents($p.$cp.'/'.$cp.'.html') : '';
  $css   = X::getRow($flist, ['name' => $p.$cp.'/'.$cp.'.less']) ? $fs->getContents($p.$cp.'/'.$cp.'.less') : '';
  $js    = X::getRow($flist, ['name' => $p.$cp.'/'.$cp.'.js']) ? $fs->getContents($p.$cp.'/'.$cp.'.js') : '';
  $cfg   = X::getRow($flist, ['name' => $p.$cp.'/bbn.json']) ? json_decode($fs->getContents($p.$cp.'/bbn.json'), true) : '';
  $langs = X::filter($flist, [[
    'field' => 'name',
    'operator' => 'endswith',
    'value' => '.lang'
  ]]);

  if ($js) {
    $cp_files = array_filter(
      $fs->getFiles($p.$cp, true, false), function ($a) use ($cp, $p) {
        $ext = Str::fileExt($a);
        return !in_array($ext, ['md', 'lang', 'pdf', 'bak', 'json']) &&
        !in_array($a, [$p.$cp.'/'.$cp.'.html', $p.$cp.'/'.$cp.'.less', $p.$cp.'/'.$cp.'.js']);
      }
    );
    $ar_cfg   = false;

    if (!empty($cfg['dependencies'])) {

      $cdn_cfg = new Config(
        BBN_SHARED_PATH.'?lib='
            .x::join(array_keys($cfg['dependencies']), ','),
        $sqlite
      );
      $ar_cfg  = $cdn_cfg->get();
    }

    // Start of documentation process
    $parser = new Doc($js, 'vue');
    $doc    = $parser->getVue();
    // bbn.io
    $tmp       = [
      'text' => $cp,
      'lastmod' => $last,
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
              throw new Exception(
                _("Impossible to find the content of the mixins")
                .' '.$dir.'/src/mixins/'.$mixin.'.js'
              );
            }

            $parser2 = new Doc($c2, 'vue');
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
      $idx = X::search($tmp['items'], ['value' => $rem]);
      if ($idx === null) {
        throw Error("Impossible to find item $rem");
      }

      array_splice($tmp['items'], $idx, 1);
    }

    $vue[] = $tmp;
    // End of documentation process

    // .vue files
    $fs->createPath($distPath.'/components/'.$cp);
    $fs->createPath('dist/vue/components/'.$cp);
    $st_vue           = '';
    $css_dependencies = [];
    $st_js            = (!$asSingleFiles ? '(bbn_resolve) => {'.PHP_EOL : '') . '((bbn) => {'.PHP_EOL;
    $dep_st           = false;

    // Start of dependencies management
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
        if (!empty($nc['css'])) {
          foreach ($nc['css'] as $nccss) {
            if (($nc['mode'] === 'npm') && !empty($nc['npm'])) {
              $files_css[] = 'npm/'.$nc['npm'].'@'.$nc['version'].'/'.$nccss;
            }
            elseif (!empty($nc['git'])) {
              $files_css[] = 'gh/'.$nc['git'].'@'.$nc['version'].'/'.$nccss;
            }
          }
        }

        if (count($files_css)) {
          $css_dependencies[] = 'https://cdn.jsdelivr.net/combine/'.x::join($files_css, ',');
        }
      }

      if (count($files_js)) {
        $dep_st = 'https://cdn.jsdelivr.net/combine/'.x::join($files_js, ',');
        $st_js .= PHP_EOL . "let script_dep = document.createElement('script');" .
                  PHP_EOL . "script_dep.setAttribute('src', '$dep_st');" .
                  PHP_EOL . "script_dep.onload = () => {" . 
                  PHP_EOL . PHP_EOL;
      }

      if (count($css_dependencies)) {
        $st_js .= PHP_EOL.'let css_dependency;'.PHP_EOL;
        foreach ($css_dependencies as $css_d) {
          $st_js .= PHP_EOL . "css_dependency = document.createElement('link');" .
                    PHP_EOL . "css_dependency.setAttribute('rel', 'stylesheet');" .
                    PHP_EOL . "css_dependency.setAttribute('href', '$css_d');" .
                    PHP_EOL . "document.head.insertAdjacentElement('beforeend', css_dependency);" .
                    PHP_EOL . PHP_EOL;
        }
      }
    }

    if ($html) {
      $st_vue .= '<template>'.PHP_EOL.$html.PHP_EOL.'</template>'.PHP_EOL;
      $content = str_replace('`', '\\`', str_replace('\\', '\\\\', $html));
      $st_js  .= PHP_EOL . "let script = document.createElement('script');" .
                 PHP_EOL . "script.innerHTML = `$content`;" .
                 PHP_EOL . "script.setAttribute('id', 'bbn-tpl-component-$cp');" .
                 PHP_EOL . "script.setAttribute('type', 'text/x-template');" .
                 "document.body.insertAdjacentElement('beforeend', script);" .
                 PHP_EOL . PHP_EOL;
    }

    $st_vue .= '<script>'.PHP_EOL.'  module.exports = '.$js.PHP_EOL.'</script>'.PHP_EOL;
    if ($css && ($css = $less->compile($css))) {
      $st_vue .= '<style scoped>'.PHP_EOL.$css.PHP_EOL.'</style>'.PHP_EOL;
      $content = str_replace('`', '\\`', $css);
      $fs->putContents($distPath.'/components/'.$cp.'/'.$cp.'.css', $content);
      if (!$asSingleFiles) {
        $st_js .= PHP_EOL . "let css = document.createElement('link');" .
                  PHP_EOL . "css.setAttribute('rel', 'stylesheet');" .
                  PHP_EOL . "css.setAttribute('href', bbn.cp.libURL + '$distPath/components/$cp/$cp.css');" .
                  PHP_EOL . "document.head.insertAdjacentElement('beforeend', css);" .
                  PHP_EOL . PHP_EOL;
      }
    }

    $st_js .= $js.PHP_EOL.(!$asSingleFiles ? 'if (bbn_resolve) {bbn_resolve("ok");}' : '').PHP_EOL;
    if ($dep_st) {
      $st_js .= '};'.PHP_EOL.'document.head.insertAdjacentElement("beforeend", script_dep);'.PHP_EOL;
    }

    $st_js .= '})(bbn);' . ($asSingleFiles ? '' : PHP_EOL.'}');
    foreach ($cp_files as $cp_file) {
      $fs->copy($cp_file, $distPath.'/components/'.$cp.'/'.basename($cp_file));
      $fs->copy($cp_file, 'dist/vue/components/'.$cp.'/'.basename($cp_file));
    }

    $fs->putContents($distPath.'/components/'.$cp.'/'.$cp.'.js', $st_js);
    $fs->putContents($distPath.'/components/'.$cp.'/'.$cp.'.min.js', JShrink\Minifier::minify($st_js, ['flaggedComments' => false]));
    $fs->putContents('dist/vue/components/'.$cp.'/'.$cp.'.vue', $st_vue);
    if ($fs->isFile('dist/vue/components/'.$cp.'/'.$cp.'.vue')) {
      $num++;
    }

    // Langs
    if (!empty($langs)) {
      foreach ($langs as $lang) {
        preg_match('/[[:alnum:]]*\.{1}([a-z]{2})\.{1}lang$/', $lang['name'], $lFile);
        if (!empty($lFile)
          && !empty($lFile[1])
          && ($langFileContent = $fs->getContents($lFile[1]))
        ){
          $langContent = PHP_EOL . "(() => {" .
                         PHP_EOL . "  bbn.fn.autoExtend('lng', $langFileContent);" .
                         PHP_EOL . "})();";
          $fs->putContents($distPath.'/components/'.$cp.'/'.$cp.'.'.$lFile[1].'.js', $langContent);
          $fs->putContents($distPath.'/components/'.$cp.'/'.$cp.'.'.$lFile[1].'.min.js', JShrink\Minifier::minify($langContent, ['flaggedComments' => false]));
        }
      }
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
$p                 = new Docblock('js');
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
$files = json_decode('["src\/vars.js","src\/methods.js","src\/mixins\/basic.js","src\/mixins\/empty.js","src\/mixins\/dimensions.js","src\/mixins\/position.js","src\/mixins\/dropdown.js","src\/mixins\/keynav.js","src\/mixins\/toggle.js","src\/mixins\/localStorage.js","src\/mixins\/data.js","src\/mixins\/dataEditor.js","src\/mixins\/events.js","src\/mixins\/editableList.js","src\/mixins\/list.js","src\/mixins\/memory.js","src\/mixins\/input.js","src\/mixins\/resizer.js","src\/mixins\/close.js","src\/mixins\/field.js","src\/mixins\/view.js","src\/mixins\/observer.js","src\/mixins\/keepCool.js","src\/mixins\/url.js","src\/mixins\/serviceWorker.js","src\/mixins\/browserNotification.js","src\/mixins\/componentInside.js","src\/mixins.js","src\/defaults.js","src\/init.js"]');
$st    = '';
foreach ($files as $f) {
  $st .= $fs->getContents($f).PHP_EOL.PHP_EOL.PHP_EOL;
}

$fs->putContents($distPath.'/bbn-vue.js', $st);

$fs->putContents($distPath.'/bbn-vue.min.js', Minifier::minify($st, ['flaggedComments' => false]));

// i18n
if ($i18nFiles = $fs->getFiles('src/i18n')) {
  foreach ($i18nFiles as $i18nFile) {
    $st = $fs->getContents($i18nFile);
    if (!$fs->isDir($distPath.'/i18n')) {
      $fs->createPath($distPath.'/i18n');
    }
    $fs->putContents($distPath.'/i18n/bbn-vue.'.basename($i18nFile), $st);
    $fs->putContents($distPath.'/i18n/bbn-vue.'.basename($i18nFile, '.js').'.min.js', Minifier::minify($st, ['flaggedComments' => false]));
  }
}
return ['data' => $res];
