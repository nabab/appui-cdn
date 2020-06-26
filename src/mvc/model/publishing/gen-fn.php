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
$less = new \lessc();
if ($model->has_data('fns')) {
  $dir = BBN_CDN_PATH.'lib/bbnjs/1.0.1/src/fn';
  $fs = new \bbn\file\system();
  $fs->cd($dir);
  $files = $fs->get_files('.');
  $res = [];
  $p = new \bbn\parsers\docblock('js');
  foreach( $files as $i => $f ){
    if (!in_array(substr($f, 0, 1), ['.', '_'])) {
      $content = $fs->get_contents($f);
      $parser = $p->parse($content);
      if (isset($parser['ignore'])) {
        continue;
      }
      /*
      $tt = Peast\Peast::latest($content)->tokenize(); //Parse it!
      foreach ($tt as $t) {
        \bbn\x::hdump($t->getType(), $t->getValue());
      }
      die(\bbn\x::dump($tt));
      $p = new \bbn\parsers\doc($content, 'js');
      $parser = $p->get_js();
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
  "use strict";

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
            $desc = \bbn\x::split($meth['description'], PHP_EOL);
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
            $bits = \bbn\x::split($ex['text'], PHP_EOL);
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
        if (!empty($meth['param'])) {
          foreach ($meth['param'] as $param) {
            if (!isset($param['type'])) {
              $param['type'] = '{*}';
            }
            $args[] = $param['name'];
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
          $methods[$meth['name']] = sprintf($methods[$meth['name']], \bbn\x::join($args, ', ')).PHP_EOL.
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
          $src .= '     * '.(empty($line['tag']) ? '' : '@').\bbn\x::join($line, '').PHP_EOL;
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
      $md .= '<a name="bbn_top"></a>'.$toc.PHP_EOL.PHP_EOL.\bbn\x::join($methods, PHP_EOL.PHP_EOL);
      $fs->put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/doc/src/'.$f, $src);
      $fs->put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/doc/md/'.basename($f, '.js').'.md', $md);
      $parser['new'] = $src;
      $res[$f] = $parser;
    }
  }
  $json = [];
  foreach ($res as $file => $content) {
    if (!empty($content['methods'])) {
      $tmp = [
        'text' => substr($content['summary'], 0, -1),
        'desc' => $content['description'],
        'value' => basename($file, '.js'),
        'items' => \bbn\x::map(function($a, $name) use ($file) {
          return [
            'file' => $file,
            'text' => $name,
            'desc' => substr($a['summary'], 0, -1),
            'value' => $name,
            'url' => 'bbn-js/doc/'.basename($file, '.js').'/'.$name
          ];
        }, $content['methods'])
      ];
      \bbn\x::sort_by($tmp['items'], 'text');
      $json[] = $tmp;
    }
  }
  $fs->put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/doc/bbn.json', json_encode($json, JSON_PRETTY_PRINT));
  $files = json_decode('["src\/bbn.js","src\/functions.js","src\/env\/_def.js","src\/var\/_def.js","src\/var\/diacritic.js","src\/fn\/_def.js","src\/fn\/ajax.js","src\/fn\/form.js","src\/fn\/history.js","src\/fn\/init.js","src\/fn\/locale.js","src\/fn\/misc.js","src\/fn\/object.js","src\/fn\/size.js","src\/fn\/string.js","src\/fn\/style.js","src\/fn\/type.js"]');
  $st = '';
  foreach ($files as $f) {
    $st .= $fs->get_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/'.$f).PHP_EOL.PHP_EOL.PHP_EOL;
  }
  $fs->put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/dist/bbn.js', $st);
  $fs->put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/dist/bbn.min.js', JShrink\Minifier::minify($st, ['flaggedComments' => false]));
  $files = $fs->get_files(BBN_CDN_PATH.'lib/bbnjs/1.0.1/src/css', 'less');
  $st = '';
  foreach ($files as $f) {
    if (\bbn\str::is_integer(substr(basename($f), 0, 2))) {
      $st .= $fs->get_contents($f).PHP_EOL.PHP_EOL.PHP_EOL;
    }
  }
  $default = $fs->get_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/src/css/themes/_def.less');
  $compiled = $less->compile($default.$st);
  $fs->put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/dist/css/bbn.css', $compiled);
  $fs->put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/dist/css/bbn.min.css', CssMin::minify($compiled));
  $themes = $fs->get_files(BBN_CDN_PATH.'lib/bbnjs/1.0.1/src/css/themes', '.less');
  foreach ($themes as $t) {
    $name = basename($t, '.less');
    $error = false;
    try {
      $compiled = $less->compile($default.$fs->get_contents($t).$st);
    }
    catch (\Exception $e) {
      \bbn\x::log($name);
      \bbn\x::log($e->getMessage());
      $error = true;
    }
    if (!$error && $compiled) {
      $fs->put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/dist/css/bbn.'.$name.'.css', $compiled);
      $fs->put_contents(BBN_CDN_PATH.'lib/bbnjs/1.0.1/dist/css/bbn.'.$name.'.min.css', CssMin::minify($compiled));
    }
  }
  //$files = $fs->get_files
  return $json;
}