<?php

class Pelanggan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('pelanggan_model');
    }
    function index()
    {     
        redirect('pelanggan/daftar');
    }
    function daftar($offset=0)
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcari');
        $this->session->set_userdata(array('txtcari'=>$txtcari));
        $data['txtcari']            = $this->session->userdata('txtcari');
        
        $data['data_all']           = $this->pelanggan_model->get('','','',$data['txtcari']);
        
        $base_url                   = base_url().'index.php/pelanggan/daftar';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 10;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        $data['data']               = $this->pelanggan_model->get('',$per_page,$offset,$data['txtcari']);
        $data['halaman']            = 'pelanggan/index';
        $data['judulweb']           = ' | Pelanggan';
        $this->load->view('layout/index',$data);   
    }
    function pelanggan_form($trx)
    {
        $data                       = $this->app_model->general();
        switch($trx)
        {
            case 'tambah' :
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'pelanggan/tambah';
                $this->load->view('layout/index',$data);
                break;
            case 'edit' :
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'pelanggan/edit';
                $data['data']                   = $this->pelanggan_model->get($this->uri->segment(4),'','');
                $this->load->view('layout/index',$data);
                break;
            case 'hapus' :
                $this->pelanggan_exec($trx,$this->uri->segment(4));
                break;
        }
    }
    function pelanggan_exec($trx)
    {
        $data['pelanggan_member']           = $this->input->post('pelanggan_member');
        $data['pelanggan_nm_lengkap']       = $this->input->post('pelanggan_nm_lengkap');
        $data['pelanggan_alamat']           = $this->input->post('pelanggan_alamat');
        $data['pelanggan_kelurahan']        = $this->input->post('pelanggan_kelurahan');
        $data['pelanggan_kecamatan']        = $this->input->post('pelanggan_kecamatan');
        $data['pelanggan_kota']             = $this->input->post('pelanggan_kota');
        $data['pelanggan_provinsi']         = $this->input->post('pelanggan_provinsi');
        $data['pelanggan_kodepos']          = $this->input->post('pelanggan_kodepos');
        $data['pelanggan_telprumah']        = $this->input->post('pelanggan_telprumah');
        $data['pelanggan_handphone']        = $this->input->post('pelanggan_handphone');
        $data['pelanggan_faximile']         = $this->input->post('pelanggan_faximile');
        $data['pelanggan_email']            = $this->input->post('pelanggan_email');
        $data['pelanggan_keterangan']       = $this->input->post('pelanggan_keterangan');
        $data['pelanggan_tgl_gabung']       = $this->input->post('pelanggan_tgl_gabung');
        switch($trx)
        {
            case 'tambah' :
                $data['uid']                = $this->session->userdata('user_kd');
                $data['doe']                = date('Y-m-d h:i:s');
                if($this->pelanggan_model->simpan($data))
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
                if($this->pelanggan_model->update($this->input->post('pelanggan_kd'),$data))
                {
                    echo '<script type="text/javascript">alert("Berhasil diupdate!");parent.iclose();</script>';
                }
                else
                {
                    echo '<script type="text/javascript">alert("Gagal diupdate");</script>';
                }
                break;
            case 'hapus' :
                $this->pelanggan_model->hapus($this->uri->segment(4));
                redirect('pelanggan/daftar');
                break;
        }
    }
    function cek_pelanggan($idpelanggan)
    {
        $pelanggan              = $this->pelanggan_model->get($idpelanggan,'','');
        //echo $th
        if($pelanggan->num_rows()>0)
        {
            echo $pelanggan->num_rows().'#'.$pelanggan->row()->pelanggan_nm_lengkap.'#'.$pelanggan->row()->pelanggan_kategori.'#'.$pelanggan->row()->pelanggan_diskon_persen.'#'.$pelanggan->row()->pelanggan_diskon_nilai;
        }
        else
        {
            echo $pelanggan->num_rows().'#';
        }
    }
}
