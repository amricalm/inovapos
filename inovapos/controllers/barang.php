<?php

class Barang extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('barang_model');
        $this->load->model('barang_imei_model');
        $this->load->model('barang_saldo_model');
        $this->load->model('barang_mutasi_model');
        $this->load->model('group_barang_model');
        $this->load->model('satuan_model');
        $this->load->model('outlet_model');
        $this->load->model('log_proses_model');
        $this->load->model('kasir_model');
        $this->load->model('barang_saldo_penyesuaian_model');
        $this->load->model('barang_saldo_penyesuaian_negatif_model');
    }
    function index()
    {     
        redirect('barang/daftar');
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
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('grup') : $this->session->userdata('cbogrupbarang');
        $this->session->set_userdata(array('cbogrupbarang'=>$grup));
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupbarang');
        
        $data['data_all']           = $this->barang_model->get('','','',$data['txtcari'],$data['cbogrup']);
        
        $base_url                   = base_url().'index.php/barang/daftar';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 50;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        $data['data']               = $this->barang_model->get('',$per_page,$offset,$data['txtcari'],$data['cbogrup']);
        //echo $this->db->last_query();
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'barang/index';
        $data['judulweb']           = ' | Daftar Barang';
        $this->load->view('layout/index',$data);   
    }
    function daftar_elektrik($offset=0)
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
        
        $data['data_all']           = $this->barang_model->get_elektrik('','','',$data['txtcari']);
        
        $base_url                   = base_url().'index.php/barang/daftar';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 50;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        $data['data']               = $this->barang_model->get_elektrik('',$per_page,$offset,$data['txtcari']);
        //echo $this->db->last_query();
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'barang/index_elektrik';
        $data['judulweb']           = ' | Daftar Barang';
        $this->load->view('layout/index',$data);   
    }
    function list_barang($elemen=0,$offset=0)
    {
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'list_barang';
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('grup') : $this->session->userdata('cbogrupbarang');
        $this->session->set_userdata(array('cbogrupbarang'=>$grup));
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupbarang');

        $data['data_all']           = $this->barang_model->get('','','',$data['txtcari'],$data['cbogrup']);
        $base_url                   = base_url().'index.php/barang/list_barang/'.$elemen;
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 30;
        $uri_segment                = 4;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 


        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['data']               = $this->barang_model->get('',$per_page,$offset,$data['txtcari'],$data['cbogrup']);
        $this->load->view('layout/index',$data);
    }

    function list_barang_temp($elemen=0,$offset=0)
    {
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'list_barang_temp';
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('grup') : $this->session->userdata('cbogrupbarang');
        $this->session->set_userdata(array('cbogrupbarang'=>$grup));
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupbarang');

        $data['data_all']           = $this->barang_model->get('','','',$data['txtcari'],$data['cbogrup']);
        $base_url                   = base_url().'index.php/barang/list_barang_temp/'.$elemen;
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 30;
        $uri_segment                = 4;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 


        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['data']               = $this->barang_model->get('',$per_page,$offset,$data['txtcari'],$data['cbogrup']);

        $this->load->view('layout/index',$data);
    }
    function list_barang_elektrik($elemen=0,$offset=0)
    {
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'list_barang_elektrik';
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $data['txtcari']            = $this->session->userdata('txtcaribarang');

        $data['data_all']           = $this->barang_model->get_elektrik('','','',$data['txtcari']);
        //echo $this->db->last_query();
        $base_url                   = base_url().'index.php/barang/list_barang_elektrik/'.$elemen;
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 30;
        $uri_segment                = 4;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        
        $data['data']               = $this->barang_model->get_elektrik('',$per_page,$offset,$data['txtcari']);
        //echo $this->db->last_query();
        
        $this->load->view('layout/index',$data);
    }
    function list_imei($kdbarang)
    {
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'list_imei';
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $kd_gudang                  = $this->session->userdata('outlet_kd');
        $tgl                        = $this->session->userdata('tanggal');
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        //$data['data']               = $this->barang_saldo_model->get_imei_saldo($kdbarang,$kd_gudang,$tgl);
        $data['data']               = $this->barang_imei_model->get($kdbarang,'',1);
        //echo $this->db->last_query();
        $this->load->view('layout/index',$data);
    }
    function list_imei_temp($kdbarang)
    {
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'list_imei_temp';
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $kd_gudang                  = $this->session->userdata('outlet_kd');
        $tgl                        = $this->session->userdata('tanggal');
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        //$data['data']               = $this->barang_saldo_model->get_imei_saldo($kdbarang,$kd_gudang,$tgl);
        $data['data']               = $this->barang_imei_model->get($kdbarang,'',1);

        $this->load->view('layout/index',$data);
    }
    function list_imei_doang($kdbarang)
    {
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'list_imei_doang';
        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
        $kd_gudang                  = $this->session->userdata('outlet_kd');
        $tgl                        = $this->session->userdata('tanggal');
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        //$data['data']               = $this->barang_saldo_model->get_imei_saldo($kdbarang,$kd_gudang,$tgl);
        $data['data']               = $this->barang_imei_model->get($kdbarang,'',1);
        //echo $this->db->last_query();
        $this->load->view('layout/index',$data);
    }
    function list_imei_edit($tgl,$kdbarang)
    {
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'list_imei_edit';
//        $txtcari                    = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('txtcaribarang');
//        $this->session->set_userdata(array('txtcaribarang'=>$txtcari));
//        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        //$data['data']               = $this->barang_saldo_model->get_imei_saldo($kdbarang,$this->session->userdata('outlet_kd'),$tgl);
        $data['data']               = $this->barang_imei_model->get($kdbarang,'',1);
        //echo $this->db->last_query();exit();
        
        $this->load->view('layout/index',$data);
    }
    function list_imei_penyesuaian($tgl,$kdbarang)
    {
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'list_imei_penyesuaian';
        //$data['data']               = $this->barang_saldo_model->get_imei_saldo($kdbarang,$this->session->userdata('outlet_kd'),$tgl);
        $data['data']               = $this->barang_imei_model->get($kdbarang,'',1);
        $this->load->view('layout/index',$data);
    }
    function simpan()
    {
        $data['barcode']                            = $this->input->post('barcode');
        $data['nm_barang']                          = $this->input->post('nm_barang');
        $data['kd_satuan']                          = $this->input->post('kd_satuan');
        $data['kd_group']                           = $this->input->post('kd_group');
        $data['harga_jual']                         = $this->input->post('harga_jual');
        $data['hpp']                                = $this->input->post('hpp');
        
        $insert_id                                  = $this->barang_model->simpan_id($data);
        $result                                     = $this->barang_model->get($insert_id,'','','');
        
        $rows                       = array();
        foreach($record->result_array() as $row )
        {
            $rows[]                         = $row;
        }
        
        $jtableResult                       = array();
        $jTableResult['TotalRecordCount']   = $recordCount;
        $jtableResult['Result']             = 'OK';
        $jtableResult['Records']            = $rows;
    }
    function lihat_imei($kd)
    {
        //$imei                               = $this->barang_saldo_model->getBarangByImei($kd);
        $imei                               = $this->barang_imei_model->get('',$kd,1);
        //echo $this->db->last_query();
        //$imei                               = $this->barang_saldo_model->get_imei($this->session->userdata('outlet_kd'),$this->session->userdata('tanggal'),$kd);

        if($imei->num_rows()>0)
        {
            //echo 'S#'.$imei->row()->imei.'#'.$imei->row()->barang_kd;
            echo 'S#'.$imei->row()->imei_no.'#'.$imei->row()->imei_barang;
        }
        else
        {
            echo 'E#';
        }
    }
    function lihat_barang($kd)
    {
        $this->load->model('barang_saldo_model', 'saldo');
        $barang =  $this->barang_model->get($kd,'','','');
        //echo $this->db->last_query();
        if($barang->num_rows()>0)
        { 
            $saldo  = $this->saldo->saldo_hari_ini($kd);
            if($saldo[0]['saldo_qty']!='0')
            {
                echo 'S#'.$barang->row()->barang_kd.'#'.$barang->row()->barang_nm.'#' . ($saldo[0]['saldo_qty']) .'#'.$barang->row()->barang_harga_jual.'#'.$barang->row()->barang_group.'#'.$barang->row()->group_hp.'#'.$barang->row()->group_elektrik;
            }
            else
            {
                echo 'E#';
            }
        }
        else
        {
            echo 'E#';
        }
    }
    function lihat_barang_elektrik($kd)
    {
        $this->load->model('barang_saldo_model', 'saldo');
        $barang =  $this->barang_model->get_elektrik($kd,'','','');
        if($barang->num_rows()>0)
        { 
            $saldo  = $this->saldo->get_saldo_elektrik();
            if($saldo['saldo_qty']!='0' && $saldo['saldo_qty'] > $barang->row()->barang_harga_pokok)
            {
                echo 'S#'.$barang->row()->barang_kd.'#'.$barang->row()->barang_nm.'#' . $barang->row()->barang_harga_pokok .'#'.$barang->row()->barang_harga_jual.'#'.$barang->row()->barang_group.'#'.$barang->row()->group_hp.'#'.$barang->row()->group_elektrik;
            }
            else
            {
                echo 'E#';
            }
            //echo 'S#'.$barang->row()->barang_kd.'#'.$barang->row()->barang_nm.'#' . $barang->row()->barang_harga_pokok .'#'.$barang->row()->barang_harga_jual.'#'.$barang->row()->barang_group.'#'.$barang->row()->group_hp.'#'.$barang->row()->group_elektrik;
        }
        else
        {
            echo 'E#';
        }
    }
    function lihat_grup($kd)
    {
        $barang =  $this->barang_model->get($kd,'','','');
        if($barang->num_rows()>0)
        { 
            echo 'S#'.$barang->row()->barang_group;
        }
        else
        {
            echo 'E#';
        }
    }
    function stok_opname($tgl=0, $shift=0,$kdgroup=0,$offset=0)
    {
        if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=="Kasir" || $this->session->userdata('user_group')=="Leader" || $this->session->userdata('user_group')=='SPV')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
