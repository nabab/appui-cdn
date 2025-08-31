<?php
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
$dirs = [];
if ($model->hasData('fns')) {
  $root = constant('BBN_CDN_PATH') . 'lib/bbn-js/v2/';
  $directory = $root.'src/fn';
  $fs = new System();
  $fs->cd($directory);
  $files = $fs->scan('.', 'ts');
  $res = [];
  $p = new Docblock('js');
  foreach( $files as $i => $f ){
    $name = basename($f, '.ts');
    $dir = X::split($f, '/')[0];
    if (!in_array($dir, $dirs)) {
      $dirs[] = $dir;
    }

    if (!in_array(substr($name, 0, 1), ['.', '_'])) {
      $content = $fs->getContents($f);
      $meth = $p->parse($content);
      if (!empty($meth['ignore']) || !array_key_exists($meth['name'], $model->data['fns'])) {
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
      $meth['dir'] = $dir;
      $meth['source'] = $model->data['fns'][$name] ?? '';
      $src = '/**'.PHP_EOL;
      $md = '# '.$f.PHP_EOL.PHP_EOL;
      $head = [];
      if (empty($meth['description'])) {
        $head[] = [
          'tag' => 'file',
          'content' => $meth['summary']
        ];
      }
      else {
        array_push(
          $head,
          ['content' => $meth['summary']],
          ['content' => ''],
          ['content' => $meth['description']]
        );
      }
      $md .= '## '.$meth['summary'].PHP_EOL.PHP_EOL;
      if (!empty($meth['description'])) {
        $md .= $meth['description'].PHP_EOL.PHP_EOL;
      }
      if (empty($meth['author'])) {
        $head[] = [
          'tag' => 'author',
          'content' => 'BBN Solutions <info@bbn.solutions>'
        ];
      }
      else {
        foreach ($meth['author'] as $a) {
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
      $src .= '    /**'.PHP_EOL;
      $md = '### <a name="'.$meth['name'].'"></a>bbn.fn.'.
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
        $md .= '  __'.$summary.'__'.PHP_EOL.PHP_EOL;
        $src .= '     * '.$summary.PHP_EOL.'     *'.PHP_EOL;
        if ($meth['description'] = trim(trim($meth['description']), PHP_EOL)) {
          if (substr($meth['description'], -1) !== '.') {
            $meth['description'] .= '.';
          }
          $md .= '  '.$meth['description'].PHP_EOL.PHP_EOL;
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
            $md .= '  Fires '.$param['name'].PHP_EOL;
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
          $md .= '  * __'.$param['name'].'__ _'.
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
        "!url" => "https://bbn.io/bbn-js/doc/".$meth['name'],
        "!doc" => $meth['summary']
      ];
      $lines[] = [
        'tag' => 'returns',
        'type' => $return['type'],
        'content' => $return['description'] ?? ''
      ];

      $return_type = substr($return['type'], 1, -1);
      if ($return_type === '*') {
        $return_type = 'Mixed';
      }
      $md = sprintf($md, X::join($args, ', ')).PHP_EOL.
        '  __Returns__ _'.$return_type.'_ '.($return['description'] ?? '');
      if (!empty($meth['example'])) {
        $md .= PHP_EOL.PHP_EOL.'### Examples'.PHP_EOL;
        foreach ($meth['example'] as $ex) {
          $md .= PHP_EOL.PHP_EOL.$ex['text'];
        }
      }
      $md .= PHP_EOL.'[Back to top](#bbn_top)  ';
      pad($lines);
      foreach ($lines as $line) {
        $src .= '     * '.(empty($line['tag']) ? '' : '@').x::join($line, '').PHP_EOL;
      }
      $source = trim(empty($meth['source']) ? '' : $meth['source']);
      if (strpos($source, 'function') === 0) {
        $source = trim(substr($source, 8));
      }

      $src .= '     */'.PHP_EOL.'    '.$source.PHP_EOL.PHP_EOL;
      $src .= '  });'.PHP_EOL.'})(bbn);'.PHP_EOL;
      $md .= '<a name="bbn_top"></a>'.$toc.PHP_EOL.PHP_EOL.x::join($methods, PHP_EOL.PHP_EOL);
      //X::ddump($f, $md, $src);
      $fs->putContents($root.'doc/src/'.$name.'.js', $src);
      $fs->putContents($root.'doc/md/'.$name.'.md', $md);
      $meth['new'] = $src;
      $res[$f] = $meth;
    }
  }

  $json = [];
  foreach ($dirs as $dir) {
    $tmp = [
      'text' => $dir,
      'items' => []
    ];

    foreach ($res as $file => $content) {
      if (X::split($file, '/')[0] !== $dir) {
        continue;
      }

      $lastmod = $fs->filemtime($file);
      $tmp['items'][] = [
        'file' => $file,
        'text' => $content['name'],
        'desc' => substr($content['summary'], 0, -1),
        'value' => $content['name'],
        'lastmod' => $lastmod,
        'url' => 'bbn-js/doc/'.$content['name']
      ];
    }

    $json[] = $tmp;
  }
  $fs->putContents($root.'doc/bbn.json', json_encode($json, JSON_PRETTY_PRINT));
  $tern_json = [
    '!name' => 'bbn',
    'bbn' => [
      'fn' => $tern_json
    ]
  ];
  $fs->putContents($root.'doc/tern.json', json_encode($tern_json, JSON_PRETTY_PRINT));
  /*
  $files = json_decode('["src\/bbn.js","src\/functions.js","src\/env\/_def.js","src\/var\/_def.js","src\/var\/diacritic.js","src\/fn\/_def.js","src\/fn\/ajax.js","src\/fn\/form.js","src\/fn\/history.js","src\/fn\/init.js","src\/fn\/locale.js","src\/fn\/misc.js","src\/fn\/object.js","src\/fn\/size.js","src\/fn\/string.js","src\/fn\/style.js","src\/fn\/type.js"]');
  $st = '';
  foreach ($files as $f) {
    $st .= $fs->getContents($root.$f).PHP_EOL.PHP_EOL.PHP_EOL;
  }
  $fs->putContents($root.'dist/bbn.js', $st);
  $fs->putContents($root.'dist/bbn.min.js', Minifier::minify($st, ['flaggedComments' => false]));
  */
  /*
  $root_css = BBN_CDN_PATH.'lib/bbn-css/v2/';
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
  */
  //$files = $fs->get_files
  return ['data' => $res];
}