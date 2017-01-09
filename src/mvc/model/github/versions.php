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
    $l = $git->api('repo')->releases()->latest($model->data['git_user'], $model->data['git_repo']);
    $latest = !empty($l) ? $l['name'] : '';

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

  // Get all versions
  try {
    $versions = $git->api('repo')->releases()->all($model->data['git_user'], $model->data['git_repo']);
  }
  catch (Throwable $e){
    $versions = [];
  }
  // Create a list of versions like idversion => nameversion
  if ( !empty($versions) && is_array($versions) ){
    $tmp = [];
    foreach ( $versions as $ver ){
      array_push($tmp, [
        'id' => $ver['id'],
        'text' => $ver['name'] . ($latest === $ver['name'] ? ' ---> latest <---' : ''),
        'is_latest' => $latest === $ver['name']
      ]);
    }
    $versions = $tmp;
  }
  else {
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