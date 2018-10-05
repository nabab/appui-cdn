<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 30/12/2016
 * Time: 17:55
 */
/** @var $model \bbn\mvc\model */

if ( !empty($model->data['db']) &&
  ($libraries = $model->data['db']->get_rows("
    SELECT title, name, git, latest
    FROM libraries
    WHERE git IS NOT NULL
  "))
){

  $all = [
    'updates' => [],
    'total' => 0
  ];

  foreach ($libraries as $lib ){
    $url = $lib['git'];
    if ( !empty($lib['git']) &&
      \bbn\str::is_url($lib['git']) &&
      ((strpos($lib['git'], 'http://github.com/') === 0) || (strpos($lib['git'], 'https://github.com/') === 0)) &&
      !empty($lib['latest'])
    ){
      $lib['git'] = str_replace('http://github.com/', '', str_replace('https://github.com/', '', $lib['git']));
      $lib['git'] = explode('/', $lib['git']);
      $versions = $model->get_model('./versions', [
        'db' => $model->data['db'],
        'git_user' =>  $lib['git'][0],
        'git_repo' => $lib['git'][1]
      ]);
      if ( !empty($versions['latest']) && ($lib['latest'] !== $versions['latest'])){
        array_push($all['updates'], [
          'title' => $lib['title'],
          'local' => $lib['latest'],
          'folder' => $lib['name'],
          'latest' => $versions['latest'],
          'git_repo' => $versions['git_repo'],
          'git_user' => $versions['git_user'],
          'github' => $url
        ]);
        $all['total']++;
      }
    }
  }
  return $all;
}
