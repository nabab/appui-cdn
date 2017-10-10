<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 21/12/2016
 * Time: 18:24
 */

if ( !empty($model->data['git_user']) && !empty($model->data['git_repo']) && defined('BBN_GITHUB_TOKEN') ){
  $git = new \Github\Client();
  $git->authenticate(BBN_GITHUB_TOKEN, Github\Client::AUTH_HTTP_TOKEN);

  // Get the latest version
  $latest = '';
  try {
    if ( $releases = $git->api('repo')->releases() ){
      $l = $releases->latest($model->data['git_user'], $model->data['git_repo']);
      $latest = !empty($l) ? $l['tag_name'] : '';
    }
  }
  catch (Throwable $e) {
    try {
      $tags = $git->api('repo')->tags($model->data['git_user'], $model->data['git_repo']);
    }
    catch (Throwable $e){
      $tags = [];
    }
    if ( !empty($tags) && !empty($tags[0]['name']) ){
      $latest = $tags[0]['name'];
    }
  }

  if ( $releases ){
    try {
      $versions = $releases->all($model->data['git_user'], $model->data['git_repo']);
    }
    catch (Throwable $e){
      $versions = [];
    }
  }
  // Can't ger versions
  else {
    $versions = [];
  }
  // Create a list of versions like idversion => nameversion
  if ( !empty($versions) && is_array($versions) ){
    $tmp = [];
    foreach ( $versions as $ver ){
      array_push($tmp, [
        'id' => $ver['id'],
        'text' => $ver['tag_name'] . ($latest === $ver['tag_name'] ? ' ---> latest <---' : ''),
        'is_latest' => $latest === $ver['tag_name']
      ]);
    }
    $versions = $tmp;
  }
  /*else if ( !empty($tags) ){
    $versions = array_map(function($t){
      return [
        'id' => $t['name'],
        'text' => $t['name'],
        'is_latest' => false
      ];
    }, $tags );
  }*/
  else if ( !empty($latest) ){
    $versions = [[
      'id' => $latest,
      'text' => $latest . ' ---> latest <---',
      'is_latest' => true
    ]];
  }

  return [
    'git_repo' => $model->data['git_repo'],
    'git_user' => $model->data['git_user'],
    'latest' => $latest,
    'versions' => $versions
  ];
}