<?php
if( !empty($model->data['folder']) ){
  return [
    'depend' => $model->data['db']->getRows('
      SELECT "vers"."id" AS id_ver, "vers"."name" AS version, "libr"."name" AS name,
        "libr"."title" AS lib_title, "dependencies"."order"
      FROM "versions"
      JOIN "libraries"
        ON "versions"."library" = "libraries"."name"
        AND "versions"."name" = "libraries"."latest"
      JOIN "dependencies"
        ON "versions"."id" = "dependencies"."id_slave"
      JOIN "versions" AS vers
        ON "dependencies"."id_master" = "vers"."id"
      JOIN "libraries" AS libr
        ON "vers"."library" = "libr"."name"
      WHERE "libraries"."name" = ?
      GROUP BY ("lib_name")
      ORDER BY "libr"."title" COLLATE NOCASE ASC',
      $model->data['folder']
    ),
    'dependent' => $model->data['db']->getRows("
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
    WHERE libraries.name = ?
    ORDER BY libr.name ASC",
      $model->data['folder']
    )
  ];
}
return ['succes' => false];
