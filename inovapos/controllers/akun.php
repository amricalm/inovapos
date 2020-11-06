<?php

class Akun extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('akun_model');
        $this->load->model('gol_akun_model');
    }
    function list_perkiraan($elemen=0,$offset=0)
    {
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'list_perkiraan';
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcariakun');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('grup') : $this->session->userdata('cbogrupakun');
        $this->session->set_userdata(array('cbogrupbarang'=>$grup));
        $data['txtcari']            = $this->session->userdata('txtcariakun');
        $data['cbogrup']            = $this->session->userdata('cbogrupakun');

        $data['data_all']           = $this->barang_model->get('','','',$data['txtcari'],$data['cbogrup']);
        $base_url                   = base_url().'index.php/barang/list_barang/'.$elemen;
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 30;
        $uri_segment                = 4;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 


        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['data']               = $this->barang_model->get('',$per_page,$offset,$data['txtcari'],$data['cbogrup']);
        $this->load->view('layout/index',$data);
    }
}

