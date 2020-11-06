<?php

class Satuan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('satuan_model');
    }
    function index()
    {     
        redirect('satuan/daftar');
    }
    function daftar()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['halaman']            = 'satuan/index';
        $data['judulweb']           = ' | Daftar Satuan';
        $this->load->view('layout/index',$data);   
    }
    function list_for_dropdown()
    {
        $record                     = $this->satuan_model->get('','','');
        $rows                       = array();
        foreach($record->result() as $row)
        {
            $rows[(int)$row->kd_satuan]   = $row->nm_satuan;
        }
        
        print json_encode($rows);
    }
}

?>