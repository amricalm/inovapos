<?php

class Group_barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('group_barang_model');
    }
    function index()
    {     
        redirect('group_barang/daftar');
    }
    function daftar($offset=0)
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcarigroupbarang');
        $this->session->set_userdata(array('txtcarigroupbarang'=>$txtcari));
        $data['txtcari']            = $this->session->userdata('txtcarigroupbarang');
        
        $data['data_all']           = $this->group_barang_model->get('','','',$data['txtcari']);
        
        $base_url                   = base_url().'index.php/group_barang/daftar';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 10;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        $data['data']               = $this->group_barang_model->get('',$per_page,$offset,$data['txtcari']);
        $data['halaman']            = 'group_barang/index';
        $data['judulweb']           = ' | Daftar Grup Barang';
        $this->load->view('layout/index',$data);   
    }
    function list_for_dropdown()
    {
        $record                     = $this->group_barang_model->get('','','');
        $rows                       = array();
        foreach($record->result() as $row)
        {
            //$rows['names']          = $row->kd_group;
            //$rows['values']         = $row->nm_group;
            $rows[$row->kd_group]   = $row->nm_group;
        }
        
        print json_encode($rows);
    }
}

?>