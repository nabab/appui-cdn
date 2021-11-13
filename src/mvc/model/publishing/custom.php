<?php
/**
 * What is my purpose?
 *
 **/

/** @var $model \bbn\Mvc\Model*/

$dir = BBN_CDN_PATH.'lib/bbn-vue/master/dist/js_single_files';
$dirLang = $dir . '/i18n';
$dirComp = $dir . '/components';
$fs  = new \bbn\File\System();
if (isset($model->data['components'])) {
  $fs->cd($dir);
  $js = '';
  $css = '';
  if ($fs->isFile("bbn-vue.min.js")) {
    $js .= $fs->getContents("bbn-vue.min.js").PHP_EOL;
  }
  if ($model->data['language'] !== 'en') {
    $fs->cd($dirLang);
    if ($fs->isFile("bbn-vue.".$model->data['language'].".min.js")) {
      $js .= $fs->getContents("bbn-vue.".$model->data['language'].".min.js").PHP_EOL;
    }
  }
  $fs->cd($dirComp);
  if (!empty($model->data['domcontentloaded'])){
    $js .= 'document.addEventListener("DOMContentLoaded", () => {';
  }
  foreach ($model->data['components'] as $cp) {
    if ($fs->isFile("$cp/$cp.min.js")) {
      $js .= $fs->getContents("$cp/$cp.min.js").PHP_EOL;
      if (($model->data['language'] !== 'en')
        && $fs->isFile("$cp/$cp.".$model->data['language'].".min.js")
      ) {
        $js .= $fs->getContents("$cp/$cp.".$model->data['language'].".min.js").PHP_EOL;
      }
      if ($fs->isFile("$cp/$cp.css")) {
        $css .= $fs->getContents("$cp/$cp.css");
      }
    }
  }
  if (!empty($model->data['domcontentloaded'])){
    $js .= '});';
  }
  $name = 'static_'.date('Y-m-d_H-i-s');
  file_put_contents($model->contentPath().$name.'.js', $js);
  file_put_contents($model->contentPath().$name.'.css', $css);
  return [
    'success' => true,
    'files' => $name
  ];
}
else {
  $fs->cd($dirComp);
  $components  = $fs->getDirs('.');
  $languages = [
    [
      'text' => 'English',
      'value' => 'en'
    ], [
      'text' => 'Français',
      'value' => 'fr'
    ], [
      'text' => 'Italian',
      'value' => 'it'
    ]
  ];
  return [
    'components' => $components,
    'languages' => $languages
  ];
}
