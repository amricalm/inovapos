<?php

class Diskon_promo extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('curl');  
    }
    //---- inovaPOS 14.0 -----
    function df($offset=0)
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }

        $data                       = $this->app_model->general();         
        $data['halaman']            = 'diskon_promo/daftar';
        $data['judulweb']           = ' | Daftar Diskon/Promo';
        $this->load->view('layout/index',$data);
    
    }
    
    function aj_df()
    {
        $this->load->library('curl');
        $data               = $this->app_model->general();   
        $data['url']        = $this->app_model->system('ka_service_url'). "DfDiskonPromo" ;
        $hasil              = $this->curl->simple_get($data['url']); 
        $hasil              = json_decode($hasil);
        echo $hasil->DfDiskonPromoResult;
    }  
    
    
}