<?php
/** @var $model \bbn\Mvc\Model */

return $model->data['db']->getRows("
    SELECT *
    FROM licences
    ORDER BY name COLLATE NOCASE ASC
  ");