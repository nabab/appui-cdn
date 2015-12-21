<?php
/** @var $this \bbn\mvc\controller */

// DB connection
$db =& $this->data['db'];

return $db->get_rows("
    SELECT *
    FROM licences
    ORDER BY name COLLATE NOCASE ASC
  ");