//        if($data['tutup_stok']==1)
//        {
//            if($this->session->userdata('user_group')=="Kasir")
//            {
//                redirect('');
//            }
//        }
        $data['tgl']                = $tgl;
        $data['shift']              = $shift;
        $data['kdgroup']            = $kdgroup;
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('searchso');
        $this->session->set_userdata(array('searchso'=>$grup));
        $data['filter']		       = $this->session->userdata('searchso');
        $data['data_all']           = $this->barang_saldo_model->get2($data['tgl'],$data['shift'],$data['kdgroup'],$data['filter'],'','');
        $base_url                   = base_url().'index.php/barang/stok_opname';
        $base_url                   .= ($tgl!='') ? '/'.$tgl.'/'.$shift : '/0';
        $base_url                   .= ($kdgroup!='') ? '/'.$kdgroup : '/0';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 10;
        $uri_segment                = 6;
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        $data['data']               = $this->barang_saldo_model->get2($data['tgl'],$data['shift'],$data['kdgroup'],$data['filter'] , $per_page,$offset);
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'barang/stok_opname';
        $data['judulweb']           = ' | Stok Opname';
        $this->load->view('layout/index',$data); 
    }
    function stok_penyesuaian($tgl=0,$shift=0,$kdgroup=0,$offset=0)
    {
        if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=="Kasir" || $this->session->userdata('user_group')=='Leader')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
