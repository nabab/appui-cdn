<?php
use Exception;
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
$dir = constant('BBN_CDN_PATH') . 'lib/bbn-cp/v2';
$fs  = new System();
$fs->cd($dir);
$p           = 'src/components/';
$components  = $fs->getDirs($p);
$num         = 0;
$cps         = [];

$mixins      = [];
$expressions = [];
$less        = new Less();
$mixinPrefix = 'bbn.cp.mixins.';
$isLocalMixin = false;
// For each directory in src/components gets the content of js, html and less file
foreach ($components as $component) {
  $cp    = basename($component);
  $flist = $fs->getFiles($p.$cp, false, false, null, 'mdt');
  $last  = X::sortBy($flist, 'mtime', 'desc')[0]['mtime'];
  $html  = X::getRow($flist, ['name' => $p.$cp.'/'.$cp.'.html']) ? $fs->getContents($p.$cp.'/'.$cp.'.html') : '';
  $css   = X::getRow($flist, ['name' => $p.$cp.'/'.$cp.'.less']) ? $fs->getContents($p.$cp.'/'.$cp.'.less') : '';
  $js    = X::getRow($flist, ['name' => $p.$cp.'/'.$cp.'.js']) ? $fs->getContents($p.$cp.'/'.$cp.'.js') : '';
  $cfg   = X::getRow($flist, ['name' => $p.$cp.'/bbn.json']) ? json_decode($fs->getContents($p.$cp.'/bbn.json'), true) : '';
  $hasMixins = $fs->isDir($p.$cp.'/_mixins');
  $langs = $fs->getFiles($p.$cp.'/_i18n', false, false, 'lang');

  if ($js) {
    $cp_files = array_filter(
      $fs->getFiles($p.$cp, true, false), function ($a) use ($cp, $p) {
        $ext = Str::fileExt($a);
        return !in_array($ext, ['md', 'lang', 'pdf', 'bak', 'json']) &&
        !in_array($a, [$p.$cp.'/'.$cp.'.html', $p.$cp.'/'.$cp.'.less', $p.$cp.'/'.$cp.'.js']);
      }
    );
    $ar_cfg   = false;

    // Start of documentation process
    $parser = new Doc($js, 'cp');
    $doc    = $parser->getCp();
    // bbn.io
    $tmp       = [
      'text' => $cp,
      'lastmod' => $last,
      'url' => 'bbn-cp/component/'. $cp,
      'props' => [],
      'methods' => [],
      'mixins' => []
    ];

    foreach (['mixins', 'props', 'methods'] as $i) {
      foreach ($doc[$i] as $d) {
        if (empty($d['name']) || (substr($d['name'], 0, 1) === '_')) {
          continue;
        }

        $bits = X::split($d['name'], '.' );
        $name = end($bits);
        if ($i === 'mixins') {
          $mixin = $name;
          if (strpos($d['name'], $mixinPrefix) === 0) {
            $c2 = $fs->getContents($dir.'/src/mixins/'.$mixin.'.js');
          }
          elseif ($hasMixins && $fs->exists($p.$cp.'/_mixins/'.$mixin.'.js')) {
            $c2 = $fs->getContents($p.$cp.'/_mixins/'.$mixin.'.js');
            $isLocalMixin = true;
          }
          else {
            X::ddump($mixin, $cp, $hasMixins, $p.$cp.'/_mixins/'.$mixin.'.js');
          }

          if (!$c2) {
            throw new Exception(
              _("Impossible to find the content of the mixins")
              .' '.$dir.'/src/mixins/'.$mixin.'.js'
            );
          }

          $parser2 = new Doc($c2, 'cp');
          if ($doc2 = $parser2->getCp()) {
            $mixinCp = [
              'name' => $mixin,
              'props' => [],
              'methods' => []
            ];
            foreach ($mixinCp as $k => &$item2) {
              foreach ($doc2[$k] as $d2) {
                if (substr($d2['name'], 0, 1) !== '_') {
                  $item2[] = array_merge($d2, [
                    'text' => $d2['name'],
                    'desc' => $d2['description'] ?? '',
                    'url' => 'bbn-cp/mixins/'. $name .'/'.$k.'/'.$d2['name'],
                  ]);
                }
              }
            }

            unset($item2);
            if ($isLocalMixin) {
              array_push($tmp['props'], ...$mixinCp['props']);
              array_push($tmp['methods'], ...$mixinCp['methods']);
              $stmp = false;
            }
            else {
              $mixins[$mixin] = $mixinCp;
              $stmp = $mixins[$mixin];
            }
          }

        }
        else {
          $stmp = array_merge($d, [
            'type' => $i,
            'text' => $name,
            'desc' => $d['description'] ?? '',
            'url' => 'bbn-vue/component/'. $cp .'/doc/'.$i.'#'.$name,
          ]);
        }

        if (!empty($stmp)) {
          $tmp[$i][] = $stmp;
        }

      }
    }

    $cps[] = $tmp;
  }
}
    // End of documentation process

