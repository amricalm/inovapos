<?php

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Laporan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('history_kasir_model');
        $this->load->model('group_barang_model');
        $this->load->model('barang_saldo_model');
        $this->load->model('log_proses_model');
    }
    function index()
    {
        
    }
    function penjualan($offset=0)
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();

        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('grup') : $this->session->userdata('cbogrupbarang');
        $this->session->set_userdata(array('cbogrupbarang'=>$grup));
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupbarang');
        $this->load->model('laporan_model');
        $data['data_all']           = $this->history_kasir_model->penjualan($this->session->userdata('tanggal'),$this->session->userdata('shift'),$data['cbogrup'],$data['txtcari'],'','');
        
        $base_url                   = base_url().'index.php/laporan/penjualan';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 10;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        $data['data']               = $this->history_kasir_model->penjualan($this->session->userdata('tanggal'),$this->session->userdata('shift'),$data['cbogrup'],$data['txtcari'],$per_page,$offset);
        //echo $this->db->last_query();
        //die();
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'laporan/penjualan';
        $data['judulweb']           = ' | Penjualan';
        $this->load->view('layout/index',$data);   
    }

    function penjualan_per_faktur($offset=0)
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['data']               = $this->history_kasir_model->penjualan_per_faktur($this->session->userdata('tanggal'),$this->session->userdata('shift'),'','','','');
        //echo $this->db->last_query();
        //die();
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'laporan/penjualan_per_faktur';
        $data['judulweb']           = ' | Penjualan';
        $this->load->view('layout/index',$data);   
    }
    function rekap_penjualan($offset=0)
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();

        $tgldarifilter              = ($this->input->post('submit')!='') ? $this->input->post('tgldari') : $this->session->userdata('tgldarifilter');
        $this->session->set_userdata(array('tgldarifilter'=>$tgldarifilter));
        $tglsampaifilter            = ($this->input->post('submit')!='') ? $this->input->post('tglsampai') : $this->session->userdata('tglsampaifilter');
        $this->session->set_userdata(array('tglsampaifilter'=>$tglsampaifilter));
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('grup') : $this->session->userdata('cbogrupbarang');
        $this->session->set_userdata(array('cbogrupbarang'=>$grup));
        $data['tgldarifilter']      = $this->session->userdata('tgldarifilter');
        $data['tglsampaifilter']    = $this->session->userdata('tglsampaifilter');
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupbarang');
        $this->load->model('laporan_model');
        $data['data_all']           = $this->history_kasir_model->rekap_penjualan($data['tgldarifilter'],$data['tglsampaifilter'],$data['txtcari'],$data['cbogrup']);
        //echo $this->db->last_query();
        //die();
        $base_url                   = base_url().'index.php/laporan/rekap_penjualan';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 20;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        $data['data']               = $this->history_kasir_model->rekap_penjualan($data['tgldarifilter'],$data['tglsampaifilter'],$data['txtcari'],$data['cbogrup'],$per_page,$offset);
        //echo $this->db->last_query();
        //die();
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'laporan/rekap_penjualan';
        $data['judulweb']           = ' | Penjualan';
        $this->load->view('layout/index',$data);    
    }
    function laporan_elektrik($offset=0)
    {
        $this->load->model('laporan_model');
        $this->load->model('kasir_elektrik_model');
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();

        $tgldarifilter              = ($this->input->post('submit')!='') ? $this->input->post('tgldari') : $this->session->userdata('tgldarifilter');
        $this->session->set_userdata(array('tgldarifilter'=>$tgldarifilter));
        $tglsampaifilter            = ($this->input->post('submit')!='') ? $this->input->post('tglsampai') : $this->session->userdata('tglsampaifilter');
        $this->session->set_userdata(array('tglsampaifilter'=>$tglsampaifilter));
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $data['tgldarifilter']      = ($this->session->userdata('tgldarifilter')!='') ? $this->session->userdata('tgldarifilter') : date('Y-m-d');
        $data['tglsampaifilter']    = ($this->session->userdata('tglsampaifilter')!='') ? $this->session->userdata('tglsampaifilter') : date('Y-m-d');
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['data_all']           = $this->kasir_elektrik_model->get($data['tgldarifilter'],$data['tglsampaifilter'],'',$data['txtcari']);
        
        $base_url                   = base_url().'index.php/laporan/laporan_elektrik';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 10;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        
        $data['data']               = $this->kasir_elektrik_model->get($data['tgldarifilter'],$data['tglsampaifilter'],'',$data['txtcari'],$offset,$per_page);
        $data['halaman']            = 'laporan/penjualan_elektrik';
        $data['judulweb']           = ' | Penjualan Elektrik';
        $this->load->view('layout/index',$data);  
    }
     
}