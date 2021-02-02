<?php
/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 14/12/2016
 * Time: 18:59
 */
/** @var $model \bbn\Mvc\Model */

if ( !empty($model->data['db']) ){
  return $model->data['db']->rselectAll('libraries', [], [], ['title' => 'ASC']);
}
