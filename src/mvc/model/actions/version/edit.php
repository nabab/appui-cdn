<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 09:50
 */
/** @var bbn\Mvc\Model $model */


if ( !empty($model->data['db']) &&
  !empty($model->data['id']) &&
  ( !empty($model->data['files']) ||
    !empty($model->data['languages']) ||
    !empty($model->data['themes']) )
){

  $lib = $model->data['db']->rselect('libraries', ['latest'], ['name' => $model->data['library']]);

  $languages = [];
  $themes = [];
  foreach ( $model->data['languages'] as $l ){
    array_push($languages, $l['path']);
  }
  foreach ( $model->data['themes'] as $l ){
    array_push($themes, $l['path']);
  }
  $content = [
    'files' => !empty($model->data['files']) ? $model->data['files'] : [],
    'lang' => $languages,
    'theme_files' => $themes
  ];

  if ( !empty($content['theme_files']) ){
    $content['theme_prepend'] = $model->data['theme_prepend'];
  }
  else{
    $content['theme_prepend'] = false;
  }


  if ( $model->data['db']->update('versions', ['content' => json_encode($content)], ['id' => $model->data['id']]) ){
    if ( !empty($model->data['is_latest']) ){
      //$ver_lib = $model->data['db']->rselect('versions', ['name', 'library'], ['id' => $model->data['id']]);
      //$lib = $model->data['db']->rselect('libraries', ['latest'], ['name' => $model->data['library']]);

      if ( ($model->data['name'] !== $lib['latest']) &&
        !$model->data['db']->update('libraries', ['latest' => $model->data['name']], ['name' => $model->data['library']])
      ){
        return ['error' => 'Error to update latest library\'s version'];
      }
    }//temporaney add a latest if latest in blibraries is empty
  /*  if ( isset($lib) && empty($lib['latest']) ){
      $all_versions = $model->data['db']->rselectAll(
        "versions", // table
        ['name'], // column name
        ["library" => $model->data['library']], // WHERE
        ["id" => DESC] // ORDER
      );

      if( !empty($all_versions) &&
        (!$model->data['db']->update('libraries', ['latest' => $all_versions[0]['name']], ['name' => $model->data['library']]))
      ){
        return ['error' => 'Error to add latest library\'s version'];
      }
    }*/
    if ( !empty($model->data['dependencies']) ){
      $dependencies = [];
      foreach ( $model->data['dependencies'] as $dep ){
        $dependencies[$dep['id_ver']] = $dep;
      }
      /*$old_dep = $model->data['db']->getColArray('
        SELECT dependencies.id_master
        FROM dependencies
        JOIN versions
          ON dependencies.id_master = versions.id
        WHERE dependencies.id_slave = ?
        GROUP BY versions.library
        ORDER BY versions.internal',
        $model->data['id']
      );*/
      $old_dep = $model->data['db']->rselectAll([
        'table' => "dependencies",
        'fields' => 'id_master',
        'join' => [[
          'table' => 'versions',
          'on' => [            
            'conditions' => [
              'field' => 'dependencies.id_master',          
              'exp' => 'versions.id'
            ]
          ]      
        ]],
        'where' => [
          'conditions' => [[
            'field' => "dependencies.id_slave",
            'value' => $model->data['id']
          ]]
        ],
        'group_by' => 'versions.library',
        'order' => [[
          'field' => 'versions.internal',
          'dir' => 'DESC'
        ]]        
      ]);
    
      if ( count($old_dep) ){
        
        foreach ( $old_dep as $old ){
          if ( !\in_array($old, array_keys($dependencies)) ){
            if ( !$model->data['db']->delete('dependencies', [
              'id_slave' => $model->data['id'],
              'id_master' => $old
            ]) ){
              return ['error' => _('Error to delete a version\'s dependency')];
            };
          }
        }
      }   
      
      foreach ( $dependencies as $idd => $dep ){
        if ( !\in_array($idd, $old_dep) ){
          die(var_dump($model->data['id'], $idd, $dep['order'],$model->data['db'],$model->data['db']->insert('dependencies', [
            'id_slave' => $model->data['id'],
            'id_master' => $idd,
            'order' => $dep['order']
          ])));   
          if ( !$model->data['db']->insert('dependencies', [
            'id_slave' => $model->data['id'],
            'id_master' => $idd,
            'order' => $dep['order']
          ]) ){
            return ['error' => _('Error to insert a new version\'s dependency') ];
          }
        }
        else {
          if ( !$model->data['db']->update('dependencies', [
            'order' => $dep['order']
          ], [
            'id_slave' => $model->data['id'],
            'id_master' => $idd
          ]) ){
            return ['error' => _('Error to update a version\'s dependency') ];
          }
        }
      }
    }
    return ['success' => 1];
  }
  return false;
}
