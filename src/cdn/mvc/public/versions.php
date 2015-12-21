<?php
/** @var $this \bbn\mvc\controller */

if ( !empty($this->post) ){
  $this->data = array_merge($this->data, $this->post);
  $this->obj->data = $this->get_model();
}