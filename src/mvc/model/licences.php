<?php
/** @var $model \bbn\mvc\model */

return $model->data['db']->get_rows("
    SELECT *
    FROM licences
    ORDER BY name COLLATE NOCASE ASC
  ");