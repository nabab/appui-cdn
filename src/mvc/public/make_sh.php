<?php

use bbn\X;


/** @var $ctrl \bbn\Mvc\Controller */
$db = $ctrl->data['db'];

$q = $db->query(<<<SQL
SELECT libraries.name, libraries.git, libraries.latest, versions.commands
FROM libraries
	JOIN versions
  	ON versions.library = libraries.name
WHERE git <> ''
ORDER BY libraries.name
SQL);
$res = [];
$str_path = 'CDN_PATH=$(pwd);' . PHP_EOL . PHP_EOL . PHP_EOL;
$str = '';
$commands = $str_path;
while ($lib = $q->getRow()) {
  if (!str_ends_with($lib['git'], '.git')) {
    $lib['git'] = $lib['git'] . '.git';
  }
  array_push($res,$lib);
  $str .= "mkdir -p public_html/lib/$lib[name];" . PHP_EOL;
  $str .= "git submodule add $lib[git] public_html/lib/$lib[name]/$lib[latest];" . PHP_EOL;
  $str .= "cd public_html/lib/$lib[name]/$lib[latest];" . PHP_EOL;
  if ($lib['latest'] !== 'master') {
    $str .= "git checkout tags/$lib[latest];" . PHP_EOL;
  }

  if (!empty($lib['commands'])) {
    $commands .= "cd public_html/lib/$lib[name]/$lib[latest];" . PHP_EOL;
    $commands .= $lib['commands'] . PHP_EOL;
    $commands .= 'cd $CDN_PATH;' . PHP_EOL . PHP_EOL;
  }

  $str .= 'cd $CDN_PATH;' . PHP_EOL . PHP_EOL;
}

echo '<div class="bbn-padded bbn-w-100"><code class="bbn-pre">' . $commands . $str . '</code></div>' . PHP_EOL;
echo '<div class="bbn-hr bbn-w-100"> </div>' . PHP_EOL;
echo '<div class="bbn-padded bbn-w-100"><code class="bbn-pre">' . $str_path . $str . '</code></div>' . PHP_EOL;
