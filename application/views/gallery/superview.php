<?php

$this->load->view('gallery/common/header');

//$this->load->view('gallery/commom/nav');

$this->load->view('gallery/' . $template);

$this->load->view('gallery/common/footer');

exit;

?>