//        if($data['tutup_stok']==1)
//        {
//            if($this->session->userdata('user_group')=="Kasir")
//            {
//                redirect('');
//            }
//        }
        if($tgl==0){$tgl = $this->session->userdata('tanggal');}
        $etgl                       = explode('-',$tgl);
        $data['no_faktur']          = 'SO'.substr($etgl[0],3,2).$etgl[1].$etgl[2].$shift;
        $data['kdgroup']            = $kdgroup;
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('searchso');
        $this->session->set_userdata(array('searchso'=>$grup));
        $data['filter']             = $this->session->userdata('searchso');
        $data['data_all']           = $this->barang_saldo_penyesuaian_model->get($data['no_faktur'],'',$data['kdgroup'],$this->session->userdata('searchso'),0,0);
        //cho $this->db->last_query();
        $base_url                   = base_url().'index.php/barang/stok_penyesuaian';
        $base_url                   .= ($tgl!='') ? '/'.$tgl : '/0';
        $base_url                   .= ($shift!='') ? '/'.$shift : '/0';
        $base_url                   .= ($kdgroup!='') ? '/'.$kdgroup : '/0';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 30;
        $uri_segment                = 6;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        
        $data['data']               = $this->barang_saldo_penyesuaian_model->get($data['no_faktur'],'',$data['kdgroup'],$this->session->userdata('searchso'),$per_page,$offset);
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'barang/stok_penyesuaian';
        $data['judulweb']           = ' | Stok Penyesuaian';
        $this->load->view('layout/index',$data); 
    }
    function simpan_stok()
    {
        $jmh_stok   = 0;
        $hp         = 0;
        $error      = '';

        $kd_barang              = $this->input->post('saldo_barang');
        $tgl                    = $this->input->post('saldo_tgl');
        $shift                  = $this->input->post('saldo_shift');
        $jmh_stok               = $this->input->post('saldo_qty');
//        $hp                     = $this->barang_model->cek_grup_hp($kd_barang); 
//        if($hp==1)
//        {
//            $jmh_stok = $this->barang_saldo_model->get_stok_by_imei($kd_barang,$tgl,$shift);
//        }
//        else
//        {
//            $jmh_stok = $this->input->post('saldo_qty');
//        }
        $input                  = array();
        $input['saldo_tgl']     = $tgl;
        $input['saldo_shift']   = $this->input->post('saldo_shift');
        $input['saldo_barang']  = $kd_barang;
        $input['saldo_qty']     = $jmh_stok;
        $input['saldo_gudang']  = $this->session->userdata('outlet_kd');
        $adaatotidak            = $this->barang_saldo_model->cek($input['saldo_barang'],$input['saldo_gudang'],$input['saldo_tgl'],$input['saldo_shift']);
        //echo $adaatotidak->num_rows() ; exit();
        if($adaatotidak->num_rows() == 0)
        {

            if($this->barang_saldo_model->simpan($input))
            {
                //echo 'insert';exit();
            }
            else
            {
                $error          .= 'error';
            }
        }
        else
        {
            $input['saldo_kd']  = $adaatotidak->row()->saldo_kd;
            if($this->barang_saldo_model->update($input))
            {
                //echo 'update';exit();
            }
            else
            {
                $error          .= 'error';
            }
        }

        if($error!='')
        {
            echo 'E#Ada kesalahan pada data!';
        }
        else
        {
            echo 'S#Berhasil disimpan!#' . 'hp'. $hp . '#' .$jmh_stok;
        }
    }
    function simpan_stok_imei()
    {
        $error                      = '';
        $input                      = array();
        $input['saldo_tgl']         = $this->input->post('saldo_tgl');
        $input['saldo_barang']      = $this->input->post('saldo_barang');
        $input['saldo_imei']        = $this->input->post('saldo_imei');
        $input['saldo_gudang']      = $this->session->userdata('outlet_kd');
        
        // Pada Proses ini (Saldo Awal) Tidak Boleh Ada Imei Ganda.
        $this->db->trans_begin();
        if($this->barang_saldo_model->cek_imei_saldo($input['saldo_imei'])==0)
        {
            if($this->barang_saldo_model->simpan_imei($input))
            {
                //Insert/Update Imei untuk Penelusuran Imei (Tracking)
                $cekimei                    = $this->barang_imei_model->get($input['saldo_barang'],$input['saldo_imei'],'');
                if($cekimei->num_rows() > 0)
                {
                    $imeibarang['imei_barang']      = $input['saldo_barang'];
                    $imeibarang['imei_no']          = $input['saldo_imei'];
                    $imeibarang['imei_ref']         = 'Saldo Awal';
                    $imeibarang['imei_status']      = 1;
                    $imeibarang['uid_edit']         = $this->session->userdata('user_kd');
                    $imeibarang['doe_edit']         = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $this->barang_imei_model->update($input['saldo_barang'],$input['saldo_imei'],$imeibarang);
                    
                }
                else
                {
                    $imeibarang['imei_barang']      = $input['saldo_barang'];
                    $imeibarang['imei_no']          = $input['saldo_imei'];
                    $imeibarang['imei_ref']         = 'Saldo Awal';
                    $imeibarang['imei_status']      = 1;
                    $imeibarang['uid']              = $this->session->userdata('user_kd');
                    $imeibarang['doe']              = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $this->barang_imei_model->simpan($imeibarang);
                    
                }
            }
            else
            {
                $error              .= 'error';
            }
            if($error!='')
            {
                echo 'E#Ada kesalahan pada data!';
            }
            else
            {
                echo 'S#Berhasil disimpan!';
            }
        }
        else
        {
            echo 'S#Imei Ganda';
        }
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
        }
        else
        {
            $this->db->trans_commit();
        }
        
    }
    function simpan_stok_penyesuaian()
    {
        $error                      = '';
        $input                      = array();

        $input['tgl']               = $this->session->userdata('tanggal');
        $input['shift']             = $this->session->userdata('shift');
        $input['kd_barang']         = $this->input->post('stok_kdbarang');
        $input['kd_group']          = $this->input->post('stok_kdgrup');
        $input['qty']               = (int)$this->input->post('stok_qty');
        $input['kd_gudang']         = $this->session->userdata('outlet_kd');
        $input['user_kd']           = $this->session->userdata('user_kd');
        $etgl                       = explode('-',$input['tgl']);
        
        $inputhdr['no_faktur']      = 'SO'.$etgl[0].$etgl[1].$etgl[2].$input['shift'];
        $inputhdr['tgl']            = $input['tgl'];
        $inputhdr['kd_gudang']      = $input['kd_gudang'];
        $inputhdr['status']         = 'positif';
        $inputhdr['shift']          = $input['shift'];
        $cekhdr                     = $this->barang_saldo_penyesuaian_model->cek_faktur($inputhdr['no_faktur'],$inputhdr['status']);
        if($cekhdr->num_rows() > 0)
        {        
            //echo $this->db->last_query();
            //print_r($this->input->post());
            //die();
            $inputhdr['uid_edit']   = $input['user_kd'];
            $inputhdr['doe_edit']   = $input['tgl'].' '.date('H:i:s');
            $this->barang_saldo_penyesuaian_model->update_hdr($inputhdr['no_faktur'],$inputhdr);
            $cekdtl                 = $this->barang_saldo_penyesuaian_model->cek_dtl($inputhdr['no_faktur'],$input['kd_barang']);
            if($cekdtl->num_rows() > 0)
            {
                $dtl['no_faktur']           = $inputhdr['no_faktur'];
                $dtl['kd_barang']           = $input['kd_barang'];
                $inputdtl['qty']            = $input['qty'];
                $inputdtl['uid']            = $input['user_kd'];
                $inputdtl['doe']            = $input['tgl'].' '.date('H:i:s');
                if(!$this->barang_saldo_penyesuaian_model->update_dtl($dtl,$inputdtl))
                {
                    $error              .= 'error';
                }
                else
                {
					
                    //echo $this->db->last_query();
                    //die();
                }
            }
            else
            {
                $inputdtl['no_faktur']      = $inputhdr['no_faktur'];
                $inputdtl['kd_barang']      = $input['kd_barang'];
                $inputdtl['qty']            = $input['qty'];
                $inputdtl['uid']            = $input['user_kd'];
                $inputdtl['doe']            = $input['tgl'].' '.date('H:i:s');
                if(!$this->barang_saldo_penyesuaian_model->simpan_dtl($inputdtl))
                {
                    $error              .= 'error';
                }
            }
        }
        else
        {
            $inputhdr['uid']        = $input['user_kd'];
            $inputhdr['doe']        = $input['tgl'].' '.date('H:i:s');
            $this->barang_saldo_penyesuaian_model->simpan_hdr($inputhdr);
            $inputdtl['no_faktur']  = $inputhdr['no_faktur'];
            $inputdtl['kd_barang']  = $input['kd_barang'];
            $inputdtl['qty']        = $input['qty'];
            $inputdtl['uid']        = $input['user_kd'];
            $inputdtl['doe']        = $input['tgl'].' '.date('H:i:s');
            if(!$this->barang_saldo_penyesuaian_model->simpan_dtl($inputdtl))
            {
                $error              .= 'error';
            }
        }
        
        if($error!='')
        {
            echo 'E#Ada kesalahan pada data!';
        }
        else
        {
            echo 'S#Berhasil disimpan!';
        }
    }
    function simpan_stok_penyesuaian_imei()
    {
        $error                      = '';
        $input                      = array();
        $input['tgl']               = $this->session->userdata('tanggal');
        $input['shift']             = $this->session->userdata('shift');
        $input['kd_barang']         = $this->input->post('stok_kdbarang');
        $input['kd_group']          = $this->input->post('stok_kdgroup');
        $input['imei']              = $this->input->post('stok_imei');
        $input['kd_gudang']         = $this->session->userdata('outlet_kd');
        $input['user_kd']           = $this->session->userdata('user_kd');
        if($input['imei']!='')
        {
            if($this->barang_saldo_model->get_imei_saldo($input['kd_barang'],$input['kd_gudang'],$input['tgl'],$input['imei'])->num_rows()==0)
            {
                if($this->barang_saldo_penyesuaian_model->simpan_imei($input,$input['imei']))
                {
                    $error              .= '';
                    $cekimei                    = $this->barang_imei_model->get($input['kd_barang'],$input['imei'],'');
                    if($cekimei->num_rows() > 0)
                    {
                        $imeibarang['imei_barang']      = $input['kd_barang'];
                        $imeibarang['imei_no']          = $input['imei'];
                        $imeibarang['imei_ref']         = '';
                        $imeibarang['imei_status']      = 1;
                        $imeibarang['uid_edit']         = $this->session->userdata('user_kd');
                        $imeibarang['doe_edit']         = $this->session->userdata('tanggal').' '.date('H:i:s');
                        $this->barang_imei_model->update($input['kd_barang'],$input['imei'],$imeibarang);
                    }
                    else
                    {
                        $imeibarang['imei_barang']      = $input['kd_barang'];
                        $imeibarang['imei_no']          = $input['imei'];
                        $imeibarang['imei_ref']         = '';
                        $imeibarang['imei_status']      = 1;
                        $imeibarang['uid']              = $this->session->userdata('user_kd');
                        $imeibarang['doe']              = $this->session->userdata('tanggal').' '.date('H:i:s');
                        $this->barang_imei_model->simpan($imeibarang);
                    }
                }
                else
                {
                    $error              .= 'error';
                }
                
                if($error!='')
                {
                    echo 'E#Ada kesalahan pada data!';
                }
                else
                {
                    
                    echo 'S#Berhasil disimpan!';
                }
            }
            else
            {
                echo 'S#Imei Ganda';
            }
        }
    }
    function hapus_imei()
    {
        $error                      = '';
        $input                      = array();
        $input['saldo_tgl']         = $this->input->post('saldo_tgl');
        $input['saldo_barang']      = $this->input->post('saldo_barang');
        $input['saldo_imei']        = $this->input->post('saldo_imei');
        $input['saldo_gudang']      = $this->session->userdata('outlet_kd');
        if($this->barang_saldo_model->hapus_imei($input))
        {
            $cekimei                    = $this->barang_imei_model->get($input['saldo_barang'],$input['saldo_imei'],'');
            if($cekimei->num_rows() > 0)
            {
                $imeibarang['imei_barang']      = $input['saldo_barang'];
                $imeibarang['imei_no']          = $input['saldo_imei'];
                $imeibarang['imei_ref']         = '';
                $imeibarang['imei_status']      = 0;
                $imeibarang['uid_edit']         = $this->session->userdata('user_kd');
                $imeibarang['doe_edit']         = $this->session->userdata('tanggal').' '.date('H:i:s');
                $this->barang_imei_model->update($input['saldo_barang'],$input['saldo_imei'],$imeibarang);
            }
            else
            {
                $imeibarang['imei_barang']      = $input['saldo_barang'];
                $imeibarang['imei_no']          = $input['saldo_imei'];
                $imeibarang['imei_ref']         = '';
                $imeibarang['imei_status']      = 0;
                $imeibarang['uid']              = $this->session->userdata('user_kd');
                $imeibarang['doe']              = $this->session->userdata('tanggal').' '.date('H:i:s');
                $this->barang_imei_model->simpan($imeibarang);
            }
        }
        else
        {
            $error              .= 'error';
        }
        if($error!='')
        {
            echo 'E#Ada kesalahan pada data!';
        }
        else
        {
            echo 'S#Berhasil dihapus!';
        }
    }
    function hapus_penyesuaian_imei()
    {
        $error                      = '';
        $input                      = array();
        $input['tgl']               = $this->session->userdata('tanggal');
        $input['shift']             = $this->session->userdata('shift');
        $input['kd_barang']         = $this->input->post('stok_kdbarang');
        $input['kd_group']          = $this->input->post('stok_kdgroup');
        $input['imei']              = $this->input->post('stok_imei');
        $input['kd_gudang']         = $this->session->userdata('outlet_kd');
        $input['user_kd']           = $this->session->userdata('user_kd');
        if($input['imei']!='')
        {
            if($this->barang_saldo_penyesuaian_model->hapus_imei($input))
            {
                $error                      .= '';
                $cekimei                    = $this->barang_imei_model->get($input['kd_barang'],$input['imei'],'');
                if($cekimei->num_rows() > 0)
                {
                    $imeibarang['imei_barang']      = $input['kd_barang'];
                    $imeibarang['imei_no']          = $input['imei'];
                    $imeibarang['imei_ref']         = '';
                    $imeibarang['imei_status']      = 0;
                    $imeibarang['uid_edit']         = $this->session->userdata('user_kd');
                    $imeibarang['doe_edit']         = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $this->barang_imei_model->update($input['kd_barang'],$input['imei'],$imeibarang);
                }
                else
                {
                    $imeibarang['imei_barang']      = $input['kd_barang'];
                    $imeibarang['imei_no']          = $input['imei'];
                    $imeibarang['imei_ref']         = '';
                    $imeibarang['imei_status']      = 0;
                    $imeibarang['uid']              = $this->session->userdata('user_kd');
                    $imeibarang['doe']              = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $this->barang_imei_model->simpan($imeibarang);
                }
            }
            else
            {
                $error              .= 'error';
            }
        }
        
        if($error!='')
        {
            echo 'E#Ada kesalahan pada data!';
        }
        else
        {
            echo 'S#Berhasil disimpan!';
        }
    }
    function download_stok_opname($tgl=0,$kdgroup=0,$offset=0)
    {
        $this->load->model('barang_saldo_model');
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        //$tgl_saw                    = $this->barang_saldo_model2->get_tgl_saw();
        //$query                      = $this->barang_saldo_model2->get_saw();
        $query                      = $this->barang_saldo_model->get_imei_saldo('',$this->session->userdata('outlet_kd'),$this->session->userdata('tanggal'));   
        print_r($query->result_array());
        die();
        $hasil                      = "";
        $data['json']               = json_encode($query->result_array());
        $namafile_awal              = 'saldo_awal_'.$tgl_saw;
        $namafile                   = $namafile_awal.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        foreach ($query->result() as $row) 
        {
            $saldoqty               = $this->barang_saldo_model->get_imei_saldo($row->barang_kd,$this->session->userdata('outlet_kd'),$this->session->userdata('tanggal'));
            $saldo                  = $saldoqty->num_rows();   
            fwrite($ourFileHandle,$row->barang_kd . ';' . $row->barang_nm . ';' . $saldo . ';' . $row->imei . "\r\n");
        }
        //fwrite($ourFileHandle,$data['json']);
        fclose($ourFileHandle);
        if(!file_exists($ourFileName))
        {
            die('Error: File not found.');
        }
        else
        {
            //$this->cetak($result_array);
            header("Content-type: application/force-download");
            header('Content-Disposition: inline; filename="' . $ourFileName . '"');
            header("Content-Transfer-Encoding: Binary");
            header("Content-length: ".filesize($ourFileName));
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $namafile . '"');
            readfile("$ourFileName"); 
        }
        
        $sdata['log_col']               = 'close stock opname';
        $sdata['log_val']               = 'sukses';
        $sdata['tgl']                   = $this->session->userdata('tanggal').' '.date('h:i:s');
        $sdata['tipe']                  = 'close stock opname';
        $sdata['uid']                   = $this->session->userdata('user_kd');
        $sdata['tgl_tambah']            = $this->session->userdata('tanggal').' '.date('h:i:s');
        $this->log_proses_model->simpanLogJual($sdata);        
    }

    function download_stok_opname2($tgl=0,$kdgroup=0,$offset=0)
    {
        $this->load->model('barang_saldo_model2');
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                           = $this->app_model->general();
        if($data['tutup_shift'])
        {
            $etglsekarang               = explode('-',$this->session->userdata('tanggal'));
            if($this->session->userdata('shift')=='1')
            {
                $shiftselanjutnya       = '2';
                $tglselanjutnya         = $this->session->userdata('tanggal');
            }
            else
            {
                $shiftselanjutnya       = '1';
                $tglselanjutnya         = date('Y-m-d',mktime(0,0,0,$etglsekarang[1],$etglsekarang[2]+1,$etglsekarang[0],0));
            }
            $query                      = $this->barang_saldo_model->saldo('',$tglselanjutnya,$shiftselanjutnya);
        }
        else 
        {
            $query                      = $this->barang_saldo_model->saldo_hari_ini();
        }
        $hasil                      = "";
        $namafile_awal              = 'saldo_awal_'.$this->session->userdata('outlet_kd').'_'.$this->session->userdata('tanggal').'_'.$this->session->userdata('shift');
        $namafile                   = $namafile_awal.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,'Stok '.$this->session->userdata('outlet_nm')."\r\n");
        fwrite($ourFileHandle,'Tanggal : '.$this->session->userdata('tanggal')."\r\n");
        fwrite($ourFileHandle,'Shift : '.$this->session->userdata('shift')."\r\n");
        for($i=0;$i<count($query);$i++)
        {
            if($query[$i]['saldo_qty']!='0' && $query[$i]['saldo_qty']!='')
            {
                $namabarang         = $this->barang_model->get($query[$i]['saldo_barang'],'','','','','')->row()->barang_nm;
                $dataimei           = $this->barang_imei_model->get($query[$i]['saldo_barang'],'',1);
                if($dataimei->num_rows()>0)
                {
                    foreach($dataimei->result() as $rowimei)
                    {
                        fwrite($ourFileHandle,$query[$i]['saldo_barang'] . ';' . $namabarang . ';' . $query[$i]['saldo_qty'] . ';' . $rowimei->imei_no . "\r\n");   
                    }
                }
                else
                {
                    fwrite($ourFileHandle,$query[$i]['saldo_barang'] . ';' . $namabarang . ';' . $query[$i]['saldo_qty'] . ';'."\r\n");
                }
            }
        }
        fclose($ourFileHandle);
        if(!file_exists($ourFileName))
        {
            die('Error: File not found.');
        }
        else
        {
            //$this->cetak($result_array);
            header("Content-type: application/force-download");
            header('Content-Disposition: inline; filename="' . $ourFileName . '"');
            header("Content-Transfer-Encoding: Binary");
            header("Content-length: ".filesize($ourFileName));
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $namafile . '"');
            readfile("$ourFileName"); 
        }
        
        $sdata['log_col']               = 'close stock opname';
        $sdata['log_val']               = 'sukses';
        $sdata['tgl']                   = $this->session->userdata('tanggal').' '.date('h:i:s');
        $sdata['tipe']                  = 'close stock opname';
        $sdata['uid']                   = $this->session->userdata('user_kd');
        $sdata['tgl_tambah']            = $this->session->userdata('tanggal').' '.date('h:i:s');
        $this->log_proses_model->simpanLogJual($sdata);        
    }

    function download_stok_opname3()
    {
        $this->load->model('barang_saldo_model2');
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $tgl                        = $this->session->userdata('tanggal');
        $shift                      = $this->session->userdata('shift');
        $kd_gudang                  = $this->session->userdata('outlet_kd');
        $query                      = $this->barang_saldo_model->get_saw($tgl,$shift,$kd_gudang);
        $hasil                      = "";
        $namafile_awal              = 'saldo_awal_'.$tgl.'_'.$shift;
        $namafile                   = $namafile_awal.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
 
        for($i=0;$i<count($query);$i++)
        {
            if($query[$i]['saldo_qty']!='0' || $query[$i]['saldo_qty']!='')
            {
                $namabarang         = $this->barang_model->get($query[$i]['saldo_barang'],'','','','','')->row()->barang_nm;
                if($query[$i]['saldo_gudang']=='10' || $query[$i]['saldo_gudang']=='60' || $query[$i]['saldo_gudang']=='70')
                {
                    $dataimei       = $this->barang_imei_model->get($query[$i]['saldo_barang'],'',1);
                    foreach($dataimei->result() as $rowimei)
                    {
                        fwrite($ourFileHandle,$query[$i]['saldo_barang'] . ';' . $namabarang . ';' . $query[$i]['saldo_qty'] . ';' . $rowimei->imei_no . "\r\n");   
                    }
                }
                else
                {
                    fwrite($ourFileHandle,$query[$i]['saldo_barang'] . ';' . $namabarang . ';' . $query[$i]['saldo_qty'] . ';'."\r\n");
                }
            }
        }

        fclose($ourFileHandle);
        if(!file_exists($ourFileName))
        {
            die('Error: File not found.');
        }
        else
        {
            //$this->cetak($result_array);
            header("Content-type: application/force-download");
            header('Content-Disposition: inline; filename="' . $ourFileName . '"');
            header("Content-Transfer-Encoding: Binary");
            header("Content-length: ".filesize($ourFileName));
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . $namafile . '"');
            readfile("$ourFileName"); 
        }
        
        $sdata['log_col']               = 'close stock opname';
        $sdata['log_val']               = 'sukses';
        $sdata['tgl']                   = $this->session->userdata('tanggal').' '.date('h:i:s');
        $sdata['tipe']                  = 'close stock opname';
        $sdata['uid']                   = $this->session->userdata('user_kd');
        $sdata['tgl_tambah']            = $this->session->userdata('tanggal').' '.date('h:i:s');
        $this->log_proses_model->simpanLogJual($sdata);        
    }


    function barang_form($trx)
    {
        $data                       = $this->app_model->general();
        switch($trx)
        {
            case 'tambah' :
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'barang/tambah';
                $this->load->view('layout/index',$data);
                break;
            case 'edit' :
                if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=="Kasir" || $this->session->userdata('user_group')=='SPV')
                {
                    redirect('');
                }
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'barang/edit';
                $data['data']                   = $this->barang_model->get($this->uri->segment(4),'','','');
                $this->load->view('layout/index',$data);
                break;
            case 'hapus' :
                $this->barang_exec($trx,$this->uri->segment(4));
                break;
        }
    }
    function barang_elektrik_form($trx)
    {
        $data                       = $this->app_model->general();
        switch($trx)
        {
            case 'edit' :
                if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=="Kasir" || $this->session->userdata('user_group')=='SPV')
                {
                    redirect('');
                }
                $data['option_tampilan']        = 'tanpa_menu';
                $data['halaman']                = 'barang/edit_elektrik';
                $data['data']                   = $this->barang_model->get_elektrik($this->uri->segment(4),'','','');
                $this->load->view('layout/index',$data);
                break;
        }
    }
    function barang_exec($trx)
    {
        $hargasebelumnya                        = $this->barang_model->get($this->input->post('barang_kd'),'','','','','')->row()->barang_harga_jual;
        $data['barang_barcode']                 = $this->input->post('barcode_barang');
        $data['barang_kd']                      = $this->input->post('kd_barang');
        $data['barang_nm']                      = $this->input->post('nm_barang');
        $data['barang_group']                   = $this->input->post('kd_group');
        $data['barang_satuan']                  = $this->input->post('kd_satuan');
        $data['barang_stok_min']                = $this->input->post('stok_min_barang');
        $data['barang_harga_jual']              = $this->input->post('barang_harga_jual');
        switch($trx)
        {
            case 'tambah' :
                $data['uid']                = $this->session->userdata('user_kd');
                $data['doe']                = date('Y-m-d h:i:s');
                if($this->barang_model->simpan($data))
                {
                    echo '<script type="text/javascript">alert("Berhasil disimpan!");parent.iclose();</script>';
                }
                else
                {
                    echo '<script type="text/javascript">alert("Gagal disimpan");</script>';
                }
                break;
            case 'edit' :
                if($this->barang_model->update($this->input->post('barang_kd'),$data))
                {
                    $log['log_col']             = 'UPDATE HARGA MANUAL';
                    $log['log_val']             = $this->input->post('barang_kd').'#'.$hargasebelumnya.'#'.$data['barang_harga_jual'];
                    $log['tgl']                 = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $log['shift']               = $this->session->userdata('shift');
                    $log['tipe']                = 'UPDATE HARGA MANUAL';
                    $log['uid']                 = $this->session->userdata('user_kd');
                    $log['tgl_tambah']          = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $this->log_proses_model->close($log);          
                    echo '<script type="text/javascript">alert("Berhasil diupdate!");parent.iclose();</script>';
                }
                else
                {
                    echo '<script type="text/javascript">alert("Gagal diupdate");</script>';
                }
                break;
            case 'hapus' :
                    $this->barang_model->hapus($this->uri->segment(4));
                    redirect('barang/daftar');
                    break;
        }
    }
    function barang_elektrik_exec($trx)
    {
        $hargasebelumnya                        = $this->barang_model->get_elektrik($this->input->post('barang_kd'),'','','','','')->row()->barang_harga_jual;
        $data['barang_harga_jual']              = $this->input->post('barang_harga_jual');
        $data['barang_harga_pokok']             = $this->input->post('barang_harga_pokok');
        switch($trx)
        {
            case 'edit' :
                if($this->barang_model->update($this->input->post('barang_kd'),$data))
                {
                    $this->cetak_update_harga($this->input->post('barang_kd'));
                    $log['log_col']             = 'UPDATE HARGA MANUAL';
                    $log['log_val']             = $this->input->post('barang_kd').'#'.$hargasebelumnya.'#'.$data['barang_harga_jual'];
                    $log['tgl']                 = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $log['shift']               = $this->session->userdata('shift');
                    $log['tipe']                = 'UPDATE HARGA MANUAL';
                    $log['uid']                 = $this->session->userdata('user_kd');
                    $log['tgl_tambah']          = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $this->log_proses_model->close($log);          
                    echo '<script type="text/javascript">alert("Berhasil diupdate!");parent.iclose();</script>';
                }
                else
                {
                    echo '<script type="text/javascript">alert("Gagal diupdate");</script>';
                }
                break;
        }
    }
    function cetak_stok($judul='')
    {
        $dataglobal     = $this->app_model->general();
        $cetak          = '';
        $tgl_saw        = $this->sys_var_model->get(PERIODE_SAW_BARANG);
        $data           = $this->barang_model->get('','','','','');
        //$data		    = $this->barang_saldo_model->get_saw($tgl_saw);
        $handle         = printer_open($dataglobal['nama_printer']);
        printer_set_option($handle, PRINTER_MODE, "RAW");
        printer_start_doc($handle, "PrintKasir");
        printer_start_page($handle);

        if ($judul!='')
        {
            $judul = 'Laporan Stock Tutup Shift';
        }
        else
        {
            $judul = 'Laporan Stock';
        }
        $cetak          .= $this->app_model->maksimal(40,$judul,'tengah');
        $cetak          .= $this->app_model->maksimal(40,$this->session->userdata('outlet_nm'),'tengah');
        $cetak          .= $this->app_model->maksimal(40,$this->session->userdata('user_nm'),'tengah');
        $cetak          .= $this->app_model->maksimal(40,$this->session->userdata('tanggal').' '.date('H:i:s'),'tengah');
        $cetak          .= $this->app_model->maksimal(40,'Shift : '.$this->session->userdata('shift'),'tengah');
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->garis_empatpuluh();
        //$cetak          .= '<br/>';
        foreach($data->result() as $rowbarang)
        {
            $saldo          = $this->barang_saldo_model->saldo_hari_ini($rowbarang->barang_kd);
            if($saldo[0]['saldo_qty'] != 0)
            {
                $cetak      .= $this->app_model->maksimal(10,$rowbarang->barang_kd,'kiri');
                $cetak      .= $this->app_model->maksimal(20,$rowbarang->barang_nm,'kiri');
                $cetak      .= $this->app_model->maksimal(10,$saldo[0]['saldo_qty'],'kanan');
                //$cetak          .= '<br/>';
            }
        }
        $cetak          .= $this->app_model->garis_empatpuluh();
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,'Dicetak: ' . date('Y-m-d H:i'), 'kiri');
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        //$cetak          .= '<br/>';
        //echo $cetak;
        //die();
        
        printer_write($handle,$cetak);
        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);
        redirect('barang');
    }
    
    function cetak_stok_akhir_shift($judul='')
    {
        $dataglobal     = $this->app_model->general();
        $cetak          = '';
        $tglselanjutnya = '';
        $shiftselanjutnya = '';
        $tgl_saw        = $this->sys_var_model->get(PERIODE_SAW_BARANG);
        $data           = $this->barang_model->get('','','','','');
        //$data		    = $this->barang_saldo_model->get_saw($tgl_saw);
        $handle         = printer_open($dataglobal['nama_printer']);
        printer_set_option($handle, PRINTER_MODE, "RAW");
        printer_start_doc($handle, "PrintKasir");
        printer_start_page($handle);

        if ($judul!='')
        {
            $judul = 'Laporan Stock Tutup Shift';
        }
        else
        {
            $judul = 'Laporan Stock';
        }
        $cetak          .= $this->app_model->maksimal(40,$judul,'tengah');
        $cetak          .= $this->app_model->maksimal(40,$this->session->userdata('outlet_nm'),'tengah');
        $cetak          .= $this->app_model->maksimal(40,$this->session->userdata('user_nm'),'tengah');
        $cetak          .= $this->app_model->maksimal(40,$this->session->userdata('tanggal').' '.date('H:i:s'),'tengah');
        $cetak          .= $this->app_model->maksimal(40,'Shift : '.$this->session->userdata('shift'),'tengah');
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->garis_empatpuluh();
        //$cetak          .= '<br/>';
        foreach($data->result() as $rowbarang)
        {
            if($judul!='')
            {
                $etglsekarang                   = explode('-',$this->session->userdata('tanggal'));
                if($this->session->userdata('shift')=='1')
                {
                    $shiftselanjutnya           = '2';
                    $tglselanjutnya             = $this->session->userdata('tanggal');
                }
                else
                {
                    $shiftselanjutnya           = '1';
                    $tglselanjutnya             = date('Y-m-d',mktime(0,0,0,$etglsekarang[1],$etglsekarang[2]+1,$etglsekarang[0],0));
                }
                $saldo          = $this->barang_saldo_model->saldo($rowbarang->barang_kd,$tglselanjutnya,$shiftselanjutnya);
            }
            else
            {
                $saldo          = $this->barang_saldo_model->saldo_hari_ini($rowbarang->barang_kd);
            }
            if($saldo[0]['saldo_qty'] != 0)
            {
                $cetak      .= $this->app_model->maksimal(10,$rowbarang->barang_kd,'kiri');
                $cetak      .= $this->app_model->maksimal(26,$rowbarang->barang_nm,'kiri');
                $cetak      .= $this->app_model->maksimal(4,$saldo[0]['saldo_qty'],'kanan');
                //$cetak          .= '<br/>';
            }
        }
        $cetak          .= $this->app_model->garis_empatpuluh();
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,'Dicetak: ' . date('Y-m-d H:i'), 'kiri');
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        //$cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        //$cetak          .= '<br/>';
        //echo $cetak;
        //die();
        
        printer_write($handle,$cetak);
        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);
        if($judul=='')
        {
            redirect('barang');
        }
        else
        {
            redirect('tutup_shift');
        }
    }
    
    
    function kartu_stock($offset=0)
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
        
        $data['data_all']           = $this->barang_model->get('','','',$data['txtcari'],$data['cbogrup']);
        
        $base_url                   = base_url().'index.php/barang/kartu_stock';
        $total_rows                 = $data['data_all']->num_rows();
        $per_page                   = 30;
        $uri_segment                = 3;
        
        $config                     = $this->adnpagination->config($base_url,$total_rows,$per_page,$uri_segment);
        $this->pagination->initialize($config); 
        $data['data']               = $this->barang_model->get('',$per_page,$offset,$data['txtcari'],$data['cbogrup']);
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'barang/kartu_stock';
        $data['judulweb']           = ' | Kartu Stock';
        $this->load->view('layout/index',$data);   
    }
    
    function cetak_update_harga($kdbarang)
    {
        $dataglobal     = $this->app_model->general();
        $cetak          = '';
        $data           = $this->barang_model->get($kdbarang,'','','','');
        $handle         = printer_open($dataglobal['nama_printer']);
        printer_set_option($handle, PRINTER_MODE, "RAW");
        printer_start_doc($handle, "PrintKasir");
        printer_start_page($handle);
        $cetak          .= $this->app_model->maksimal(40,$this->session->userdata('outlet_nm'),'tengah');
        $cetak          .= $this->app_model->maksimal(40,'PERUBAHAN HARGA MANUAL','tengah');
        $cetak          .= $this->app_model->maksimal(20,'Tanggal Sesi : ','kiri');
        $cetak          .= $this->app_model->maksimal(20,$this->session->userdata('tanggal'),'kanan');
        $cetak          .= $this->app_model->maksimal(20,'Cetak : ','kiri');
        $cetak          .= $this->app_model->maksimal(20,date('Y-m-d H:i:s'),'kanan');        
        $cetak          .= $this->app_model->maksimal(20,'Shift : '.$this->session->userdata('shift'),'kiri');
        $cetak          .= $this->app_model->maksimal(20,'Oleh : '.$this->session->userdata('user_nm'),'kanan');
        $cetak          .= $this->app_model->garis_empatpuluh();
        $cetak          .= $this->app_model->maksimal(12,$data->row()->barang_kd,'kiri');
        $cetak          .= $this->app_model->maksimal(18,$data->row()->barang_nm,'kiri');
        $cetak          .= $this->app_model->maksimal(10,$data->row()->barang_harga_jual,'kanan');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        //$cetak          .= $this->app_model->garis_empatpuluh();
        printer_write($handle,$cetak);
        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);
    }
}

?>