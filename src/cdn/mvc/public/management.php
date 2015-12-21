<?php
/** @var $this \bbn\mvc\controller */

if ( empty($this->post) ){
  echo $this
    ->set_title('CDN Management')
    ->add_js([
      'all_lib' => $this->get_model('./libraries'),
      'licences' => $this->get_model('./licences')
    ])
    ->get_view();
}
else {
  $this->data = array_merge($this->data, $this->post);
  $this->obj->data = $this->get_model('./libraries');
}
