<?php

class Outlet extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('outlet_model');
        $this->load->library('curl');  
    }
    function index()
    {     
        redirect('outlet/daftar');
    }
    function daftar()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['data']               = $this->outlet_model->outlet_ambil('','','');
        $data['halaman']            = 'outlet/index';
        $data['judulweb']           = ' | Outlet';
        $this->load->view('layout/index',$data);   
    }
    function sinkronisasi()
    {
        $data                       = $this->app_model->general();
        $data['url']                = $this->app_model->system('service_url').'api/service_outlet/outlet/format/json';
        $data['data']               = $this->curl->simple_get($data['url']);
        $data['data_array']         = json_decode($data['data'],true);
        
        /* Simpan di File  */
        $namafile                   = trim($this->session->userdata('outlet_kd')).'@outlet'.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        if(file_exists($ourFileName))
        {
            unlink($ourFileName);
        }
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$data['data']);
        fclose($ourFileHandle);
        
        $ourFileName                = $data['base_upload'].'/'.$this->session->userdata('outlet_kd').'@outlet.txt';
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        
        $data['data_array']         = json_decode($data['data'],true);
        
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        $this->pelanggan_model->hapus('');
        for($i=0;$i<count($data['data_array']);$i++)
        {
            //$datainput['pelanggan_kd']              = $data['data_array'][$i]['kd_ps'];
            $datainput['pelanggan_member']          = $data['data_array'][$i]['id_ps'];
            $datainput['pelanggan_nm_lengkap']      = $data['data_array'][$i]['nm_ps'];
            $datainput['pelanggan_kategori']        = $data['data_array'][$i]['nm_group_ps'];
            $datainput['pelanggan_diskon_persen']   = $data['data_array'][$i]['diskon_persen'];
            $datainput['pelanggan_diskon_nilai']    = $data['data_array'][$i]['diskon_nilai'];
            $datainput['pelanggan_alamat']          = $data['data_array'][$i]['alamat'];
            $datainput['pelanggan_kota']            = $data['data_array'][$i]['kota'];
            $datainput['pelanggan_provinsi']        = $data['data_array'][$i]['propinsi'];
            $datainput['pelanggan_telprumah']       = $data['data_array'][$i]['telp'];
            $datainput['pelanggan_handphone']       = $data['data_array'][$i]['hp'];
            $datainput['pelanggan_faximile']        = $data['data_array'][$i]['fax'];
            $datainput['pelanggan_email']           = $data['data_array'][$i]['email'];
            if($this->pelanggan_model->simpan($datainput))
            {
                $data['error'] .= '';
            }
            else
            {
                $data['error'] .= 'Error';
            }
        }
        if ($this->db->trans_status() === FALSE) //CEK JIKA GAGAL
        {
            $this->db->trans_rollback(); //ROLLBACK
        }
        else
        {
            $this->db->trans_commit(); //DILAKUKAN
        }
        if($data['error']=='')
        {
            echo '<script type="text/javascript">alert("Sukses Simpan Data Pelanggan!");parent.iclose();</script>';
        }
        else
        {
            echo '<script type="text/javascript">alert("Gagal Simpan Data Pelanggan!");history.go(-1);</script>';
        }
        
    }
}

?>