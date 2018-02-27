<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 09:43
 */
/** @var $model \bbn\mvc\model */
$res['success'] = false;

if ( !empty($model->data['db']) && !empty($model->data['id_lib']) ){
  // Get all library's versions
  $versions =  $model->data['db']->get_rows("
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
    $versions[$i]['dependencies'] = $model->data['db']->get_rows("
      SELECT libraries.title, libraries.name, versions.name AS version
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
    $versions[$i]['slave_dependencies'] = $model->data['db']->get_rows("
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
    $versions[$i]['files_tree'] = \bbn\x::make_tree((array)json_decode($ver['content']));
  }
  $res = [
    'success' => true,
    'versions' => $versions
  ];
  return $res;
}
return $res;
