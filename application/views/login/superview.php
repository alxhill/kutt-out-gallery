<?php

$this->load->view('gallery/common/header');

$this->load->view('gallery/common/nav');

$this->load->view('login/' . $template);

$this->load->view('gallery/common/footer');

exit;

?>