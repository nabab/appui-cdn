<?php

//die(var_dump("ss", $model->data['library']));
if ( !empty($model->data['db']) &&
  ($library = $model->data['db']->get_rows("
    SELECT title, name, git, latest
    FROM libraries
    WHERE git IS NOT NULL AND NAME = ?
  ", $model->data['library']))
){
  $library = $library[0];
  $url = $library['git'];
  if ( !empty($library['git']) &&
    \bbn\str::is_url($library['git']) &&
    ((strpos($library['git'], 'http://github.com/') === 0) || (strpos($library['git'], 'https://github.com/') === 0)) &&
    !empty($library['latest'])
  ){

    $library['git'] = str_replace('http://github.com/', '', str_replace('https://github.com/', '', $library['git']));
    $library['git'] = explode('/', $library['git']);
    $version = $model->get_model('./versions', [
      'db' => $model->data['db'],
      'git_user' =>  $library['git'][0],
      'git_repo' => $library['git'][1]
    ]);

    if ( !empty($version['latest']) && ($library['latest'] !== $version['latest'])){
      return [
        'latest' => $version['latest'],
        'update' => true
      ];
    }
  }
}
return ['update' => false];
