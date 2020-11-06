<?php

class Promosi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('promosi_model');
    }
    function index()
    {
        if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=="Kasir")
        {
            redirect('');
        }
        $data                   = $this->app_model->general();
        $data['data']           = $this->promosi_model->get();
        $data['halaman']        = 'promosi/index';
        $this->load->view('layout/index',$data);
    }
    function simpan()
    {
        $data['promosi_teks']   = $this->input->post('promosi_teks');
        if($this->promosi_model->update($data))
        {
            echo '<script type="text/javascript">alert("Berhasil disimpan!");window.location="'.base_url().'index.php/promosi";</script>';
        }
        else
        {
            echo '<script type="text/javascript">alert("Gagal disimpan!");history.go(-1);</script>';
        }
    }
}

?>