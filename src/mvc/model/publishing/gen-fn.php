<?php
/*
 * Describe what it does!
 *
 **/
use bbn\X;
use bbn\Str;
use bbn\Db;
use bbn\File\System;
use bbn\Compilers\Less;
use bbn\Parsers\Doc;
use bbn\Parsers\Docblock;
use JShrink\Minifier;

/** @var bbn\Mvc\Model $model */
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
$less = new Less();
$tern_json = [];
if ($model->hasData('fns')) {
  $root = BBN_CDN_PATH.'lib/bbn-js/master/';
  $dir = $root.'src/fn';
  $fs = new System();
  $fs->cd($dir);
  $files = $fs->getFiles('.');
  $res = [];
  $p = new Docblock('js');
  foreach( $files as $i => $f ){
    if (!in_array(substr($f, 0, 1), ['.', '_'])) {
      $content = $fs->getContents($f);
      $parser = $p->parse($content);
      if (isset($parser['ignore'])) {
        continue;
      }
      /*
      $tt = Peast\Peast::latest($content)->tokenize(); //Parse it!
      foreach ($tt as $t) {
        X::hdump($t->getType(), $t->getValue());
      }
      die(x::dump($tt));
      $p = new \bbn\Parsers\Doc($content, 'js');
      $parser = $p->getJs();
      //ksort($parser['methods']);
      */
      foreach ($parser['methods'] as &$m) {
        $m['source'] = $model->data['fns'][$m['name']] ?? '';
      }
      unset($m);
      $src = '/**'.PHP_EOL;
      $md = '# '.$f.PHP_EOL.PHP_EOL;
      $head = [];
      if (empty($parser['summary'])) {
        $head[] = [
          'tag' => 'todo',
          'content' => 'Add a file summary'
        ];
      }
      else {
        if (empty($parser['description'])) {
          $head[] = [
            'tag' => 'file',
            'content' => $parser['summary']
          ];
        }
        else {
          array_push(
            $head,
            ['content' => $parser['summary']],
            ['content' => ''],
            ['content' => $parser['description']]
          );
        }
        $md .= '## '.$parser['summary'].PHP_EOL.PHP_EOL;
        if (!empty($parser['description'])) {
          $md .= $parser['description'].PHP_EOL.PHP_EOL;
        }
        if (empty($parser['author'])) {
          $head[] = [
            'tag' => 'author',
            'content' => 'BBN Solutions <info@bbn.solutions>'
          ];
        }
        else {
          foreach ($parser['author'] as $a) {
            $head[] = [
              'tag' => 'author',
              'content' => $a['text']
            ];
          }
        }
        $head[] = [
          'tag' => 'since',
          'content' => isset($h['since']) ? $h['since']['text'] : date('d/m/Y')
        ];
      }
      pad($head);
      foreach ($head as $h) {
        $src .= ' * @'.($h['tag'] ?? '').$h['content'].PHP_EOL;
      }
      $src .= ' */'.PHP_EOL.PHP_EOL;
      $src .= <<<EOD
;((bbn) => {
  "use Strict";

  /**
   * @var {Object} _private Misc variable for internal use
   */
  let _private = {};

  Object.assign(bbn.fn, {

EOD;
      $params = ['param', 'returns', ''];
      $methods = [];
      $parser['methods'] = array_filter($parser['methods'], function($a) {
        return substr($a['name'], 0, 1) !== '_';
      });
      foreach ($parser['methods'] as $meth) {
        $src .= '    /**'.PHP_EOL;
        $methods[$meth['name']] = '### <a name="'.$meth['name'].'"></a>bbn.fn.'.
          $meth['name'].'(%s)'.PHP_EOL.PHP_EOL;
        $args = [];
        $args_list;
        $lines = [];
        $has_desc = false;
        if (!empty($meth['summary'])) {
          $summary = trim($meth['summary']);
          if (substr($summary, -1) !== '.') {
            $summary .= '.';
          }
          $methods[$meth['name']] .= '  __'.$summary.'__'.PHP_EOL.PHP_EOL;
          $src .= '     * '.$summary.PHP_EOL.'     *'.PHP_EOL;
          if ($meth['description'] = trim(trim($meth['description']), PHP_EOL)) {
            if (substr($meth['description'], -1) !== '.') {
              $meth['description'] .= '.';
            }
            $methods[$meth['name']] .= '  '.$meth['description'].PHP_EOL.PHP_EOL;
            $desc = X::split($meth['description'], PHP_EOL);
            $num = 0;
            foreach ($desc as $d) {
              $d = trim($d);
              if ($d) {
                $num++;
                $src .= '     * '.$d.PHP_EOL;
              }
            }
            if ($num) {
              $src .= '     *'.PHP_EOL;
            }
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
        if (isset($meth['ignore'])) {
          $lines[] = [
            'tag' => 'ignore'
          ];
        }
        $lines[] = [
          'tag' => 'memberof',
          'content' => 'bbn.fn'
        ];
        if (!empty($meth['example'])) {
          foreach ($meth['example'] as $ex) {
            $bits = X::split($ex['text'], PHP_EOL);
            $lines[] = [
              'tag' => 'example'
            ];
            foreach ($bits as $bit) {
              $lines[] = [
                'content' => $bit
              ];
            }
          }
        }
        if (!empty($meth['fires'])) {
          foreach ($meth['fires'] as $param) {
            if (isset($param['name'])) {
              $methods[$meth['name']] .= '  Fires '.$param['name'].PHP_EOL;
              $lines[] = [
                'tag' => 'fires',
                'name' => $param['name']
              ];
            }
          }
        }
        $tern_args = [];
        $reserved = ['null', 'undefined', 'NaN', '[]', '{}', 'false', 'true'];
        if (!empty($meth['param'])) {
          foreach ($meth['param'] as $j => $param) {
            if (!isset($param['type'])) {
              $param['type'] = '{*}';
            }
            $tern_def = false;
            if (substr($param['name'], 0, 1) === '[') {
              $tern_name = str_replace('[', '', str_replace(']', '', $param['name']));
              $bits = X::split($tern_name, '=');
              if (count($bits) === 2) {
                $tern_name = trim($bits[0]);
                $tern_def = trim($bits[1]);
                if (!in_array($tern_def, $reserved) && !\bbn\Str::isNumber($tern_def)) {
                  $tern_def = "'".$tern_def."'";
                }
              }
            }
            else {
              $tern_name = $param['name'];
            }
            $args[] = $tern_name;
            $tern_type = $param['type'] ? substr($param['type'], 1, -1) : 'mixed';
            if (substr($tern_type, 0, 1) === '(') {
              $tern_type = substr($tern_type, 1, -1);
            }
            if (($tern_type === 'String') || strpos('|', $tern_type)) {
              $tern_type = strtolower($tern_type);
            }
            $tern_args[] = $tern_name.': '.$tern_type;//.($tern_def ? ' = '.$tern_def : '');
            $lines[] = [
              'tag' => 'param',
              'type' => $param['type'],
              'name' => $param['name'],
              'content' => $param['description'] ?? ''
            ];
            $methods[$meth['name']] .= '  * __'.$param['name'].'__ _'.
              ($param['type'] === '{*}' ? 'Mixed' : substr($param['type'], 1, -1)).
              '_ '.($param['description'] ?? '').PHP_EOL;
          }
        }
        $return = $meth['return'] ?? ($meth['returns'] ?? []);
        if (!isset($return['type'])) {
          $return['type'] = '{undefined}';
        }
        $tern_json[$meth['name']] = [
          "!type" => str_replace('*', 'mixed', 'fn('.x::join($tern_args, ', ').') -> '.substr($return['type'], 1, -1)),
          "!url" => "https://bbn.io/bbn-js/doc/".basename($f, '.js')."/".$meth['name'],
          "!doc" => $meth['summary']
        ];
        $lines[] = [
          'tag' => 'returns',
          'type' => $return['type'],
          'content' => $return['description'] ?? ''
        ];
        if (isset($meth['ignore'])) {
          unset($methods[$meth['name']]);
        }
        else {
          $return_type = substr($return['type'], 1, -1);
          if ($return_type === '*') {
            $return_type = 'Mixed';
          }
          $methods[$meth['name']] = sprintf($methods[$meth['name']], X::join($args, ', ')).PHP_EOL.
            '  __Returns__ _'.$return_type.'_ '.($return['description'] ?? '');
          if (!empty($meth['example'])) {
            foreach ($meth['example'] as $ex) {
              $methods[$meth['name']] .= PHP_EOL.PHP_EOL.$ex['text'];
            }
          }
          $methods[$meth['name']] .= PHP_EOL.'[Back to top](#bbn_top)  ';
        }
        pad($lines);
        foreach ($lines as $line) {
          $src .= '     * '.(empty($line['tag']) ? '' : '@').x::join($line, '').PHP_EOL;
        }
        $src .= '     */'.PHP_EOL.'    '.(empty($meth['source']) ? '' : $meth['source'].',').PHP_EOL.PHP_EOL;
      }
      $method_names = array_keys($methods);
      sort($method_names);
      $toc = '';
      foreach ($method_names as $method_name) {
        if (substr($method_name, 0, 1) === '_') {
          continue;
        }
        $toc .= '[bbn.fn.__'.$method_name.'__](#'.$method_name.')  '.PHP_EOL.
          $parser['methods'][$method_name]['summary'].'  '.PHP_EOL;
      }
      $src .= '  });'.PHP_EOL.'})(bbn);'.PHP_EOL;
      $md .= '<a name="bbn_top"></a>'.$toc.PHP_EOL.PHP_EOL.x::join($methods, PHP_EOL.PHP_EOL);
      $fs->putContents($root.'doc/src/'.$f, $src);
      $fs->putContents($root.'doc/md/'.basename($f, '.js').'.md', $md);
      $parser['new'] = $src;
      $res[$f] = $parser;
    }
  }
  $json = [];
  foreach ($res as $file => $content) {
    if (!empty($content['methods'])) {
      $lastmod = $fs->filemtime($file);
      $tmp = [
        'text' => substr($content['summary'], 0, -1),
        'desc' => $content['description'],
        'value' => basename($file, '.js'),
        'lastmod' => $lastmod,
        'items' => X::map(function($a, $name) use ($file, $lastmod) {
          return [
            'file' => $file,
            'text' => $name,
            'desc' => substr($a['summary'], 0, -1),
            'value' => $name,
            'lastmod' => $lastmod,
            'url' => 'bbn-js/doc/'.basename($file, '.js').'/'.$name
          ];
        }, $content['methods'])
      ];
      X::sortBy($tmp['items'], 'text');
      $json[] = $tmp;
    }
  }
  $fs->putContents($root.'doc/bbn.json', Json_encode($json, JSON_PRETTY_PRINT));
  $tern_json = [
    '!name' => 'bbn',
    'bbn' => [
      'fn' => $tern_json
    ]
  ];
  $fs->putContents($root.'doc/tern.json', Json_encode($tern_json, JSON_PRETTY_PRINT));
  $files = json_decode('["src\/bbn.js","src\/functions.js","src\/env\/_def.js","src\/var\/_def.js","src\/var\/diacritic.js","src\/fn\/_def.js","src\/fn\/ajax.js","src\/fn\/form.js","src\/fn\/history.js","src\/fn\/init.js","src\/fn\/locale.js","src\/fn\/misc.js","src\/fn\/object.js","src\/fn\/size.js","src\/fn\/string.js","src\/fn\/style.js","src\/fn\/type.js"]');
  $st = '';
  foreach ($files as $f) {
    $st .= $fs->getContents($root.$f).PHP_EOL.PHP_EOL.PHP_EOL;
  }
  $fs->putContents($root.'dist/bbn.js', $st);
  $fs->putContents($root.'dist/bbn.min.js', Minifier::minify($st, ['flaggedComments' => false]));
  $root_css = BBN_CDN_PATH.'lib/bbn-css/master/';
  $files = $fs->getFiles($root_css.'src/css', 'less');
  $st = '';
  foreach ($files as $f) {
    if (Str::isInteger(substr(basename($f), 0, 2))) {
      $st .= $fs->getContents($f).PHP_EOL.PHP_EOL.PHP_EOL;
    }
  }
  $default = $fs->getContents($root_css.'src/css/themes/_def.less');
  $default .= $fs->getContents($root_css.'src/css/themes/_colors.less');
  $compiled = $less->compile($default.$st);
  $fs->putContents($root_css.'dist/bbn.css', $compiled);
  $fs->putContents($root_css.'dist/bbn.min.css', CssMin::minify($compiled));
  $themes = $fs->getFiles($root_css.'src/css/themes', '.less');
  foreach ($themes as $t) {
    $name = basename($t, '.less');
    if (substr($name, 0, 1) === '_') {
      continue;
    }

    $error = false;
    try {
      $compiled = $less->compile($default.$fs->getContents($t).$st);
    }
    catch (\Exception $e) {
      X::log($name);
      X::log($e->getMessage());
      $error = true;
    }
    if (!$error && $compiled) {
      $fs->putContents($root_css.'dist/bbn.'.$name.'.css', $compiled);
      $fs->putContents($root_css.'dist/bbn.'.$name.'.min.css', CssMin::minify($compiled));
    }
  }
  //$files = $fs->get_files
  return ['data' => $json];
}