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
    function group_form($trx)
    {
        $data                       = $this->app_model->general();
        switch($trx)
        {
            case 'tambah' :
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'group_barang/tambah';
                $this->load->view('layout/index',$data);
                break;
            case 'edit' :
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'group_barang/edit';
                $data['data']                   = $this->group_barang_model->get($this->uri->segment(4),'','');
                $this->load->view('layout/index',$data);
                break;
            case 'hapus' :
                $this->group_barang_exec($trx,$this->uri->segment(4));
                break;
        }
    }
    function group_barang_exec($trx)
    {
        $data['group_kd']                   = $this->input->post('kd_group');
        $data['group_nm']                   = $this->input->post('nm_group');
        $data['group_hp']                   = $this->input->post('hp');
        $data['group_coa_hpp']                    = $this->input->post('coa_hpp');
        $data['group_coa_diskon_pembelian']       = $this->input->post('coa_diskon_pembelian');
        $data['group_coa_penjualan']              = $this->input->post('coa_penjualan');
        $data['group_coa_persediaan']             = $this->input->post('coa_persediaan');
        $data['group_coa_piutang']                = $this->input->post('coa_piutang');
        $data['group_coa_diskon']                 = $this->input->post('coa_diskon');
        $data['group_jn_group']                   = $this->input->post('jn_group');
        
        switch($trx)
        {
            case 'tambah' :
                $data['uid']                = $this->session->userdata('user_kd');
                $data['doe']                = date('Y-m-d h:i:s');
                if($this->group_barang_model->simpan($data))
                {
                    echo '<script type="text/javascript">alert("Berhasil disimpan!");parent.iclose();</script>';
                }
                else
                {
                    echo '<script type="text/javascript">alert("Gagal disimpan");</script>';
                }
                break;
            case 'edit' :
                $data['uid_edit']           = $this->session->userdata('user_kd');
                $data['doe_edit']           = date('Y-m-d h:i:s');
                if($this->group_barang_model->update($this->input->post('kd_group'),$data))
                {
                    echo '<script type="text/javascript">alert("Berhasil diupdate!");parent.iclose();</script>';
                }
                else
                {
                    echo '<script type="text/javascript">alert("Gagal diupdate");</script>';
                }
                break;
            case 'hapus' :
                $this->group_barang_model->hapus($this->uri->segment(4));
                redirect('group_barang/daftar');
                break;
        }
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