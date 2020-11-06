<?php

class User extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('group_user_model');
    }
    function index()
    {
        redirect('user/daftar');
    }
    function daftar($offset=0)
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('grup') : $this->session->userdata('cbogrupuser');
        $this->session->set_userdata(array('cbogrupuser'=>$grup));
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupuser');
        $wheregrup                  = ($this->session->userdata('cbogrupuser')!='')?"user_group='".$data['cbogrup']."'":'';
        $data['data_all']           = $this->user_model->get('','','',$data['txtcari'],$wheregrup);
        //echo $this->db->last_query();
        $base_url                   = base_url().'index.php/barang/daftar';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 10;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        $data['data']               = $this->user_model->get('',$per_page,$offset,$data['txtcari'],$wheregrup);
        $data['data_group']         = $this->group_user_model->get('');
        $data['halaman']            = 'user/index';
        $data['judulweb']           = ' | Daftar User';
        $this->load->view('layout/index',$data);   
    }
    function user_form($trx)
    {
        $data                       = $this->app_model->general();
        $data['group']              = $this->group_user_model->get('');
        switch($trx)
        {
            case 'tambah' :
                if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=="Kasir" || $this->session->userdata('user_group')=='SPV')
                {
                    redirect('');
                }
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'user/tambah';
                $this->load->view('layout/index',$data);
                break;
            case 'edit' :
                if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=="Kasir" || $this->session->userdata('user_group')=='SPV')
                {
                    redirect('');
                }
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'user/edit';
                $data['data']                   = $this->user_model->get($this->uri->segment(4),'','','','');
                //echo $this->db->last_query();
                $this->load->view('layout/index',$data);
                break;
            case 'hapus' :
                $this->user_exec('hapus',$this->uri->segment(4));
                break;
        }
    }
    function user_exec($trx)
    {
        //rint_r($this->input->post());die();
        switch($trx)
        {
            case 'tambah' :
                $input['user_kd']               = $this->input->post('user_kd');
                $input['user_nm']               = $this->input->post('user_nm');
                $input['user_group']            = $this->input->post('user_group');
                $input['user_password']         = $this->input->post('user_password');
                $input['user_outlet']           = $this->session->userdata('outlet_kd'); 
                $cek                            = $this->user_model->get($input['user_kd'],'','','','');
                if($cek->num_rows()>0)
                {
                    echo '<script type="text/javascript">alert("User sudah ada, silahkan hubungi admin IT.");window.parent.iclose();</script>';
                }  
                else
                {
                    if($this->user_model->user_simpan($input))
                    {
                        echo '<script type="text/javascript">alert("Berhasil disimpan");window.parent.iclose();</script>';
                    }
                }           
                break;
            case 'update' :
                $user_kd                        = $this->input->post('user_kd');
                $input['user_nm']               = $this->input->post('user_nm');
                $input['user_group']            = $this->input->post('user_group');
                $input['user_password']         = $this->input->post('user_password');
                $input['user_outlet']           = $this->session->userdata('outlet_kd'); 
                $cek                            = $this->user_model->get($user_kd,'','','','');
                if($cek->num_rows()>0)
                {
                    if($this->user_model->user_update($user_kd,$input))
                    {
                        echo '<script type="text/javascript">alert("Berhasil disimpan");window.parent.iclose();</script>';
                    }
                }  
                else
                {
                    echo '<script type="text/javascript">alert("User sudah ada, silahkan hubungi admin IT.");window.parent.iclose();</script>';
                }           
                break;
            case 'hapus' :
                if($this->user_model->user_hapus($this->uri->segment(4)))
                {
                    echo '<script type="text/javascript">alert("Berhasil Dihapus");window.location="'.base_url().'index.php/user";</script>';
                }
                break;
        }
    }
}