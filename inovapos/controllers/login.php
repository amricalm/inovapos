<?php

class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('group_model');
        $this->load->model('user_model');  
        $this->load->model('outlet_model');
        $this->load->model('log_proses_model');
        $this->load->model('maintenance_model');
        $this->load->model('sys_var_model');
        $this->load->model('barang_mutasi_model');
    }
    function index()
    {
        $data           = $this->app_model->general();
        if($this->session->userdata('user_nm')!='')
        {
            redirect('home');
        }
        $this->load->view('layout/login',$data);
    }
    function submit()
    {
        $datas                                              = array();
        $data['user_nm']                                    = $this->input->post('username');
        $data['user_pwd']                                   = $this->input->post('password');
        $hasil                                              = $this->user_model->user_ambil($data['user_nm']);
        if($hasil->num_rows() > 0)
        {
            if($hasil->row()->user_password==$data['user_pwd'])
            {
                $array['user_kd']                           = $hasil->row()->user_kd;
                $array['user_nm']                           = $this->input->post('username');
                $array['user_group_kd']                     = $hasil->row()->user_group;
                $array['user_group']                        = $this->group_model->group_ambil($hasil->row()->user_group)->row()->group_nm;
                $array['outlet_kd']                         = $hasil->row()->user_outlet;
                $array['outlet_nm']                         = $this->outlet_model->outlet_ambil($hasil->row()->user_outlet)->row()->outlet_nm; 
                $tglshift                                   = $this->log_proses_model->gettutupshift();
                $shift                                      = $this->log_proses_model->getmaxshifttanggal($tglshift->row()->tgl);
                $nextpage                                   = '';
                if($tglshift->row()->tgl != '')
                {
                    if($shift->row()->log_val == 'OPEN' && $tglshift->row()->tgl==date('Y-m-d'))
                    {
                        $array['tanggal']                   = $tglshift->row()->tgl;
                        $array['shift']                     = $shift->row()->shift;
                        $nextpage                           = 'home';
                        $this->session->set_userdata($array);
                        
                    }
                    elseif($shift->row()->log_val == 'OPEN' && $tglshift->row()->tgl!=date('Y-m-d'))
                    {
                        $array['tanggal']                   = $tglshift->row()->tgl;
                        $array['shift']                     = $shift->row()->shift;
                        $nextpage                           = 'home'; //harusnya menuju halaman yang hanya ada 1 menu yaitu : Tutup Shift
                        $this->session->set_userdata($array);
                    }
                    elseif($shift->row()->log_val == 'CLOSE')
                    {
//                        $laporan                            = $this->log_proses_model->getlaporan($tglshift->row()->tgl,$shift->row()->shift);
//                        if($laporan->num_rows() > 0)
//                        {
                            $etglshift                      = /*explode('-',$laporan->row()->tgl)*/ explode('-',$tglshift->row()->tgl);
                            $tglselanjutnya                 = date('Y-m-d',mktime(0,0,0,$etglshift[1],$etglshift[2]+1,$etglshift[0]));
                            $datas['log_col']                = "SHIFT";
                            $datas['log_val']                = "OPEN";
                            $datas['tipe']                   = "SHIFT";
                            $datas['tgl']                    = $tglselanjutnya;
                            $datas['shift']                  = '1';
                            $datas['uid']                    = $array['user_kd'];
                            $this->log_proses_model->simpanLogJual($datas);
                            $tglshift                       = $this->log_proses_model->gettutupshift(); 
                            $shift                          = $this->log_proses_model->getmaxshifttanggal($tglshift->row()->tgl);
                            $array['tanggal']               = $tglshift->row()->tgl;
                            $array['shift']                 = $shift->row()->shift;
                            $nextpage                       = 'home';
                            $this->session->set_userdata($array);
//                        }
//                        else
//                        {
//                            $array['tanggal']               = $tglshift->row()->tgl;
//                            $array['shift']                 = $shift->row()->shift;
//                            $nextpage                       = 'home'; //harusnya menuju halaman yang hanya ada 1 menu yaitu : Tutup Shift
//                            $this->session->set_userdata($array);
//                        }
                    }
                }
                else
                {
                    $tglshiftsys        = $this->sys_var_model->get('shift_awal');
                    $etglshiftsys       = explode('#',$tglshiftsys);
                    $array['tanggal']   = $etglshiftsys[0];
                    $array['shift']     = $etglshiftsys[1];
                    $nextpage           = 'home';
                    $this->session->set_userdata($array);
                    $datas['log_col']                = "SHIFT";
                    $datas['log_val']                = "OPEN";
                    $datas['tipe']                   = "SHIFT";
                    $datas['tgl']                    = $etglshiftsys[0];
                    $datas['shift']                  = $etglshiftsys[1];
                    $datas['uid']                    = $array['user_kd'];
                    $this->log_proses_model->simpanLogJual($datas);
                }
                if($this->maintenance_model->cek_maintenance()->num_rows() == 0)
                {
                    $this->maintenance();
                    $datam['log_col']               = 'MAINTENANCE';
                    $datam['log_val']               = 'SUKSES';
                    $datam['tgl']                   = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $datam['shift']                 = $this->session->userdata('shift');
                    $datam['tipe']                  = 'MAINTENANCE';
                    $datam['uid']                   = $this->session->userdata('user_kd');
                    $datam['tgl_tambah']            = $this->session->userdata('tanggal').' '.date('H:i:s');   
                    $this->log_proses_model->simpanLogJual($datam);
                }
                redirect($nextpage);
            }
            else
            {
                echo '<script type="text/javascript">alert("Username atau Password salah!");history.go(-1);</script>';
            }
        }
        else
        {
            echo '<script type="text/javascript">alert("Username atau Password salah!");history.go(-1);</script>';
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('');
    }
    
    function maintenance()
    {
        /*
         |
         | Cek ENGINE tabel, jika engine-nya bukan InnoDB, diubah ke InnoDB. 
         |
         */
        $tabel                      = array(
                                        'ac_tjual',
                                        'ac_tjual_dtl',
                                        'ac_tjual_dtl_imei',
                                        'im_mbarang',
                                        'im_tpindah_barang',
                                        'im_tpindah_barang_dtl',
                                        'im_tpindah_barang_dtl_imei',
                                        'im_mpelanggan',
                                        'im_tsaldo_barang',
                                        'im_tsaldo_barang_dtl',
                                        'im_tsaldo_barang_dtl_imei',
                                        'im_moutlet',
                                        'im_tpromosi'
                                        );
        for($i=0;$i<count($tabel);$i++)
        {
            $cek                    = $this->maintenance_model->cek_engine($tabel[$i]);
            if($cek->row()->ENGINE != 'InnoDB')
            {
                $this->maintenance_model->set_engine($tabel[$i],'InnoDB');
            }
        }
    }
}
