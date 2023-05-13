<?php

$where = false;
if ($model->hasData('folder')) {
  $where = ['libraries.name' => $model->data['folder']];
}
elseif ($model->hasData('version')) {
  $where = [
    ['id_slave' => $model->data['version']],
    ['id_master' => $model->data['version']]
  ];
}

if ($where) {
  return [
    'data' => [
      'depend' => $model->data['db']->rselectAll([
        'tables' => 'dependencies',
        'fields' => [
          'versions.id',
          'version' => 'versions.name',
          'libraries.name',
          'lib_title' => 'libraries.title',
          'dependencies.order'
        ],
        'join' => [
          [
            'table' => 'versions',
            'on' => [
              [
                'field' => 'versions.id',
                'exp' => 'dependencies.id_master'
              ]
            ]
          ], [
            'table' => 'libraries',
            'on' => [
              [
                'field' => 'libraries.name',
                'exp' => 'versions.library'
              ]
            ]
          ]
        ],
        'where' => isset($where[1]) ? $where[0] : $where,
        'order' => [
          'field' => 'libraries.title',
          'dir' => 'ASC'
        ],
        'group_by' => 'libraries.name'
      ]),
      'dependent' => $model->data['db']->rselectAll([
        'tables' => 'dependencies',
        'fields' => [
          'versions.id',
          'version' => 'versions.name',
          'libraries.name',
          'lib_title' => 'libraries.title',
          'dependencies.order'
        ],
        'join' => [
          [
            'table' => 'versions',
            'on' => [
              [
                'field' => 'versions.id',
                'exp' => 'dependencies.id_slave'
              ]
            ]
          ], [
            'table' => 'libraries',
            'on' => [
              [
                'field' => 'libraries.name',
                'exp' => 'versions.library'
              ]
            ]
          ]
        ],
        'where' => isset($where[0]) ? $where[1] : $where,
        'order' => [
          'field' => 'libraries.title',
          'dir' => 'ASC'
        ],
        'group_by' => 'libraries.name'
      ]),
      'last' => $model->data['db']->last()
    ]
  ];
}
return ['succes' => false];
