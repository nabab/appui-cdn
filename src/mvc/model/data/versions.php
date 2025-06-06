<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 09:43
 */
use bbn\X;
/** @var bbn\Mvc\Model $model */
$res['success'] = false;
/*if ( !empty($model->data['data']['id_lib']) && !isset($model->data['id_lib']) ){
  $model->data['id_lib'] = $model->data['data']['id_lib'];
}*/

if ( !empty($model->data['db']) && !empty($model->data['id_lib']) ){
  // Get all library's versions
  $versions =  $model->data['db']->getRows("
    SELECT versions.*,
      CASE WHEN versions.name = libraries.latest THEN 1 ELSE 0 END AS is_latest
    FROM versions
    JOIN libraries
      ON versions.library = libraries.name
    WHERE versions.library = ?
    ORDER BY internal DESC",
    $model->data['id_lib']
  );

  foreach( $versions as $i => $ver ){
    // Get all version's dependencies
    $versions[$i]['dependencies'] = $model->data['db']->getRows("
      SELECT libraries.title, libraries.name, Versions.name AS version
      FROM dependencies
        LEFT JOIN versions
          ON id_master = versions.id
        LEFT JOIN libraries
          ON versions.library = libraries.name
      WHERE id_slave = ?
      GROUP BY libraries.title
      ORDER BY libraries.name",
      $ver['id']
    );

    // Get all version's slave dependencies
    $versions[$i]['slave_dependencies'] = $model->data['db']->getRows("
      SELECT libr.name, libr.title, vers.name AS version
      FROM versions
      JOIN libraries
        ON versions.library = libraries.name
        AND versions.name = libraries.latest
      JOIN dependencies
        ON versions.id = dependencies.id_master
      JOIN versions AS vers
        ON dependencies.id_slave = vers.id
      JOIN libraries AS libr
        ON vers.library = libr.name
      WHERE versions.id = ?
      GROUP BY libr.name
      ORDER BY libr.title COLLATE NOCASE ASC, vers.internal DESC",
      $ver['id']
    );

    // Make version's files TreeView
    $versions[$i]['files_tree'] = X::makeTree((array)json_decode($ver['content']));
    //$versions[$i]['files_tree'] = json_decode($ver['content']);
  }
  $res = [
    'success' => true,
    'versions' => $versions
  ];
  return $res;
}
return $res;
