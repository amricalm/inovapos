<?php
if(!isset($option_tampilan) || $option_tampilan=='')
{
    $this->load->view('layout/header');
}
else
{
    $this->load->view('layout/header_tanpa_menu');
}
$this->load->view($halaman);
if(!isset($option_tampilan) || $option_tampilan=='')
{
    $this->load->view('layout/footer');
}
else
{
    $this->load->view('layout/footer_tanpa_menu');
}
?>