<?php

class Tukar extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('curl');  
        $this->load->model('tukar_model');
        $this->load->model('history_model');
        $this->load->model('kasir_model');
        $this->load->model('barang_imei_model');
        //$this->load->model('log_proses_model');
    }
    //---- inovaPOS 14.0 -----
    function df($offset=0)
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

        //$data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'tukar/df';
        $data['judulweb']           = ' | Tukar Barang';
        $this->load->view('layout/index',$data);   
    }
    
    function getLstImei()
    {
        $kdbarang   = $this->input->post('kdBarang');
        $hasil  = false;
        $pesan  = "";
        
        try
        {
            $obj            = $this->barang_imei_model->get($kdbarang,'',1);
            if(count($obj)>0)
            {
                $hasil = true;
                $pesan = $obj->result();
            }
        }   
        catch(Exception $e)
        {
            $pesan = $e->getMessage();
        } 
        echo json_encode(new AdnResponse($hasil,$pesan));
    }
    
    function getLstImeiByNoFaktur()
    {
        $kdbarang   = $this->input->post('kdBarang');
        $NoStruk   = $this->input->post('NoStruk');
        $hasil  = false;
        $pesan  = "";
        
        try
        {
            $obj            = $this->barang_imei_model->get($kdbarang,'',0,$NoStruk);
            if(count($obj)>0)
            {
                $hasil = true;
                $pesan = $obj->result();
            }
        }   
        catch(Exception $e)
        {
            $pesan = $e->getMessage();
        } 
        echo json_encode(new AdnResponse($hasil,$pesan));
    }
    
    function getLstImeiPengganti()
    {
        $kdbarang   = $this->input->post('kdBarang');
        $hasil  = false;
        $pesan  = "";
        
        try
        {
            $obj            = $this->barang_imei_model->get($kdbarang,'',1);
            if(count($obj)>0)
            {
                $hasil = true;
                $pesan = $obj->result();
            }
        }   
        catch(Exception $e)
        {
            $pesan = $e->getMessage();
        } 
        echo json_encode(new AdnResponse($hasil,$pesan));
        
    }
    
    function input()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'tukar/input';
        $data['judulweb']           = ' | Tukar Barang';
        $this->load->view('layout/index',$data); 
    }
    
    function cariStrukJual()
    {
        $no_struk   = $this->input->post('no_struk');
        $hasil  = false;
        $pesan  = "";
        
        try
        {
            //cek di histori dulu
            $batas_tukar    = $this->app_model->system("lama_tukar_maksimal");
            $obj            = $this->history_model->get2($no_struk);
            
            if(count($obj)>0)
            {
                $now        = time();
                $tgl_tr     = strtotime($obj[0]->tgl);
                $datediff   = $now - $tgl_tr;
                $selisih_hari =floor($datediff/(60*60*24));
                if($selisih_hari<=$batas_tukar){
                    $hasil = true;
                    $pesan = $obj;
                }
                else
                {              
                    $pesan = "Tidak Bisa Ditukar Melewati Batas Hari!";
                    $hasil = false;
                }
            }
            //=======cek di histori dulu================
            else
            {
                $obj            = $this->kasir_model->getByNoStruk($no_struk);
                if(count($obj)>0)
                {
                    $hasil = true;
                    $pesan = $obj;
                }
            }
        }   
        catch(Exception $e)
        {
            $pesan = $e->getMessage();
        } 
        echo json_encode(new AdnResponse($hasil,$pesan));
    }
    
    function pilihBarang()
    {
        $this->load->library('curl');
        $data   ="";
        //$data                   = json_decode($_REQUEST['data'], true);
        $data['tgl']            = $this->session->userdata('tanggal');
        $data['shift']          = $this->session->userdata('shift');
        $data['kd_gudang']      = $this->session->userdata('outlet_kd');

//        $this->curl->create($this->app_model->system('pos_service_url').'/KartuStok');
//        $this->curl->post(json_encode($data));  
//        $hasil              = json_decode($this->curl->execute(),true);  
        $data['url']        = $this->app_model->system('pos_service_url') ."/DfBarang" ;
        $hasil              = $this->curl->simple_get($data['url']); 
        $hasil              = json_decode($hasil);
        echo $hasil->DfBarangResult;
    }
    
    function simpan()
    {
        $this->load->library('curl');

        $hasil  = false;
        $pesan  = "";
        
        try
        {
            $data                   = json_decode($_REQUEST['data'], true);
            $data['shift']          = $this->session->userdata('shift');
            $data['uid']            = $this->session->userdata('user_kd'); 
            $data['uid_edit']       = $data['uid'];
            $data['tgl_tambah']     = date('Y-m-d H:i:s');
            $data['tgl_edit']       = date('Y-m-d H:i:s');
            
            $this->curl->create($this->app_model->system('pos_service_url').'/SimpanTukar');  
            $this->curl->post(json_encode($data));  
            $ws_pesan = json_decode($this->curl->execute(),true);  

            $ws_pesan = json_decode($ws_pesan); 
            if($ws_pesan->IsSuccess)
            {
                $hasil = true;
            }
            $pesan = $ws_pesan->Message;
        }
        catch(Exception $e)
        {
            $pesan = $e->getMessage();
        } 
        echo json_encode(new AdnResponse($hasil,$pesan));
    }
    
    function aj_df()
    {
        $data['url']        = $this->app_model->system('pos_service_url') ."/DfTukar" ;
        $hasil              = $this->curl->simple_get($data['url']); 
        $hasil              = json_decode($hasil);
        echo $hasil->DfTukarResult;
    }   
    
    function cariImei()
    {
        $imei   = $this->input->post('imei');
        $hasil  = false;
        $pesan  = "";
        
        try
        {
            $isAda            = $this->history_model->cekImei($imei);
            
            if($isAda){
                $hasil = true;
            }
            else
            {              
                $pesan = "Imei Tidak Ditemukan!";
                $hasil = false;
            }

        }   
        catch(Exception $e)
        {
            $pesan = $e->getMessage();
        } 
        echo json_encode(new AdnResponse($hasil,$pesan));
    }
    
    function getBarangByImei()
    {
        $imei   = $this->input->post('imei');
        $noStruk   = $this->input->post('noStruk');
        $hasil  = false;
        $pesan  = "";
        
        try
        {
            $obj          = $this->history_model->getBarangByImei($noStruk,$imei);
            
            if(count($obj)>0)
            {
                $pesan = $obj[0];
                $hasil = true;
            }
            else
            {              
                $pesan = "Barang Tidak Ditemukan!";
                $hasil = false;
            }

        }   
        catch(Exception $e)
        {
            $pesan = $e->getMessage();
        } 
        echo json_encode(new AdnResponse($hasil,$pesan));
    }
     
    function test()
    {
        $this->load->library('curl');

        $hasil  = false;
        $pesan  = "";
        
        //$data                   = json_decode($_REQUEST['data'], true);
        $data['uid']            = "adn"; //;$this->session->userdata('user_kd_gl');
        $data['uid_edit']       = $data['uid'];
        $data['tgl_tambah']     = date('Y-m-d H:i:s');
        $data['tgl_edit']       = date('Y-m-d H:i:s');
        
        $this->curl->create($this->app_model->system('pos_service_url').'/SimpanTukar');  
        $this->curl->post(json_encode($data));  
        $ws_pesan = json_decode($this->curl->execute());
        //print_r($ws_pesan);
        $x = (json_decode($ws_pesan));  
        if($x->IsSuccess)
        {
            echo 'true';
        }
        else
        {
            echo 'false';
        }
    }
    
}