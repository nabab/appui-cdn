<?php
/** @var bbn\Mvc\Model $model */

return $model->data['db']->getRows("
    SELECT *
    FROM licences
    ORDER BY name COLLATE NOCASE ASC
  ");