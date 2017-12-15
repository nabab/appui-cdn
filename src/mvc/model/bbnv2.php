<?php
/**
 * Created by PhpStorm.
 * User: BBN
 * Date: 21/03/2017
 * Time: 15:16
 */
/** @var \bbn\mvc\model $model */
$bbn = json_decode('{"siteTitle":{"name":"siteTitle","ns":"env","num":0,"occ":[]},"logging":{"name":"logging","ns":"env","num":0,"occ":[]},"cdn":{"name":"cdn","ns":"env","num":0,"occ":[]},"lang":{"name":"lang","ns":"env","num":0,"occ":[]},"body":{"name":"body","ns":"env","num":0,"occ":[]},"win":{"name":"win","ns":"env","num":0,"occ":[]},"host":{"name":"host","ns":"env","num":0,"occ":[]},"url":{"name":"url","ns":"env","num":0,"occ":[]},"old_path":{"name":"old_path","ns":"env","num":0,"occ":[]},"loading":{"name":"loading","ns":"env","num":0,"occ":[]},"width":{"name":"width","ns":"env","num":0,"occ":[]},"height":{"name":"height","ns":"env","num":0,"occ":[]},"focused":{"name":"focused","ns":"env","num":0,"occ":[]},"last_focus":{"name":"last_focus","ns":"env","num":0,"occ":[]},"sleep":{"name":"sleep","ns":"env","num":0,"occ":[]},"loaders":{"name":"loaders","ns":"env","num":0,"occ":[]},"resizeTimer":{"name":"resizeTimer","ns":"env","num":0,"occ":[]},"params":{"name":"params","ns":"env","num":0,"occ":[]},"isInit":{"name":"isInit","ns":"env","num":0,"occ":[]},"root":{"name":"root","ns":"env","num":0,"occ":[]},"path":{"name":"path","ns":"env","num":0,"occ":[]},"connection_failures":{"name":"connection_failures","ns":"env","num":0,"occ":[]},"connection_max_failures":{"name":"connection_max_failures","ns":"env","num":0,"occ":[]},"pages":{"name":"pages","ns":"env","num":0,"occ":[]},"userId":{"name":"userId","ns":"env","num":0,"occ":[]},"defaultAjaxErrorFunction":{"name":"defaultAjaxErrorFunction","ns":"fn","num":0,"occ":[]},"defaultPreLinkFunction":{"name":"defaultPreLinkFunction","ns":"fn","num":0,"occ":[]},"defaultLinkFunction":{"name":"defaultLinkFunction","ns":"fn","num":0,"occ":[]},"defaultPostLinkFunction":{"name":"defaultPostLinkFunction","ns":"fn","num":0,"occ":[]},"defaultStartLoadingFunction":{"name":"defaultStartLoadingFunction","ns":"fn","num":0,"occ":[]},"defaultEndLoadingFunction":{"name":"defaultEndLoadingFunction","ns":"fn","num":0,"occ":[]},"defaultHistoryFunction":{"name":"defaultHistoryFunction","ns":"fn","num":0,"occ":[]},"defaultResizeFunction":{"name":"defaultResizeFunction","ns":"fn","num":0,"occ":[]},"defaultAlertFunction":{"name":"defaultAlertFunction","ns":"fn","num":0,"occ":[]},"ajax":{"name":"ajax","ns":"fn","num":0,"occ":[]},"link":{"name":"link","ns":"fn","num":0,"occ":[]},"window":{"name":"window","ns":"fn","num":0,"occ":[]},"callback":{"name":"callback","ns":"fn","num":0,"occ":[]},"setNavigationVars":{"name":"setNavigationVars","ns":"fn","num":0,"occ":[]},"post_out":{"name":"post_out","ns":"fn","num":0,"occ":[]},"post":{"name":"post","ns":"fn","num":0,"occ":[]},"treat_vars":{"name":"treat_vars","ns":"fn","num":0,"occ":[]},"getParam":{"name":"getParam","ns":"fn","num":0,"occ":[]},"setParam":{"name":"setParam","ns":"fn","num":0,"occ":[]},"makeURL":{"name":"makeURL","ns":"fn","num":0,"occ":[]},"getURL":{"name":"getURL","ns":"fn","num":0,"occ":[]},"alert":{"name":"alert","ns":"fn","num":0,"occ":[]},"confirm":{"name":"confirm","ns":"fn","num":0,"occ":[]},"closePopup":{"name":"closePopup","ns":"fn","num":0,"occ":[]},"popup":{"name":"popup","ns":"fn","num":0,"occ":[]},"resize_popup":{"name":"resize_popup","ns":"fn","num":0,"occ":[]},"get_popup":{"name":"get_popup","ns":"fn","num":0,"occ":[]},"add_inputs":{"name":"add_inputs","ns":"fn","num":0,"occ":[]},"cancel":{"name":"cancel","ns":"fn","num":0,"occ":[]},"reset":{"name":"reset","ns":"fn","num":0,"occ":[]},"submit":{"name":"submit","ns":"fn","num":0,"occ":[]},"setInitialValues":{"name":"setInitialValues","ns":"fn","num":0,"occ":[]},"formupdated":{"name":"formupdated","ns":"fn","num":0,"occ":[]},"fieldValue":{"name":"fieldValue","ns":"fn","num":0,"occ":[]},"formdata":{"name":"formdata","ns":"fn","num":0,"occ":[]},"formChanges":{"name":"formChanges","ns":"fn","num":0,"occ":[]},"history":{"name":"history","ns":"fn","num":0,"occ":[]},"replaceHistory":{"name":"replaceHistory","ns":"fn","num":0,"occ":[]},"addHistoryScript":{"name":"addHistoryScript","ns":"fn","num":0,"occ":[]},"money":{"name":"money","ns":"fn","num":0,"occ":[]},"fdate":{"name":"fdate","ns":"fn","num":0,"occ":[]},"timestamp":{"name":"timestamp","ns":"fn","num":0,"occ":[]},"log":{"name":"log","ns":"fn","num":0,"occ":[]},"stat":{"name":"stat","ns":"fn","num":0,"occ":[]},"tagName":{"name":"tagName","ns":"fn","num":0,"occ":[]},"getAttributes":{"name":"getAttributes","ns":"fn","num":0,"occ":[]},"getPath":{"name":"getPath","ns":"fn","num":0,"occ":[]},"makeDeferred":{"name":"makeDeferred","ns":"fn","num":0,"occ":[]},"wait_for_script":{"name":"wait_for_script","ns":"fn","num":0,"occ":[]},"order":{"name":"order","ns":"fn","num":0,"occ":[]},"compare":{"name":"compare","ns":"fn","num":0,"occ":[]},"search":{"name":"search","ns":"fn","num":0,"occ":[]},"filterObj":{"name":"filterObj","ns":"fn","num":0,"occ":[]},"get_row":{"name":"get_row","ns":"fn","num":0,"occ":[]},"get_field":{"name":"get_field","ns":"fn","num":0,"occ":[]},"countProperties":{"name":"countProperties","ns":"fn","num":0,"occ":[]},"numProperties":{"name":"numProperties","ns":"fn","num":0,"occ":[]},"removePrivateProp":{"name":"removePrivateProp","ns":"fn","num":0,"occ":[]},"extend":{"name":"extend","ns":"fn","num":0,"occ":[]},"autoExtend":{"name":"autoExtend","ns":"fn","num":0,"occ":[]},"resize":{"name":"resize","ns":"fn","num":0,"occ":[]},"toggle_full_screen":{"name":"toggle_full_screen","ns":"fn","num":0,"occ":[]},"insertContent":{"name":"insertContent","ns":"fn","num":0,"occ":[]},"appendContent":{"name":"appendContent","ns":"fn","num":0,"occ":[]},"cssFullWidth":{"name":"cssFullWidth","ns":"fn","num":0,"occ":[]},"cssFullHeight":{"name":"cssFullHeight","ns":"fn","num":0,"occ":[]},"cssForm":{"name":"cssForm","ns":"fn","num":0,"occ":[]},"cssBlocks":{"name":"cssBlocks","ns":"fn","num":0,"occ":[]},"cssMason":{"name":"cssMason","ns":"fn","num":0,"occ":[]},"analyzeContent":{"name":"analyzeContent","ns":"fn","num":0,"occ":[]},"onResize":{"name":"onResize","ns":"fn","num":0,"occ":[]},"propagateResize":{"name":"propagateResize","ns":"fn","num":0,"occ":[]},"redraw":{"name":"redraw","ns":"fn","num":0,"occ":[]},"uniqString":{"name":"uniqString","ns":"fn","num":0,"occ":[]},"md5":{"name":"md5","ns":"fn","num":0,"occ":[]},"escapeRegExp":{"name":"escapeRegExp","ns":"fn","num":0,"occ":[]},"roundDecimal":{"name":"roundDecimal","ns":"fn","num":0,"occ":[]},"rgb2hex":{"name":"rgb2hex","ns":"fn","num":0,"occ":[]},"camelize":{"name":"camelize","ns":"fn","num":0,"occ":[]},"camelToCss":{"name":"camelToCss","ns":"fn","num":0,"occ":[]},"randomInt":{"name":"randomInt","ns":"fn","num":0,"occ":[]},"randomString":{"name":"randomString","ns":"fn","num":0,"occ":[]},"isColor":{"name":"isColor","ns":"fn","num":0,"occ":[]},"isDimension":{"name":"isDimension","ns":"fn","num":0,"occ":[]},"isEmpty":{"name":"isEmpty","ns":"fn","num":0,"occ":[]},"shorten":{"name":"shorten","ns":"fn","num":0,"occ":[]},"replaceAll":{"name":"replaceAll","ns":"fn","num":0,"occ":[]},"remove_quotes":{"name":"remove_quotes","ns":"fn","num":0,"occ":[]},"remove_nl":{"name":"remove_nl","ns":"fn","num":0,"occ":[]},"remove_all":{"name":"remove_all","ns":"fn","num":0,"occ":[]},"nl2br":{"name":"nl2br","ns":"fn","num":0,"occ":[]},"br2nl":{"name":"br2nl","ns":"fn","num":0,"occ":[]},"html2text":{"name":"html2text","ns":"fn","num":0,"occ":[]},"removeAccents":{"name":"removeAccents","ns":"fn","num":0,"occ":[]},"cssExists":{"name":"cssExists","ns":"fn","num":0,"occ":[]},"animateCss":{"name":"animateCss","ns":"fn","num":0,"occ":[]},"userName":{"name":"userName","ns":"fn","num":0,"occ":[]},"userGroup":{"name":"userGroup","ns":"fn","num":0,"occ":[]},"userAvatar":{"name":"userAvatar","ns":"fn","num":0,"occ":[]},"init":{"name":"init","ns":"fn","num":0,"occ":[]},"ajaxErrorFunction":{"name":"ajaxErrorFunction","ns":"fn","num":0,"occ":[]},"correctGridPost":{"name":"correctGridPost","ns":"fn","num":0,"occ":[]},"gridParse":{"name":"gridParse","ns":"fn","num":0,"occ":[]},"addToggler":{"name":"addToggler","ns":"fn","num":0,"occ":[]},"setWidgets":{"name":"setWidgets","ns":"fn","num":0,"occ":[]},"text2value":{"name":"text2value","ns":"fn","num":0,"occ":[]},"bool2checkbox":{"name":"bool2checkbox","ns":"fn","num":0,"occ":[]},"hideUneditable":{"name":"hideUneditable","ns":"fn","num":0,"occ":[]},"formValidator":{"name":"formValidator","ns":"fn","num":0,"occ":[]},"userAvatarImg":{"name":"userAvatarImg","ns":"fn","num":0,"occ":[]},"loggers":{"name":"loggers","ns":"var","num":0,"occ":[]},"datatypes":{"name":"datatypes","ns":"var","num":0,"occ":[]},"shortenLen":{"name":"shortenLen","ns":"var","num":0,"occ":[]},"keys":{"name":"keys","ns":"var","num":0,"occ":[]},"comparators":{"name":"comparators","ns":"var","num":0,"occ":[]},"operators":{"name":"operators","ns":"var","num":0,"occ":[]},"defaultDiacriticsRemovalMap":{"name":"defaultDiacriticsRemovalMap","ns":"var","num":0,"occ":[]},"defaultAvatar":{"name":"defaultAvatar","ns":"var","num":0,"occ":[]}}', true);
$cdn = BBN_CDN_PATH;
$vars = ['env', 'var', 'fn'];
$paths = [
  $cdn.'APST-UI/js',
  $cdn.'lib/bbn/0.2/src',
  $cdn.'lib/jquery-tabnav/0.4',
  $cdn.'lib/jquery-codemirror/0.3',
  $cdn.'lib/jquery-jsoneditor/0.2',
  $cdn.'lib/appui/0.4/src',
  $cdn.'lib/vue-kendo/0.2/src',
  BBN_APP_PATH.'mvc'
];
foreach ( $model->get_plugins() as $plugin ){
  $paths[] = $plugin['path'].'mvc';
}
$files = [];
foreach ( $paths as $p ){
  if ( $tmp = \bbn\file\dir::scan($p, 'js') ){
    $files = array_merge($files, $tmp);
  }
}
foreach ( $files as $f ){
  foreach ( file($f) as $ln => $c ){
    while ( ($pos = strpos($c, 'bbn.')) !== false ){
      $c = substr($c, $pos + 4);
      if ( $idx = strpos($c, '.') ){
        $var = substr($c, 0, $idx);
        $c = substr($c, \strlen($var) + 1);
        if ( \in_array($var, $vars) && ($res = preg_match('/[^A-z0-9_]/', $c, $matches, PREG_OFFSET_CAPTURE)) ){
          $i = $matches[0][1];
          $key = substr($c, 0, $i);
          $c = substr($c, \strlen($key) + 1);
          if ( !isset($bbn[$key]) ){
            $bbn[$key] = [
              'name' => $key,
              'ns' => $var,
              'num' => 0,
              'occ' => []
            ];
          }
          $bbn[$key]['num']++;
          $bbn[$key]['occ'][] = $f;
        }
      }
    }
  }
}
foreach ( $bbn as $k => $v ){
  $new = $k;
  while ( $idx = strpos($new, '_') ){
    $new = substr($new, 0, $idx).
      strtoupper(substr($new, $idx+1, 1)).
      substr($new, $idx+2);
  }
  if ( $v['ns'] === 'var' ){
    $new = '_'.$new;
  }
  else if ( $v['ns'] === 'env' ){
    $new .= '()';
  }
  else if ( ($v['ns'] === 'fn') && (strpos($new, 'default') === 0) ){
    $new = '_fn'.substr($new, 7, -8);
  }
  else if ( $new === 'formdata' ){
    $new = 'formData';
  }
  else if ( $new === 'formupdated' ){
    $new = 'formUpdated';
  }
  $bbn[$k]['newName'] = $new;
}
return ['occurences' => array_values($bbn)];