$mixinsNames = array_keys($mixins);
sort($mixinsNames);
$mixins = array_map(fn($n) => array_merge($mixins[$n], ['text' => $n]), $mixinsNames);

$fns               = [];
$p                 = new Docblock('js');
$files             = $fs->getFiles('src/functions', false, false, 'js');
$tmp           = [];
foreach ($files as $file) {
  $fn = basename($file, '.js');
  $content           = $fs->getContents($file);
  $parser            = $p->parse($content);
  $params            = ['param', 'returns', ''];
  $tmp[$fn] = $parser;
}

$method_names = array_keys($tmp);
sort($method_names);
$toc = '';
foreach ($method_names as $method_name) {
  if (substr($method_name, 0, 1) !== '_') {
    $fns[] = [
      'text' => $method_name,
      'url' => 'bbn-cp/function/'.$method_name,
      'desc' => $tmp[$method_name]['summary']
    ];
    $tern_json[$method_name] = [
      "!type" => 'fn() -> mixed',
      "!url" => "https://bbn.io/bbn-cp/function/".$method_name,
      "!doc" => $tmp[$method_name]['summary']
    ];
  }
}

$files             = $fs->getFiles('src/lib/Html/prototype', false, false, 'js');
$methods           = [];
foreach ($files as $file) {
  $method = basename($file, '.js');
  $content           = $fs->getContents($file);
  $parser            = $p->parse($content);
  $params            = ['param', 'returns', ''];
  $parser['name'] = $method;
  $parser['type'] = 'method';
  $methods[] = array_merge($parser, [
    'text' => $method,
    'url' => 'bbn-cp/method/'.$method_name,
    'desc' => $methods[$method_name]['summary']
  ]);
}

$res               = [
  [
    'text' => _('Components'),
    'value' => 'cp/component',
    'type' => 'category',
    'items' => $cps
  ], [
    'text' => _('Functions'),
    'value' => 'cp/functions',
    'type' => 'category',
    'items' => $fns
  ], [
    'text' => _('Common methods'),
    'value' => 'cp/methods',
    'type' => 'category',
    'items' => $methods
  ], [
    'text' => _('Mixins'),
    'value' => 'cp/mixins',
    'type' => 'category',
    'items' => $mixins
  ]
];

$tern_json = [
  'bbn' => [
    'cp' => $tern_json
  ]
];

$res[2]['items'] = $methods;
$fs->putContents($dir.'/bbn-cp.json', Json_encode($res, JSON_PRETTY_PRINT));
$fs->putContents($dir.'/tern.json', Json_encode($tern_json, JSON_PRETTY_PRINT));

// i18n
/*
if ($i18nFiles = $fs->getFiles('src/i18n')) {
  foreach ($i18nFiles as $i18nFile) {
    $st = $fs->getContents($i18nFile);
    if (!$fs->isDir($distPath.'/i18n')) {
      $fs->createPath($distPath.'/i18n');
    }
    $fs->putContents($distPath.'/i18n/bbn-cp.'.basename($i18nFile), $st);
    $fs->putContents($distPath.'/i18n/bbn-cp.'.basename($i18nFile, '.js').'.min.js', Minifier::minify($st, ['flaggedComments' => false]));
  }
}
  */
return ['data' => $res];
