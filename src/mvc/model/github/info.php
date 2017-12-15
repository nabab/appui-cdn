<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 18:25
 */
/** @var $model \bbn\mvc\model */

if ( !\defined('BBN_GITHUB_TOKEN') ){
  die('BBN_GITHUB_TOKEN is not defined!');
}

if ( !empty($model->data['git_user']) && !empty($model->data['git_repo']) ){
  $git = new \Github\Client();
  $git->authenticate(BBN_GITHUB_TOKEN, \Github\Client::AUTH_HTTP_TOKEN);
  // Get repository's info
  $github = \bbn\x::to_object($git->api('repo')->show($model->data['git_user'], $model->data['git_repo']));

  // Check if the library already exists
  if ( empty($model->data['only_info']) && $model->data['db']->select('libraries', [], ['name' => $github->name]) ){
    return ['error' => _("The library exists.")];
  }

  // Get author
  $author = \bbn\x::to_object($git->api('user')->show($model->data['git_user']));
  //$tags = $git->api('repo')->tags($model->data['user'], $model->data['repo']);

  // Get repository's info from bower.json file if it exists
  if ( $git->api('repo')->contents()->exists($model->data['git_user'], $model->data['git_repo'], 'bower.json') ){
    $bower = json_decode($git->api('repo')->contents()->download($model->data['git_user'], $model->data['git_repo'], 'bower.json'));
  }
  // Get repository's info from package.json file if it exists
  if ( $git->api('repo')->contents()->exists($model->data['git_user'], $model->data['git_repo'], 'package.json') ){
    $package = json_decode($git->api('repo')->contents()->download($model->data['git_user'], $model->data['git_repo'], 'package.json'));
  }

  if ( empty($model->data['only_info']) ){
    // Get all versions list and the latest version
    $ver_lat = $model->get_model('./versions', $model->data);
  }

  $info = [
    'title' => !empty($package->title) ? $package->title : $github->name,
    'name' => $github->name,
    'author' => $author->name,
    'licence' => !empty($bower->license) ? $bower->license : (!empty($package->license) ? $package->license : ''),
    'website' => !empty($github->homepage) ? $github->homepage : (!empty($package->homepage) ? $package->homepage : ''),
    'download_link' => 'https://github.com/'.$model->data['git_user'].'/'.$model->data['git_repo'].'/archive/master.zip',
    'doc_link' => !empty($github->has_wiki) ? 'https://github.com/'.$model->data['git_user'].'/'.$model->data['git_repo'].'/wiki' : '',
    'git' => $github->html_url,
    'support_link' => !empty($github->has_issues) ? 'https://github.com/'.$model->data['git_user'].'/'.$model->data['git_repo'].'/issues' : (!empty($package->bugs) ? $package->bugs : ''),
    'description' => !empty($github->description) ? $github->description : (!empty($package->description) ? $package->description : ''),
    'latest' => !empty($ver_lat['latest']) ? $ver_lat['latest'] : '',
    'versions' => !empty($ver_lat['versions']) ? $ver_lat['versions'] : '',
    'user' => $model->data['git_user'],
    'repo' => $model->data['git_repo']
  ];

  return ['data' => $info];
}