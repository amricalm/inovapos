<?php

class Karyawan extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('karyawan_model');
    }
    function index()
    {     
        redirect('karyawan/daftar');
    }
    function daftar($offset='0')
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcarikaryawan');
        $this->session->set_userdata(array('txtcarikaryawan'=>$txtcari));
        $data['txtcari']            = $this->session->userdata('txtcarikaryawan');
        
        $data['data_all']           = $this->karyawan_model->get('','','',$data['txtcari']);
        
        $base_url                   = base_url().'index.php/karyawan/daftar';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 10;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        
        $data['data']               = $this->karyawan_model->get('',$per_page,$offset,$data['txtcari']);
        $data['halaman']            = 'karyawan/index';
        $data['judulweb']           = ' | Karyawan';
        $this->load->view('layout/index',$data);   
    }
    function karyawan_form($trx)
    {
        $data                       = $this->app_model->general();
        switch($trx)
        {
            case 'tambah' :
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'karyawan/tambah';
                $this->load->view('layout/index',$data);
                break;
            case 'edit' :
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'karyawan/edit';
                $data['data']                   = $this->karyawan_model->get($this->uri->segment(4),'','','');
                $this->load->view('layout/index',$data);
                break;
            case 'hapus' :
                $this->karyawan_exec($trx,$this->uri->segment(4));
                break;
        }
    }
    function karyawan_exec($trx)
    {
        $data['karyawan_nm_lengkap']       = $this->input->post('karyawan_nm_lengkap');
        $data['karyawan_tp_lahir']          = $this->input->post('karyawan_tp_lahir');
        $data['karyawan_tgl_lahir']         = $this->input->post('karyawan_tgl_lahir');
        $data['karyawan_alamat']           = $this->input->post('karyawan_alamat');
        $data['karyawan_kelurahan']        = $this->input->post('karyawan_kelurahan');
        $data['karyawan_kecamatan']        = $this->input->post('karyawan_kecamatan');
        $data['karyawan_kota']             = $this->input->post('karyawan_kota');
        //$data['karyawan_provinsi']         = $this->input->post('karyawan_provinsi');
        $data['karyawan_kodepos']           = $this->input->post('karyawan_kodepos');
        $data['karyawan_status']           = $this->input->post('karyawan_status');
        $data['karyawan_tk_pdd']           = $this->input->post('karyawan_tk_pdd');
        $data['karyawan_ijazah']           = $this->input->post('karyawan_ijazah');
        $data['karyawan_telp']              = $this->input->post('karyawan_telp');
        $data['karyawan_hp']                = $this->input->post('karyawan_hp');
        $data['karyawan_email']            = $this->input->post('karyawan_email');
        $data['karyawan_tgl_masuk']       = $this->input->post('karyawan_tgl_masuk');
        switch($trx)
        {
            case 'tambah' :
                $data['uid']                = $this->session->userdata('user_kd');
                $data['doe']                = date('Y-m-d h:i:s');
                if($this->karyawan_model->simpan($data))
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
                if($this->karyawan_model->update($this->input->post('karyawan_kd'),$data))
                {
                    echo '<script type="text/javascript">alert("Berhasil diupdate!");parent.iclose();</script>';
                }
                else
                {   
                    echo '<script type="text/javascript">alert("Gagal diupdate");</script>';
                }
                break;
            case 'hapus' :
                $this->karyawan_model->hapus($this->uri->segment(4));
                redirect('karyawan/daftar');
                break;
        }
    }
    function absen()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['halaman']            = 'karyawan/absen';
        $data['judulweb']           = ' | Absen';
        $this->load->view('layout/index',$data);   
    }
    function absen_simpan()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        
        $data['nik']                    = $this->input->post('nik');
        $data['pwd']                    = $this->input->post('pwd');
        $data['tgl']                    = date('Y-m-d');
        $data['shift']                  = $this->session->userdata('shift');
        $data['jam_masuk']              = date('Y-m-d h:i:s');
        $data['jam_keluar']             = date('Y-m-d h:i:s');
        
        $this->load->model('absen_model');
        $this->load->model('karyawan_model');
        
        if($this->karyawan_model->is_valid($data['nik'],$data['pwd']))
        {
            $qry = $this->absen_model->get_absen($data['tgl'],$data['shift'],$data['nik']);
            if($qry->num_rows() == 0)
//            if ($this->absen_model->is_ada_jam_masuk($data['tgl'],$data['shift'],$data['nik']))
            {
                if($this->absen_model->simpan($data))
                {
                    echo 'Absen Masuk Berhasil Disimpan';
                }
                else
                {
                    echo 'Absen Masuk Gagal Disimpan';
                }
            }
            else
            {
                if($qry->row()->jam_keluar =="NULL")
                {
                    if($this->absen_model->update_jam_keluar($data['tgl'],$data['shift'],$data['nik'],$data['jam_keluar']))
                    {
                        echo 'Absen Keluar Berhasil Disimpan';
                    }
                    else
                    {
                        echo 'Absen Keluar Gagal Disimpan';
                    }
                }
                else
                {
                    echo 'Absen Masuk dan Keluar Sudah Ada!';
                }
            }
                
        }
        else
        {
            echo "NIK Tidak Terdaftar atau Password Salah!";
        }
        
        
        
//        switch($trx)
//        {
//            case 'tambah' :
//                $data['uid']                = $this->session->userdata('user_kd');
//                $data['doe']                = date('Y-m-d h:i:s');
//                if($this->karyawan_model->simpan($data))
//                {
//                    echo '<script type="text/javascript">alert("Berhasil disimpan!");parent.iclose();</script>';
//                }
//                else
//                {
//                    echo '<script type="text/javascript">alert("Gagal disimpan");</script>';
//                }
//                break;
//            case 'edit' :
//                $data['uid_edit']           = $this->session->userdata('user_kd');
//                $data['doe_edit']           = date('Y-m-d h:i:s');
//                if($this->karyawan_model->update($this->input->post('karyawan_kd'),$data))
//                {
//                    echo '<script type="text/javascript">alert("Berhasil diupdate!");parent.iclose();</script>';
//                }
//                else
//                {   
//                    echo '<script type="text/javascript">alert("Gagal diupdate");</script>';
//                }
//                break;
//            case 'hapus' :
//                $this->karyawan_model->hapus($this->uri->segment(4));
//                redirect('karyawan/daftar');
//                break;
//        }
    }
}

?>    