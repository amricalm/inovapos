<?php

class Sinkronisasi extends CI_Controller
{
    function Sinkronisasi()
    {
        parent::__construct();
        $this->load->library('curl');  
        $this->load->model('barang_model');
        $this->load->model('barang_imei_model');
        $this->load->model('barang_saldo_model');
        $this->load->model('barang_mutasi_model');
        $this->load->model('group_barang_model');
        $this->load->model('satuan_model');
        $this->load->model('outlet_model');
        $this->load->model('pelanggan_model');
        $this->load->model('karyawan_model');
        $this->load->model('promosi_model');
        $this->load->model('log_proses_model');
    }
    function index()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['halaman']            = 'sinkronisasi/index';
        $data['judulweb']           = ' | Sinkronisasi';
        $this->load->view('layout/index',$data);      
    }
    function halaman_update()
    {
        if($this->session->userdata('user_nm')=='') 
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['halaman']            = 'sinkronisasi/halaman_update';
        $data['judulweb']           = ' | Sinkronisasi | Update Harga';
        $this->load->view('layout/index',$data);   
    }
    function halaman_stok()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['halaman']            = 'sinkronisasi/halaman_stok';
        $data['judulweb']           = ' | Sinkronisasi | Stok';
        $this->load->view('layout/index',$data);   
    }
    function halaman_mutasi()
    {
        
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['halaman']            = 'sinkronisasi/halaman_mutasi';
        $data['judulweb']           = ' | Sinkronisasi | Mutasi Barang';
        $this->load->view('layout/index',$data);   
    }
    function display_harga()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['url']                = $this->app_model->system('service_url').'api/service_barang/barang_harga/id/'.$this->session->userdata('outlet_kd').'/format/json';
        //echo $data['url'];die();
        $data['data']               = $this->curl->simple_get($data['url']);

        $data['data_array']         = json_decode($data['data'],true);

        /* Simpan di File  */
        $namafile                   = trim($this->session->userdata('outlet_kd')).'@harga'.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        if(file_exists($ourFileName))
        {
            unlink($ourFileName);
        }
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$data['data']);
        fclose($ourFileHandle);
        
        /* Buka dari File
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        */
        
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/display_harga';
        $data['judulweb']           = ' | Update Harga';
        $this->load->view('layout/index',$data);      
    }
    function simpan_harga()
    {
        $data                       = $this->app_model->general();
        $data['error']              = '';
        $data['hasil']              = false;
        /* Buka dari URL 
        $data['url']                = $this->config->item('service_url').'api/service_barang/barang_harga/id/'.$this->session->userdata('outlet_kd').'/format/json';
        $data['data']               = $this->curl->simple_get($data['url']);
        */
        /* Buka dari File */
        $ourFileName                = $data['base_upload'].'/'.$this->session->userdata('outlet_kd').'@harga.txt';
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        
        $data['data_array']         = json_decode($data['data'],true);
        
        $this->db->trans_begin();
        for($i=0;$i<count($data['data_array']);$i++)
        {
            if($data['data_array'][$i]['harga']!='0')
            {
                $datainput['barang_kd']         = $data['data_array'][$i]['kd_barang'];
                $datainput['barang_nm']         = $data['data_array'][$i]['nm_barang'];
                $datainput['barang_group']      = $data['data_array'][$i]['kd_group'];
                $datainput['barang_satuan']     = $data['data_array'][$i]['satuan'];
                $datainput['barang_harga_jual'] = $data['data_array'][$i]['harga'];
                $datainput['barang_harga_pokok']= round($data['data_array'][$i]['hpp']);
                if($datainput['barang_harga_jual']!='0' && $datainput['barang_harga_jual']!=0)
                {
                    if($this->barang_model->hapus($datainput['barang_kd']))
                    {
                        if($this->barang_model->simpan($datainput))
                        {
                            $data['hasil']          = true;
                        }
                        else
                        {
                            $data['error']          .= 'Error Pada Simpan Barang!';
                        }
                    }
                    else
                    {
                        $data['error']              .= 'Error Pada Hapus Barang!';
                    }
                }
            }
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo '<script type="text/javascript">alert("Gagal Update Harga!");history.go(-1);</script>';
            die();
        }
        else
        {
            $this->db->trans_commit();
            $dataarray['log_col']                   = 'UPDATE HARGA';
            $dataarray['log_val']                   = 'SUKSES';
            $dataarray['tgl']                       = $this->session->userdata('tanggal').' '.date('H:i:s');
            $dataarray['shift']                     = $this->session->userdata('shift');
            $dataarray['tipe']                      = 'UPDATE HARGA';
            $dataarray['uid']                       = $this->session->userdata('user_kd');
            $dataarray['tgl_tambah']                = date('Y-m-d H:i:s');
            $this->log_proses_model->simpanLogJual($dataarray);
            echo '<script type="text/javascript">alert("Sukses Update Harga!");parent.iclose();</script>';
            die();
        }
    }
    function simpan_harga_file()
    {
        $data['data']               = $this->input->post('data');
        $data['data_array']         = json_decode($data['data'],true);
        $data['error']              = '';
        for($i=0;$i<count($data['data_array']);$i++)
        {
            $datainput['barang_kd']         = $data['data_array'][$i]['kd_barang'];
            $datainput['barang_nm']         = $data['data_array'][$i]['nm_barang'];
            $datainput['barang_group']      = $data['data_array'][$i]['kd_group'];
            $datainput['barang_satuan']     = $data['data_array'][$i]['satuan'];
            $datainput['barang_harga_jual'] = $data['data_array'][$i]['harga'];
            
            if($this->barang_model->hapus($datainput['barang_kd']))
            {
                if($this->barang_model->simpan($datainput))
                {
                    $data['hasil']          = true;
                }
                else
                {
                    $data['error']          .= 'Error Pada Simpan Barang!';
                }
            }
            else
            {
                $data['error']              .= 'Error Pada Hapus Barang!';
            }
        }
        if($data['error']=='')
        {
            echo '<script type="text/javascript">alert("Sukses Update Harga!");window.location="'.base_url().'index.php/sinkronisasi/display_upload_file/update_harga";</script>';
        }
        else
        {
            echo '<script type="text/javascript">alert("Gagal Update Harga!");history.go(-1);</script>';
        }
    }
    function kirim_jual()  
    {  
        $this->load->model('kasir_model','kasir');

        $tanggal                    = $this->session->userdata('tanggal');
        $shift                      = $this->session->userdata('shift');
        $query                      = $this->kasir->kirim_get($tanggal,$shift);
//		print_r($query);
//		die();
        $data                       = json_encode($query);

        $this->curl->create($this->app_model->system('service_url') . 'api/service_jual/jual/format/json');  
        $this->curl->post($data);  

        $result = json_decode($this->curl->execute());  
        
        if(isset($result) && $result == 'Sukses')  
        {  
            echo 'Proses Pengiriman Sukses!';  
        }  
        else  
        {  
            echo 'Proses Pengiriman sudah dilakukan atau ada error ketika dikirim!';
        }  
    }  
    function display_upload_file($type)
    {
        $data                       = $this->app_model->general();
        $data['tipe']               = '';
        switch ($type)
        {
            case 'update_harga' : 
                $data['tipe']       = 'Update Harga';
                break;
            case 'mutasi_barang' :
                $data['tipe']       = 'Mutasi Barang';
                break; 
        }
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/upload_file';
        $data['judulweb']           = ' | Upload File';
        $this->load->view('layout/index',$data);      
    } 
    function display_file($type)
    {
        $data                       = $this->app_model->general();
        $data['data']               = '';
        switch ($type)
        {
            case 'update_harga' : 
                $data['tipe']       = 'harga';
                $data['tipeteks']   = 'Update Harga';
                break;
            case 'mutasi_barang' :
                $data['tipe']       = 'TX';
                $data['tipeteks']   = 'Mutasi Barang';
                break; 
        }
        $data['option_tampilan']    = 'tanpa_menu';
        if((!empty($_FILES["file"])) && ($_FILES['file']['error'] == 0))
        {
            if($data['tipe']=='harga')
            {
                $filename       = basename($_FILES['file']['name']);
                $extdannama     = explode('.',$filename);
                $gudangdantipe  = explode('@',$extdannama[0]);
                $gudang         = $gudangdantipe[0];
                $tipe           = $gudangdantipe[1];
                if($gudang!=$this->session->userdata('outlet_kd'))
                {
                    echo '<script type="text/javascript">alert("File bukan untuk Gudang/Outlet ini!");history.go(-1);</script>';
                }
                else
                {
                    if($tipe!=$data['tipe'])
                    {
                        echo '<script type="text/javascript">alert("File bukan untuk '.$data['tipeteks'].'!");history.go(-1);</script>';
                    }
                    else
                    {
                        $ext            = substr($filename, strrpos($filename, '.') + 1);
                        $tgl            = str_replace('-','',$this->session->userdata('tanggal')).date('His');
                        $target_path    = $data['base_upload'];
                        $nama_path      = $data['tipe'].$tgl.'.'.$ext;
                        if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path.'/'.$nama_path)) 
                        {
                            $ourFileName    = $target_path.'/'.$nama_path;
                            $ourFileHandle  = fopen($ourFileName, 'r') or die("can't open file");
                            $dataFile       = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
                            $data['data']   = $dataFile;
                        }
                    }
                }
                $data['halaman']            = 'sinkronisasi/display_file';
            }
            else
            {
                $filename       = basename($_FILES['file']['name']);
                $extdannama     = explode('.',$filename);
                $namafile       = explode('-',$extdannama[0]);
                $tipe           = $namafile[0];
                $gudangasal     = $namafile[1];
                $gudangtujuan   = $namafile[2];
                $nofaktur       = $namafile[3];
                if($gudangtujuan!=$this->session->userdata('outlet_kd'))
                {
                    echo '<script type="text/javascript">alert("File bukan untuk Gudang/Outlet ini!");history.go(-1);</script>';
                }
                else
                {
                    if($tipe!=$data['tipe'])
                    {
                        echo '<script type="text/javascript">alert("File bukan untuk '.$data['tipeteks'].'!");history.go(-1);</script>';
                    }
                    else
                    {
                        //$ext            = substr($filename, strrpos($filename, '.') + 1);
                        //$tgl            = date('YmdHis');
                        $target_path    = $data['base_upload'];
                        //$nama_path      = $data['tipe'].$tgl.'.'.$ext;
                        if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path.'/'.$filename)) 
                        {
                            $ourFileName    = $target_path.'/'.$filename;
                            $ourFileHandle  = fopen($ourFileName, 'r') or die("can't open file");
                            $dataFile       = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
                            $data['data']   = $dataFile;
                        }
                    }
                }
                $data['halaman']            = 'sinkronisasi/display_file_mutasi';
            }
        }
        $data['judulweb']           = ' | File';
        $this->load->view('layout/index',$data); 
    }
    function mutasi_barang()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['outlet']             = $this->outlet_model->outlet_ambil();
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['halaman']            = 'barang/mutasi_barang';
        $data['judulweb']           = ' | Mutasi Barang';
        $this->load->view('layout/index',$data); 
    }
    function daftar_mutasi_barang()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['option_tampilan']    = 'tanpa_menu';
        $data['outlet']             = $this->outlet_model->outlet_ambil();
        $data['data_group']         = $this->group_barang_model->get('','','','');
        $data['data_array']         = array();
        $data['data_hdr']           = $this->barang_mutasi_model->get('','');
        $seqhdr                     = 0;
        foreach($data['data_hdr']->result() as $rowhdr)
        {
            //if($rowhdr->kd_gudang_asal == $this->session->userdata('outlet_kd'))
            //{
                $data['data_array'][$seqhdr]['no_faktur']   = $rowhdr->no_faktur;
                $data['data_array'][$seqhdr]['tgl']         = $rowhdr->tgl;
                $data['data_array'][$seqhdr]['kd_gudang_asal'] = $rowhdr->kd_gudang_asal;
                $data['data_array'][$seqhdr]['kd_gudang_tujuan'] = $rowhdr->kd_gudang_tujuan;
                $data['data_array'][$seqhdr]['ref']         = $rowhdr->ref;
                $data['data_array'][$seqhdr]['ket']         = $rowhdr->ket;
                $data['data_array'][$seqhdr]['st_dokumen']  = $rowhdr->st_dokumen;
                $data['data_array'][$seqhdr]['status']      = $rowhdr->status;
                $data['data_array'][$seqhdr]['sinkronisasi'] = $rowhdr->sinkronisasi;
                $data['data_array'][$seqhdr]['datadetail']  = array();
                $data['data_dtl']                           = $this->barang_mutasi_model->get_dtl($rowhdr->no_faktur);
                if($data['data_dtl']->num_rows() > 0)
                {
                    $seqdtl                                 = 0;
                    foreach($data['data_dtl']->result() as $rowdtl)
                    {
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['no_faktur']    = $rowdtl->no_faktur;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['kd_barang']    = $rowdtl->kd_barang;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['qty']          = $rowdtl->qty;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['satuan']       = $rowdtl->satuan;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['urutan']       = $rowdtl->urutan;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['imei']         = array();
                        
                        //$data['data_imei']                                                  = $this->barang_imei_model->get($rowdtl->kd_barang,'','',$rowhdr->no_faktur);
                        $data['data_imei']                                                  = $this->barang_mutasi_model->get_dtl_imei($rowhdr->no_faktur,$rowdtl->kd_barang);
                        $seqimei                                                            = 0;
                        if($data['data_imei']->num_rows() > 0)
                        {
                            foreach($data['data_imei']->result() as $rowimei)
                            {
                                $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['imei'][$seqimei] = $rowimei->imei;
                                $seqimei++;
                            }   
                        }
                    }
                }
                $seqhdr++;
            //}
        } 
        $data['data']               = json_encode($data['data_array']);
        $data['halaman']            = 'sinkronisasi/daftar_mutasi';
        $data['judulweb']           = ' | Daftar Mutasi Barang';
        $this->load->view('layout/index',$data); 
    }
    
    function daftar_mutasi_detail($nofaktur)
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['data_hdr']           = $this->barang_mutasi_model->get($nofaktur,'');
        //echo $this->db->last_query();
        //die();
        $data['data_array']         = array();
        $seqhdr                     = 0;
        foreach($data['data_hdr']->result() as $rowhdr)
        {
            //if($rowhdr->kd_gudang_asal == $this->session->userdata('outlet_kd'))
            //{
                $data['data_array'][$seqhdr]['no_faktur']   = $rowhdr->no_faktur;
                $data['data_array'][$seqhdr]['tgl']         = $rowhdr->tgl;
                $data['data_array'][$seqhdr]['kd_gudang_asal'] = $rowhdr->kd_gudang_asal;
                $data['data_array'][$seqhdr]['kd_gudang_tujuan'] = $rowhdr->kd_gudang_tujuan;
                $data['data_array'][$seqhdr]['ref']         = $rowhdr->ref;
                $data['data_array'][$seqhdr]['ket']         = $rowhdr->ket;
                $data['data_array'][$seqhdr]['st_dokumen']  = $rowhdr->st_dokumen;
                $data['data_array'][$seqhdr]['datadetail']  = array();
                $data['data_dtl']       = $this->barang_mutasi_model->get_dtl($rowhdr->no_faktur);
                if($data['data_dtl']->num_rows() > 0)
                {
                    $seqdtl                                 = 0;
                    foreach($data['data_dtl']->result() as $rowdtl)
                    {
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['no_faktur']    = $rowdtl->no_faktur;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['kd_barang']    = $rowdtl->kd_barang;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['qty']          = $rowdtl->qty;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['satuan']       = $rowdtl->satuan;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['urutan']       = $rowdtl->urutan;
                        $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['imei']         = array();
                        
                        //$data['data_imei']                                                  = $this->barang_imei_model->get($rowdtl->kd_barang,'','',$rowhdr->no_faktur);
                        $data['data_imei']                                                  = $this->barang_mutasi_model->get_dtl_imei($rowhdr->no_faktur,$rowdtl->kd_barang);
                        $seqimei                                                            = 0;
                        if($data['data_imei']->num_rows() > 0)
                        {
                            foreach($data['data_imei']->result() as $rowimei)
                            {
                                $data['data_array'][$seqhdr]['datadetail'][$seqdtl]['imei'][$seqimei] = $rowimei->imei;
                                $seqimei++;
                            }   
                        }
                        $seqdtl++;
                    }
                }
                $seqhdr++;
            //}
        }
        $data['data']               = json_encode($data['data_array']);
        $data['no_faktur']          = $nofaktur;
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/daftar_mutasi_detail';
        $data['judulweb']           = ' | Mutasi Barang Detail';
        $this->load->view('layout/index',$data); 
    }
    function simpan_mutasi()
    {
        $this->db->trans_begin(); // TRANSAKSI DIMULAI
        $error                      = '';
        $data                       = array();
        $datadtl                    = array();
        $dataimei                   = array();
        $sesitanggal                = explode('-',$this->session->userdata('tanggal'));
        $sesitanggalt               = substr($sesitanggal[0],2,2);
        $data['kd_gudang_asal']     = $this->session->userdata('outlet_kd');
        $data['kd_gudang_tujuan']   = $this->input->post('outlet');
        $max_faktur                 = ($this->barang_mutasi_model->get_max_faktur($sesitanggalt.$sesitanggal[1])->num_rows()>0) ? $this->barang_mutasi_model->get_max_faktur($sesitanggalt.$sesitanggal[1])->row()->ttt : 0;
        $max_faktur_history         = ($this->barang_mutasi_model->get_max_faktur_history($sesitanggalt.$sesitanggal[1])->num_rows()>0) ? $this->barang_mutasi_model->get_max_faktur_history($sesitanggalt.$sesitanggal[1])->row()->ttt : 0;
        $nofaktur                   = ($max_faktur > $max_faktur_history) ? $max_faktur : $max_faktur_history;
        $data['no_faktur']          = 'OTX'.$data['kd_gudang_asal'].$sesitanggalt.$sesitanggal[1].$this->app_model->max_lima_karakter((int)$nofaktur+1);
        $data['tgl']                = $this->input->post('tgl');
        $data['ket']                = $this->input->post('keterangan');
        if($this->barang_mutasi_model->simpan($data))
        {
            $error                  = '';
            $datadtl['no_faktur']   = $data['no_faktur'];
            $databarang             = $this->input->post('barang_kd');
//            for($i=0;$i<count($databarang);$i++)
//            {
//                $datadtl['kd_barang'] = $databarang[$i];
//                $datadtl['qty']     = $this->input->post('qty'.($i+1));
//                $stok               = $this->barang_saldo_model->saldo_hari_ini($datadtl['kd_barang']);
//                if($stok[0]['saldo_qty'] < $datadtl['qty'])
//                {
//                    $error          = 'Jumlah';
//                }
//            }
            if($error=='')
            {
            //ho  $databarang[0] . '-' .$databarang[1] .'-'.$databarang[2] ;die;
                for($i=0;$i<count($databarang);$i++)
                {
                    $datadtl['kd_barang'] = $databarang[$i];
                    $datadtl['qty']     = $this->input->post('qty'.($i+1));
                    $datadtl['urutan']  = $i;
                    if($this->barang_mutasi_model->simpan_dtl($datadtl))
                    {
                        $dataimei['no_faktur']      = $data['no_faktur'];
                        $dataimei['kd_barang']      = $databarang[$i];
                        $dataimei['imei']           = $this->input->post('barang_imei'.($i+1));
    
                        if($this->input->post('barang_imei'.($i+1))!="")
                        {
                            $koleksi_imei = explode('#',$dataimei['imei']);
                            for($j=0;$j<count($koleksi_imei);$j++)
                            {
                                $dataimei['imei'] = $koleksi_imei[$j];
                                $this->barang_mutasi_model->simpan_dtl_imei($dataimei);
                                
                                $cekimei                    = $this->barang_imei_model->get($datadtl['kd_barang'],$dataimei['imei'],'');
                                if($cekimei->num_rows() > 0)
                                {
                                    $imeibarang['imei_barang']      = $datadtl['kd_barang'];
                                    $imeibarang['imei_no']          = $dataimei['imei'];
                                    $imeibarang['imei_ref']         = $data['no_faktur'];
                                    $imeibarang['imei_status']      = 0;
                                    $imeibarang['uid_edit']         = $this->session->userdata('user_kd');
                                    $imeibarang['doe_edit']         = $this->session->userdata('tanggal').' '.date('H:i:s');
                                    $this->barang_imei_model->update($datadtl['kd_barang'],$dataimei['imei'],$imeibarang);
                                    //echo $this->db->last_query();
                                    //die();
                                }
                                else
                                {
                                    $imeibarang['imei_barang']      = $dataimei['kd_barang'];
                                    $imeibarang['imei_no']          = $dataimei['imei'];
                                    $imeibarang['imei_ref']         = $dataimei['no_faktur'];
                                    $imeibarang['imei_status']      = 0;
                                    $imeibarang['uid']              = $this->session->userdata('user_kd');
                                    $imeibarang['doe']              = $this->session->userdata('tanggal').' '.date('H:i:s');
                                    $this->barang_imei_model->simpan($imeibarang);
                                }
                            }
                        }
                    }
                    else
                    {
                        $error          .= 'error!';
                    }
                }
            }
        }
        else
        {
            $error                  .= 'error!';
        }
        //if($error!='')
        if ($this->db->trans_status() === FALSE) //CEK JIKA GAGAL
        {
            $this->db->trans_rollback(); //ROLLBACK
//            if($error=='Jumlah')
//            {
//                echo '<script type="text/javascript">alert("Stok kurang, mohon ulangi!");window.location="'.base_url().'index.php/sinkronisasi/mutasi_barang";</script>';
//            }
//            else
//            {
                echo '<script type="text/javascript">alert("Ada kesalahan data!");window.location="'.base_url().'index.php/sinkronisasi/mutasi_barang";</script>';
//            }
        }
        else
        {
            if($error!='')
            {
                $this->db->trans_rollback();
                echo '<script type="text/javascript">alert("Ada kesalahan data!");window.location="'.base_url().'index.php/sinkronisasi/mutasi_barang";</script>';
            }
            else
            {
                $this->db->trans_commit(); //DILAKUKAN
                $redirect               = 'mutasi_barang';
                $namafile               = 'TX-'.$data['kd_gudang_asal'].'-'.$data['kd_gudang_tujuan'].'-'.date('ym').$this->app_model->max_lima_karakter((int)$max_faktur+1);
                //echo '<script type="text/javascript">alert("Berhasil disimpan!");window.location="'.base_url().'index.php/sinkronisasi/sukses_simpan_mutasi/'.$namafile.'/'.$redirect.'";</script>';
                //$namafile               = $datadtl['no_faktur'];
                redirect('sinkronisasi/sukses_simpan_mutasi/'.$namafile.'/'.$redirect);
            }
        }
//        if($error=='')
//        {
//            $redirect               = 'mutasi_barang';
//            $namafile               = 'TX-'.$data['kd_gudang_asal'].'-'.$data['kd_gudang_tujuan'].'-'.date('ym').$this->app_model->max_lima_karakter((int)$max_faktur+1);
//            //echo '<script type="text/javascript">alert("Berhasil disimpan!");window.location="'.base_url().'index.php/sinkronisasi/sukses_simpan_mutasi/'.$namafile.'/'.$redirect.'";</script>';
//            //$namafile               = $datadtl['no_faktur'];
//            redirect('sinkronisasi/sukses_simpan_mutasi/'.$namafile.'/'.$redirect);
//        }
//        else
//        {
//            echo '<script type="text/javascript">alert("Ada kesalahan data!");history.go(1);</script>';
//        }
    }
    function simpan_mutasi_file()
    {
        $data                       = array();
        $hasil                      = false;
        $data['barangbaru']         = '';
        $data['error']              = '';
        $data['sumber']             = $this->input->post('sumber');
        $data['data']               = $this->input->post('data');
        $data['no_faktur']          = $this->input->post('no_faktur');
        $data['perfaktur']          = $this->input->post('data_array');
        $dataperfaktur              = json_decode($data['perfaktur'],true);
        $data_array                 = json_decode($data['data'],true);
        
        natsort($dataperfaktur);
        
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        for($i=0;$i<count($data_array);$i++)
        {
            $datainput['no_faktur'] = $data_array[$i]['no_faktur'];
            if(trim($datainput['no_faktur'])!=trim($data['no_faktur']))
            {
                $data['error']                          .= 'Pilih data yang awal terlebih dahulu!';
                break;
            }
            else
            {
                //$datainput['tgl']       = $data_array[$i]['tgl'];
                $datainput['tgl']       = $dataperfaktur[$i]['tgl'];
                //$datainput['ket']       = $data_array[$i]['ket'];
                $datainput['ket']       = $dataperfaktur[$i]['ket'];
                //$datainput['dfsdfsdf']       = $dataperfaktur[$i]['ket'];
                //$datainput['kd_gudang_asal'] = $data_array[$i]['kd_gudang_asal'];
                $datainput['kd_gudang_asal'] = $dataperfaktur[$i]['kd_gudang_asal'];
                //$datainput['kd_gudang_tujuan'] = $data_array[$i]['kd_gudang_tujuan'];
                $datainput['kd_gudang_tujuan'] = $dataperfaktur[$i]['kd_gudang_tujuan'];
                //$datainput['ref']       = $data_array[$i]['ref'];
                $datainput['ref']       = $dataperfaktur[$i]['ref'];                
                //$datainput['st_dokumen'] = $data_array[$i]['st_dokumen'];
                $datainput['st_dokumen'] = $dataperfaktur[$i]['st_dokumen'];   
                
                if(count($dataperfaktur[$i]['datadetail'])>0)
                {
                    if($this->barang_mutasi_model->simpan($datainput))
                    {
                        for($j=0;$j<count($dataperfaktur[$i]['datadetail']);$j++)
                        {
                            if($j==(count($dataperfaktur[$i]['datadetail'])-1))
                            {
                                //echo 'berakhir';
                                if($dataperfaktur[$i]['datadetail'][$j]['no_faktur']=='EOF')
                                {
                                    //echo '-true';die();
                                    $hasil = true;
                                }
                                else
                                {
                                    //echo '-false';die();
                                    $hasil = false;
                                }
                                break;
                            }
                            //$datainputdtl['no_faktur']  = $data_array[$i]['datadetail'][$j]['no_faktur'];
                            $datainputdtl['no_faktur']  = $dataperfaktur[$i]['datadetail'][$j]['no_faktur'];
                            //$datainputdtl['kd_barang']  = $data_array[$i]['datadetail'][$j]['kd_barang'];
                            $datainputdtl['kd_barang']  = $dataperfaktur[$i]['datadetail'][$j]['kd_barang'];
                            //$datainputdtl['nm_barang']  = $data_array[$i]['datadetail'][$j]['nm_barang'];
                            //$datainputdtl['qty']        = $data_array[$i]['datadetail'][$j]['qty'];
                            $datainputdtl['qty']        = $dataperfaktur[$i]['datadetail'][$j]['qty'];
                                    //$datainputdtl['testetset']     = '';
                            //$datainputdtl['satuan']     = $data_array[$i]['datadetail'][$j]['satuan'];
                            $datainputdtl['satuan']     = $dataperfaktur[$i]['datadetail'][$j]['satuan'];
                            //$datainputdtl['urutan']     = $data_array[$i]['datadetail'][$j]['urutan'];
                            $datainputdtl['urutan']     = $dataperfaktur[$i]['datadetail'][$j]['urutan'];
                            if($this->barang_mutasi_model->simpan_dtl($datainputdtl))
                            {
                                $databrg                = $this->barang_model->get($dataperfaktur[$i]['datadetail'][$j]['kd_barang'],'','','','','');
                                if($databrg->num_rows() == 0)
                                {
                                    $databarang['barang_kd']    = $dataperfaktur[$i]['datadetail'][$j]['kd_barang'];
                                    $databarang['barang_nm']    = $dataperfaktur[$i]['datadetail'][$j]['nm_barang'];
                                    $databarang['barang_group'] = $dataperfaktur[$i]['datadetail'][$j]['kd_group'];
                                    $this->barang_model->simpan($databarang);
                                    $data['barangbaru']         = 'Ada Barang Baru!';
                                }
                                if(count($dataperfaktur[$i]['datadetail'][$j]['imei'])>0)
                                {
                                    $datainputdtlimei['kd_barang']  = $dataperfaktur[$i]['datadetail'][$j]['kd_barang'];
                                    $datainputdtlimei['no_faktur']  = $dataperfaktur[$i]['datadetail'][$j]['no_faktur'];
                                    for($k=0;$k<count($dataperfaktur[$i]['datadetail'][$j]['imei']);$k++)
                                    {
                                        $datainputdtlimei['imei']   = $dataperfaktur[$i]['datadetail'][$j]['imei'][$k];
                                        $this->barang_mutasi_model->simpan_dtl_imei($datainputdtlimei);
                                        //echo $k;
                                        $cekimei                    = $this->barang_imei_model->get($datainputdtlimei['kd_barang'],$datainputdtlimei['imei'],'');
                                        // echo $this->db->last_query();
                                        if($cekimei->num_rows() > 0)
                                        {
                                            $imeibarang['imei_barang']      = $datainputdtlimei['kd_barang'];
                                            $imeibarang['imei_no']          = $datainputdtlimei['imei'];
                                            $imeibarang['imei_ref']         = $datainputdtlimei['no_faktur'];
                                            $imeibarang['imei_status']      = 1;
                                            $imeibarang['uid_edit']         = $this->session->userdata('user_kd');
                                            $imeibarang['doe_edit']         = $this->session->userdata('tanggal').' '.date('H:i:s');
                                            $this->barang_imei_model->update($datainputdtlimei['kd_barang'],$datainputdtlimei['imei'],$imeibarang);
                                        }
                                        else
                                        {
                                            $imeibarang['imei_barang']      = $datainputdtlimei['kd_barang'];
                                            $imeibarang['imei_no']          = $datainputdtlimei['imei'];
                                            $imeibarang['imei_ref']         = $datainputdtlimei['no_faktur'];
                                            $imeibarang['imei_status']      = 1;
                                            $imeibarang['uid']              = $this->session->userdata('user_kd');
                                            $imeibarang['doe']              = $this->session->userdata('tanggal').' '.date('H:i:s');
                                            $this->barang_imei_model->simpan($imeibarang);
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $data['error']          .= 'Ada Kesalahan data!\r\n';
                            }
                        }
                    }
                }
                break;
            }
        }
//        if($dataperfaktur[1]=='EOF')
//        {
//            $hasil              = true;
//        }
//        else
//        {
//            $data['error']      .= 'Tidak mencapai akhir dari file,tutup jendela ini, lalu tekan F5.\r\n Ulangi Proses!\r\n';
//            $hasil              = false;
//        }
        if ($this->db->trans_status() === FALSE) //CEK JIKA GAGAL
        {
            $this->db->trans_rollback(); //ROLLBACK
            echo '<script type="text/javascript">alert("'.$data['error'].'");history.go(-1);</script>';
        }
        else
        {
            if($hasil==false)
            {
                $this->db->trans_rollback();
                $data['error']      = 'Tidak mencapai akhir dari file,tutup jendela ini, lalu tekan F5.\r\n Ulangi Proses!\r\n';
                echo '<script type="text/javascript">alert("'.$data['error'].'");history.go(-1);</script>';
            }
            else
            {
                $this->db->trans_commit(); //DILAKUKAN
                $alertbarang            = ($data['barangbaru']!='') ? 'alert("Ada Barang Baru, harap Update Harga!");' : '';
                if($data['sumber']=='server')
                {
                    echo '<script type="text/javascript">window.opener.segarkembali();window.close();</script>';
                }
                else
                {
                    echo '<script type="text/javascript">alert("Berhasil simpan data!");'.$alertbarang.'parent.iclose();</script>';
                }
            }
        }
    }
    function sukses_simpan_mutasi($nofaktur,$redirect)
    {
        echo '<html>
                <head>
                    <meta http-equiv="refresh" content="2; URL='.base_url().'index.php/sinkronisasi/download_mutasi_barang/'.$nofaktur.'">
                </head>
                <body>
                <script type="text/javascript" language="JavaScript">
                function Redirect() 
                {
                    window.location = "'.base_url().'index.php/sinkronisasi/'.$redirect.'";
                }
                window.onload = function() { setTimeout("Redirect()",2000); }
                </script>
            </body>
        </html>';
    }
    function download_mutasi_barang($nofaktur)
    {
        $data                       = $this->app_model->general();
        $data['nofaktur']           = 'O'.str_replace('-','',$nofaktur);
        $data['data']               = $this->barang_mutasi_model->get($data['nofaktur']);
        $result_array               = array();
        $nohdr                      = 0;
        foreach($data['data']->result() as $rowhdr)
        {
            $result_array[$nohdr]['no_faktur']  = $rowhdr->no_faktur;
            $result_array[$nohdr]['tgl']        = $rowhdr->tgl;
            $result_array[$nohdr]['ket']        = $rowhdr->ket;
            $result_array[$nohdr]['kd_gudang_asal'] = $rowhdr->kd_gudang_asal;
            $result_array[$nohdr]['kd_gudang_tujuan'] = $rowhdr->kd_gudang_tujuan;
            $result_array[$nohdr]['ref']        = $rowhdr->ref;
            $result_array[$nohdr]['st_dokumen'] = $rowhdr->st_dokumen;
            $data['datadtl']            = $this->barang_mutasi_model->get_dtl($data['nofaktur']);
            $nodtl                      = 0;
            if($data['datadtl']->num_rows() > 0)
            {
                foreach($data['datadtl']->result() as $rowdtl)
                {
                    $result_array[$nohdr]['datadetail'][$nodtl]['no_faktur']    = $rowdtl->no_faktur;
                    $result_array[$nohdr]['datadetail'][$nodtl]['kd_barang']    = $rowdtl->kd_barang;
                    //$result_array[$nohdr]['datadetail'][$nodtl]['nm_barang']    = $rowdtl->nm_barang;
                    //$result_array[$nohdr]['datadetail'][$nodtl]['kd_group']     = $rowdtl->kd_group;
                    $result_array[$nohdr]['datadetail'][$nodtl]['qty']          = $rowdtl->qty;
                    $result_array[$nohdr]['datadetail'][$nodtl]['satuan']       = $rowdtl->satuan;
                    $result_array[$nohdr]['datadetail'][$nodtl]['urutan']       = $rowdtl->urutan;
                    $result_array[$nohdr]['datadetail'][$nodtl]['imei']         = array();
                    
                    $data['dataimei']            = $this->barang_mutasi_model->get_dtl_imei($data['nofaktur'],$rowdtl->kd_barang);
                    $noimei = 0;
                    if($data['dataimei']->num_rows() > 0)
                    {
                        foreach($data['dataimei']->result() as $rowimei)
                        {
                            $result_array[$nohdr]['datadetail'][$nodtl]['imei'][$noimei]    = $rowimei->imei;
                            $noimei++;
                        }
                    }
                    
                    $nodtl++;
                } 
                $result_array[$nohdr]['datadetail'][$nodtl]['no_faktur']    = 'EOF';
                $result_array[$nohdr]['datadetail'][$nodtl]['kd_barang']    = 'EOF';
                $result_array[$nohdr]['datadetail'][$nodtl]['nm_barang']    = 'EOF';
                $result_array[$nohdr]['datadetail'][$nodtl]['kd_group']     = 'EOF';
                $result_array[$nohdr]['datadetail'][$nodtl]['qty']          = 0;
                $result_array[$nohdr]['datadetail'][$nodtl]['satuan']       = 'EOF';
                $result_array[$nohdr]['datadetail'][$nodtl]['urutan']       = 9999;
                $result_array[$nohdr]['datadetail'][$nodtl]['imei']         = array();
            }
            $nohdr++;
        }
        $data['json']               = json_encode($result_array);
        $pisahfaktur                = explode('-',$nofaktur);
        $namafile_awal              = 'TX-'.$result_array[0]['kd_gudang_asal'].'-'.$result_array[0]['kd_gudang_tujuan'].'-'.$pisahfaktur[2];
        $namafile                   = /*str_replace('#','',$nofaktur)*/$namafile_awal.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$data['json']);
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
    }
    function cetak_mutasi_barang($data='')
    {
        $inputdata      = $this->input->post('data');
        $dataglobal     = $this->app_model->general();
        $data           = ($this->input->post('data')!='') ? json_decode($inputdata,true) : $data;
        $cetak          = '';
        $handle         = printer_open($dataglobal['nama_printer']);
        printer_set_option($handle, PRINTER_MODE, "RAW");
        printer_start_doc($handle, "PrintKasir");
        printer_start_page($handle);
        for($i=0;$i<count($data);$i++)
        {
            $namagudangasal = $this->outlet_model->outlet_ambil($data[$i]['kd_gudang_asal'])->row()->outlet_nm;
            $namagudangtujuan = $this->outlet_model->outlet_ambil($data[$i]['kd_gudang_tujuan'])->row()->outlet_nm;
            $cetak      .= $this->app_model->maksimal(40,'SURAT JALAN MUTASI BARANG','tengah');
            $cetak      .= $this->app_model->maksimal(8,'Dari','kiri');
            $cetak      .= $this->app_model->maksimal(2,':','kiri');
            $cetak      .= $this->app_model->maksimal(30,$data[$i]['kd_gudang_asal'].$namagudangasal,'kanan');
            $cetak      .= $this->app_model->maksimal(8,'Kepada','kiri');
            $cetak      .= $this->app_model->maksimal(2,':','kiri');
            $cetak      .= $this->app_model->maksimal(30,'('.$data[$i]['kd_gudang_tujuan'].')'.$namagudangtujuan,'kanan');
            $cetak      .= $this->app_model->maksimal(20,'No.'.$data[$i]['no_faktur'],'kiri');
            $cetak      .= $this->app_model->maksimal(20,$data[$i]['tgl'],'kanan');
            $cetak      .= $this->app_model->garis_empatpuluh();
            for($j=0;$j<count($data[$i]['datadetail']);$j++)
            {
                $nmbarang = $this->barang_model->get($data[$i]['datadetail'][$j]['kd_barang'],'','','')->row()->barang_nm;
                if(count($data[$i]['datadetail'][$j]['imei'])>0)
                {
                    $jmhimei    = 0;
                    for($k=0;$k<count($data[$i]['datadetail'][$j]['imei']);$k++)
                    {
                        $kataimei = ($jmhimei=='0') ? 'IMEI:' : ' ';
                        $cetak  .= $this->app_model->maksimal(7,$kataimei,'kiri');
                        $cetak  .= $this->app_model->maksimal(33,$data[$i]['datadetail'][$j]['imei'][$jmhimei],'kiri');
                        $jmhimei++;
                    }
                }
                $cetak  .= $this->app_model->maksimal(40,$nmbarang,'kiri');
                $cetak  .= $this->app_model->maksimal(20,$data[$i]['datadetail'][$j]['kd_barang'],'kiri');
                $cetak  .= $this->app_model->maksimal(20,$data[$i]['datadetail'][$j]['qty'],'kanan');
            }
        }
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(20,'(yg menyerahkan)','tengah');
        $cetak          .= $this->app_model->maksimal(20,'(yg menerima)','tengah');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        printer_write($handle,$cetak);
        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);
        $redirect                   = ($this->input->post('sumber')!='') ? $this->input->post('sumber') : 'barang/mutasi_barang';
        redirect($redirect);
    }
    function terima_mutasi()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $faktur_akhir               = $this->barang_mutasi_model->get_max_faktur_dari_pusat()->row()->no_faktur;
        $faktur_akhir               = ($faktur_akhir=='') ? '0' : $faktur_akhir;
        $faktur_akhir_history       = $this->barang_mutasi_model->get_max_faktur_dari_pusat_history()->row()->no_faktur;
        $faktur_akhir_history       = ($faktur_akhir_history=='') ? '0' : $faktur_akhir_history;
        $data['faktur_akhir']       = ($faktur_akhir > $faktur_akhir_history) ? $faktur_akhir : $faktur_akhir_history;
        //echo $data['faktur_akhir']       ;exit();
//        $data['faktur_akhir']       = "TX130734266";
        $data['url']                = $this->app_model->system('service_url').'api/service_barang/barang_mutasi/id/'.$this->session->userdata('outlet_kd').'.'.$data['faktur_akhir'].'/format/json';
//        $data['url']                = 
        //echo $data['url'];
        $data['data']               = $this->curl->simple_get($data['url']);
        
        $data['data_array']         = json_decode($data['data'],true);
//        print_r($data['data_array']);die();
        //echo $data['url'];die();
        /* Simpan di File  */
        $namafile                   = 'TX'.trim($this->session->userdata('outlet_kd')).'@'.$this->session->userdata('tanggal').'_'.$this->session->userdata('shift').".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        if(file_exists($ourFileName))
        {
            unlink($ourFileName);
        }
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$data['data']);
        fclose($ourFileHandle);
        
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/display_mutasi';
        $data['judulweb']           = ' | Mutasi Barang';
        $this->load->view('layout/index',$data); 
    }
    function terima_mutasi_detail($nofaktur)
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        
//        $data['faktur_akhir']       = $this->barang_mutasi_model->get_max_faktur_dari_pusat()->row()->no_faktur;
//        $data['faktur_akhir']       = ($data['faktur_akhir']=='') ? '0' : $data['faktur_akhir'];
//        //$data['faktur_akhir']       = "TX120200001";
//        $data['url']                = $this->config->item('service_url').'api/service_barang/barang_mutasi/id/'.$this->session->userdata('outlet_kd').'.'.$data['faktur_akhir'].'/format/json';
//        $data['data']               = $this->curl->simple_get($data['url']);
//        $data_array                 = json_decode($data['data'],true);
        
        $ourFileName                = $data['base_upload'].'/TX'.$this->session->userdata('outlet_kd').'@'.$this->session->userdata('tanggal').'_'.$this->session->userdata('shift').'.txt';
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        
        $data['data']               = $dataFile;
        $data['data_array']         = json_decode($data['data'],true);
        $data_array                 = $data['data_array'];
        
        $hasil                      = array();
        $data['no_faktur']          = $nofaktur;
        $no                         = 0;
        $no_real                    = 0;
        for($no=0;$no<count($data_array);$no++)
        {
            if(trim($data_array[$no]['no_faktur'])==$nofaktur)
            {
                $no_real++;
                $hasil[$no]['no_faktur']    = $data_array[$no]['no_faktur'];
                $hasil[$no]['tgl']          = $data_array[$no]['tgl'];
                $hasil[$no]['ket']          = $data_array[$no]['ket'];
                $hasil[$no]['kd_gudang_asal'] = $data_array[$no]['kd_gudang_asal'];
                $hasil[$no]['kd_gudang_tujuan'] = $data_array[$no]['kd_gudang_tujuan'];
                $hasil[$no]['ref']          = $data_array[$no]['ref'];
                $hasil[$no]['st_dokumen']   = $data_array[$no]['st_dokumen'];
                if(count($data_array[$no]['datadetail'])>0)
                {
                    for($no2=0;$no2<count($data_array[$no]['datadetail']);$no2++)
                    {
                        $hasil[$no]['datadetail'][$no2]['no_faktur']    = $data_array[$no]['datadetail'][$no2]['no_faktur'];
                        $hasil[$no]['datadetail'][$no2]['kd_barang']    = $data_array[$no]['datadetail'][$no2]['kd_barang'];
                        $hasil[$no]['datadetail'][$no2]['nm_barang']    = $data_array[$no]['datadetail'][$no2]['nm_barang'];
                        $hasil[$no]['datadetail'][$no2]['kd_group']     = $data_array[$no]['datadetail'][$no2]['kd_group'];
                        $hasil[$no]['datadetail'][$no2]['qty']          = $data_array[$no]['datadetail'][$no2]['qty'];
                        $hasil[$no]['datadetail'][$no2]['satuan']       = $data_array[$no]['datadetail'][$no2]['satuan'];
                        $hasil[$no]['datadetail'][$no2]['urutan']       = $data_array[$no]['datadetail'][$no2]['urutan'];
                        if(count($data_array[$no]['datadetail'][$no2]['imei'])>0)
                        {
                            for($no3=0;$no3<count($data_array[$no]['datadetail'][$no2]['imei']);$no3++)
                            {
                                $hasil[$no]['datadetail'][$no2]['imei'][$no3] = $data_array[$no]['datadetail'][$no2]['imei'][$no3];
                            }
                        }
                    }
                    $hasil[$no]['datadetail'][$no2]['no_faktur']    = 'EOF';
                    $hasil[$no]['datadetail'][$no2]['kd_barang']    = 'EOF';
                    $hasil[$no]['datadetail'][$no2]['nm_barang']    = 'EOF';
                    $hasil[$no]['datadetail'][$no2]['kd_group']     = 'EOF';
                    $hasil[$no]['datadetail'][$no2]['qty']          = 0;
                    $hasil[$no]['datadetail'][$no2]['satuan']       = 'EOF';
                    $hasil[$no]['datadetail'][$no2]['urutan']       = 9999;
                    $hasil[$no]['datadetail'][$no2]['imei']         = array();
                }
            }
        }
                
        /* Simpan di File  */
        //$namafile                   = trim($this->session->userdata('outlet_kd')).'@harga'.".txt";
        //$hasil[$no_real]                 = "EOF";
        //print_r($hasil);die();
        $hasil                      = json_encode($hasil);
        //echo $hasil;die();
        $namafile                   = $nofaktur.'.txt';
        $ourFileName                = $data['base_upload'].$namafile;
        if(file_exists($ourFileName))
        {
            unlink($ourFileName);
        }
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$hasil);
        fclose($ourFileHandle);
        
        $data['data_arrays']         = $hasil;
        //echo $data['data_array'];
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/display_mutasi_detail';
        $data['judulweb']           = ' | Mutasi Barang Detail';
        $this->load->view('layout/index',$data); 
    }
    function kirim_mutasi()
    {
        $result                     = "";
        $tanggal                    = $this->session->userdata('tanggal');
        $query                      = $this->barang_mutasi_model->kirim_mutasi($tanggal);
        $data                       = json_encode($query);
        
        $this->curl->create($this->app_model->system('ka_service_url').'KirimMutasi'); 
        $this->curl->post($data); 
        $result = json_decode($this->curl->execute());
        
        if($result == 'SUKSES')  
        {  
            for($i=0;$i<count($query);$i++)
            {
                $datas['sinkronisasi']  = 'OK';
                $this->barang_mutasi_model->update($query[$i]['no_faktur'],$datas);
            }
            echo 'Proses Pengiriman Sukses!';  
        }  
        else  
        {  
            echo $result;
        } 
        
        //LAMA
//        $this->curl->create($this->app_model->system('service_url') . 'api/service_barang/mutasi/format/json');  
//        $this->curl->post($data);  
//
//        $result = json_decode($this->curl->execute());
//        
//        if(isset($result) && $result == 'sukses')  
//        {  
//            for($i=0;$i<count($query);$i++)
//            {
//                $datas['sinkronisasi']  = 'OK';
//                $this->barang_mutasi_model->update($query[$i]['no_faktur'],$datas);
//            }
//            echo 'Proses Pengiriman Sukses!';  
//        }  
//        else  
//        {  
//            echo $result;
//        }  
        //===END LAMA
    }
    
    function display_pelanggan()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['url']                = $this->app_model->system('service_url').'api/service_pelanggan/pelanggan/format/json';
        $data['data']               = $this->curl->simple_get($data['url']);
        $data['data_array']         = json_decode($data['data'],true);
        
        /* Simpan di File  */
        $namafile                   = trim($this->session->userdata('outlet_kd')).'@pelanggan'.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        if(file_exists($ourFileName))
        {
            unlink($ourFileName);
        }
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$data['data']);
        fclose($ourFileHandle);
        
        /* Buka dari File
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        */
        
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/display_pelanggan';
        $data['judulweb']           = ' | Daftar Pelanggan';
        $this->load->view('layout/index',$data);      
    }
    
    function display_promosi()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['url']                = $this->app_model->system('service_url').'api/service_promosi/teks_promosi/id/'.$this->session->userdata('outlet_kd').'.'.$this->session->userdata('tanggal').'/format/json';
        //echo $data['url'];echo $this->db->last-query();die();
        $data['data']               = $this->curl->simple_get($data['url']);
        $data['data_array']         = json_decode($data['data'],true);
        
        /* Simpan di File  */
        $namafile                   = trim($this->session->userdata('outlet_kd')).'@promosi'.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        if(file_exists($ourFileName))
        {
            unlink($ourFileName);
        }
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$data['data']);
        fclose($ourFileHandle);
        
        /* Buka dari File
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        */
        
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/display_promosi';
        $data['judulweb']           = ' | Teks Promosi';
        $this->load->view('layout/index',$data);      
    }
    
    function simpan_pelanggan()
    {
        $data                       = $this->app_model->general();
        $data['error']              = '';
        $data['hasil']              = false;
        /* Buka dari URL 
        $data['url']                = $this->config->item('service_url').'api/service_barang/barang_harga/id/'.$this->session->userdata('outlet_kd').'/format/json';
        $data['data']               = $this->curl->simple_get($data['url']);
        */
        /* Buka dari File */
        $ourFileName                = $data['base_upload'].'/'.$this->session->userdata('outlet_kd').'@pelanggan.txt';
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
    function simpan_promosi()
    {
        $data                       = $this->app_model->general();
        $data['error']              = '';
        $data['hasil']              = false;
        /* Buka dari URL 
        $data['url']                = $this->config->item('service_url').'api/service_barang/barang_harga/id/'.$this->session->userdata('outlet_kd').'/format/json';
        $data['data']               = $this->curl->simple_get($data['url']);
        */
        /* Buka dari File */
        $ourFileName                = $data['base_upload'].'/'.$this->session->userdata('outlet_kd').'@promosi.txt';
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        
        $data['data_array']         = json_decode($data['data'],true);
        
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        
        for($i=0;$i<count($data['data_array']);$i++)
        {
            $datainput['promosi_teks']           = $data['data_array'][$i]['teks_promosi'];
            if($this->promosi_model->update($datainput))
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
            echo '<script type="text/javascript">alert("Sukses Simpan Data Promosi!");parent.iclose();</script>';
        }
        else
        {
            echo '<script type="text/javascript">alert("Gagal Simpan Data Promosi!");history.go(-1);</script>';
        }
    }
    function display_biaya_kartu()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['url']                = $this->app_model->system('service_url').'api/service_biaya_kartu/biaya_kartu/format/json';
        $data['data']               = $this->curl->simple_get($data['url']);
        $data['data_array']         = json_decode($data['data'],true);

        /* Simpan di File  */
        $namafile                   = trim($this->session->userdata('outlet_kd')).'@biaya_kartu'.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        if(file_exists($ourFileName))
        {
            unlink($ourFileName);
        }
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$data['data']);
        fclose($ourFileHandle);

        /* Buka dari File
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        */
        
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/display_biaya_kartu';
        $data['judulweb']           = ' | Biaya Kartu';
        $this->load->view('layout/index',$data);
    }
    function simpan_biaya_kartu()
    {
        $data                       = $this->app_model->general();
        $data['error']              = '';
        $data['hasil']              = false;
        /* Buka dari URL 
        $data['url']                = $this->config->item('service_url').'api/service_barang/barang_harga/id/'.$this->session->userdata('outlet_kd').'/format/json';
        $data['data']               = $this->curl->simple_get($data['url']);
        */
        /* Buka dari File */
        $ourFileName                = $data['base_upload'].'/'.$this->session->userdata('outlet_kd').'@biaya_kartu.txt';
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        $datas                      = array();
        $data['data_array']         = json_decode($data['data'],true);
        
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        
        for($i=0;$i<count($data['data_array']);$i++)
        {
            $this->db->where('sys_col','biaya_kartu');
            $col                    = $this->db->get('sys_var');
            if($col->num_rows()>0)
            {
                $datas['sys_val']    = $data['data_array'][$i]['sys_val'];
                $this->db->where('sys_col','biaya_kartu');
                $this->db->update('sys_var',$datas);
            }
            else
            {
                $datas['sys_col']    = 'biaya_kartu';
                $datas['sys_val']    = $data['data_array'][$i]['sys_val'];
                $this->db->insert('sys_var',$datas);
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
            echo '<script type="text/javascript">alert("Sukses Simpan Biaya Kartu!");parent.iclose();</script>';
        }
        else
        {
            echo '<script type="text/javascript">alert("Gagal Simpan Biaya Kartu!");history.go(-1);</script>';
        }
    }
    function display_group_barang()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['url']                = $this->app_model->system('service_url').'api/service_barang/barang_group/format/json';
        $data['data']               = $this->curl->simple_get($data['url']);
        $data['data_array']         = json_decode($data['data'],true);
        //print_r($data['data_array']);
        /* Simpan di File  */
        $namafile                   = trim($this->session->userdata('outlet_kd')).'@group_barang'.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        if(file_exists($ourFileName))
        {
            unlink($ourFileName);
        }
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$data['data']);
        fclose($ourFileHandle);
        
        /* Buka dari File
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        */
        
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/display_group_barang';
        $data['judulweb']           = ' | Group Barang';
        $this->load->view('layout/index',$data);      
    }
    function simpan_group_barang()
    {
        $data                       = $this->app_model->general();
        $data['error']              = '';
        $data['hasil']              = false;
        /* Buka dari URL 
        $data['url']                = $this->config->item('service_url').'api/service_barang/barang_harga/id/'.$this->session->userdata('outlet_kd').'/format/json';
        $data['data']               = $this->curl->simple_get($data['url']);
        */
        /* Buka dari File */
        $ourFileName                = $data['base_upload'].'/'.$this->session->userdata('outlet_kd').'@group_barang.txt';
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        
        $data['data_array']         = json_decode($data['data'],true);
        
        $this->db->trans_begin();
        for($i=0;$i<count($data['data_array']);$i++)
        {
            $datainput['group_kd']          = trim($data['data_array'][$i]['kd_group']);
            $datainput['group_nm']          = trim($data['data_array'][$i]['nm_group']);
            $datainput['group_hp']          = (int)$data['data_array'][$i]['hp'];
            $datainput['group_elektrik']    = (int)$data['data_array'][$i]['elektrik'];
            $datainput['group_coa_hpp']     = trim($data['data_array'][$i]['coa_hpp']);
            $datainput['group_coa_penjualan']= trim($data['data_array'][$i]['coa_penjualan']);
            $datainput['group_coa_persediaan']= trim($data['data_array'][$i]['coa_persediaan']);
            $datainput['group_coa_piutang'] = trim($data['data_array'][$i]['coa_piutang']);
            $datainput['group_coa_diskon']  = trim($data['data_array'][$i]['coa_diskon']);
            $datainput['group_coa_diskon_pembelian']= trim($data['data_array'][$i]['coa_diskon_pembelian']);
            $datainput['group_jn_group']    = ($data['data_array'][$i]['hp']=='1') ? 'HP' : '';
            //print_r($datainput);die();
            if($this->group_barang_model->hapus($datainput['group_kd']))
            {
                if($this->group_barang_model->simpan($datainput))
                {
                    $data['hasil']          = true;
                }
                else
                {
                    $data['error']          .= 'Error Pada Simpan Grup Barang!';
                }
            }
            else
            {
                $data['error']              .= 'Error Pada Hapus Grup Barang!';
            }
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo '<script type="text/javascript">alert("Gagal Sinkronisasi Grup Barang!");history.go(-1);</script>';
            die();
        }
        else
        {
            $this->db->trans_commit();
            $dataarray['log_col']                   = 'Sinkronisasi Grup Barang';
            $dataarray['log_val']                   = 'SUKSES';
            $dataarray['tgl']                       = $this->session->userdata('tanggal').' '.date('H:i:s');
            $dataarray['shift']                     = $this->session->userdata('shift');
            $dataarray['tipe']                      = 'GRUP BARANG';
            $dataarray['uid']                       = $this->session->userdata('user_kd');
            $dataarray['tgl_tambah']                = date('Y-m-d H:i:s');
            $this->log_proses_model->simpanLogJual($dataarray);
            echo '<script type="text/javascript">alert("Sukses Sinkronisasi Grup Barang!");parent.iclose();</script>';
            die();
        }
    }
    
    function display_karyawan()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                       = $this->app_model->general();
        $data['url']                = $this->app_model->system('ka_service_url').'DfKaryawan';
        $data['data']               = $this->curl->simple_get($data['url']);
        
        $tmp = json_decode($data['data'],true);
        $tmp = $tmp['DfKaryawanResult'];
        
        $data['data_array']         = json_decode($tmp,true);
        if (array_key_exists('eof',end($data['data_array']))) {
            unset($data['data_array'][count($data['data_array'])-1]);
        }
        else
        {
            echo "Terjadi Error pada Saluran Internet, Silakan Diulangi Kembali!";
            die();
        }

        /* Simpan di File  */
        $namafile                   = trim($this->session->userdata('outlet_kd')).'@karyawan'.".txt";
        $ourFileName                = $data['base_upload'].$namafile;
        if(file_exists($ourFileName))
        {
            unlink($ourFileName);
        }
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        fwrite($ourFileHandle,$data['data']);
        fclose($ourFileHandle);
               
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'sinkronisasi/display_karyawan';
        $data['judulweb']           = ' | Daftar Karyawan';
        $this->load->view('layout/index',$data);      
    }
    
    function simpan_karyawan()
    {
        $data                       = $this->app_model->general();
        $data['error']              = '';
        $data['hasil']              = false;

        /* Buka dari File */
        $ourFileName                = $data['base_upload'].'/'.$this->session->userdata('outlet_kd').'@karyawan.txt';
        $ourFileHandle              = fopen($ourFileName, 'r') or die("can't open file");
        $dataFile                   = file_get_contents($ourFileName, FILE_USE_INCLUDE_PATH);
        $data['data']               = $dataFile;
        
        $tmp = json_decode($data['data'],true);
        $tmp = $tmp['DfKaryawanResult'];
        $data['data_array']         = json_decode($tmp,true);
        
        if (array_key_exists('eof',end($data['data_array']))) {
            unset($data['data_array'][count($data['data_array'])-1]);
        }
        else
        {
            echo "Terjadi Error pada Saluran Internet, Silakan Diulangi Kembali!";
            die();
        }
        
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        $this->karyawan_model->hapus('');
        for($i=0;$i<count($data['data_array']);$i++)
        {
            $datainput['nik']               = $data['data_array'][$i]['nik'];
            $datainput['nm_lengkap']        = $data['data_array'][$i]['nm_lengkap'];
            $datainput['alamat']            = $data['data_array'][$i]['alamat'];
            $datainput['kota']              = $data['data_array'][$i]['kota'];
            $datainput['propinsi']          = $data['data_array'][$i]['propinsi'];
            $datainput['telp']              = $data['data_array'][$i]['telp'];
            $datainput['hp']                = $data['data_array'][$i]['hp'];
            $datainput['email']             = $data['data_array'][$i]['email'];
            $datainput['pwd_absen']         = $data['data_array'][$i]['pwd_absen'];
            if($this->karyawan_model->simpan($datainput))
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
            echo '<script type="text/javascript">alert("Sukses Simpan Data Karyawan!");parent.iclose();</script>';
        }
        else
        {
            echo '<script type="text/javascript">alert("Gagal Simpan Data Karyawan!");history.go(-1);</script>';
        }
    }
    
    function kirim_absen()  
    {  
        $this->load->model('absen_model','absen');

        $tgl                        = $this->session->userdata('tanggal');
        $shift                      = $this->session->userdata('shift');
        $qry                        = $this->absen->get($tgl,$shift);

        $this->curl->create($this->app_model->system('ka_service_url').'KirimAbsen');  
        $this->curl->post(json_encode($qry->result_array()));  
        $result = json_decode($this->curl->execute());  

        if(isset($result) && $result == 'SUKSES')  
        {  
            echo 'Proses Pengiriman Sukses!';  
        }  
        else  
        {  
            echo 'Terjadi Kesalahan pada Proses Pengiriman!';
        }  
    } 
    
    function adn_simpan_mutasi_file()
    {
        $data                       = array();
        $hasil                      = false;
        $data['barangbaru']         = '';
        $data['error']              = '';
        $data['sumber']             = $this->input->post('sumber');
        $data['data']               = $this->input->post('data');
        $data['no_faktur']          = $this->input->post('no_faktur');
        $data['perfaktur']          = $this->input->post('data_array');
        $dataperfaktur              = json_decode($data['perfaktur'],true);
        $data_array                 = json_decode($data['data'],true);
//        print_r($data_array);die();
//        print_r($dataperfaktur);die();
        natsort($dataperfaktur);
        
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        for($i=0;$i<count($data_array);$i++)
        {
            $datainput['no_faktur'] = $data_array[$i]['no_faktur'];
            
            //echo $datainput['no_faktur'] .  'test' . trim($data['no_faktur']);die();
            if(trim($datainput['no_faktur'])!=trim($data['no_faktur']))
            {
                $data['error']                          .= 'Pilih data yang awal terlebih dahulu!';
                echo '<script type="text/javascript">alert("'.$data['error'].'");history.go(-1);</script>';
                exit();
                break;
            }
            else
            {
                //$datainput['tgl']       = $data_array[$i]['tgl'];
                $datainput['tgl']       = $dataperfaktur[$i]['tgl'];
                //$datainput['ket']       = $data_array[$i]['ket'];
                $datainput['ket']       = $dataperfaktur[$i]['ket'];
                //$datainput['dfsdfsdf']       = $dataperfaktur[$i]['ket'];
                //$datainput['kd_gudang_asal'] = $data_array[$i]['kd_gudang_asal'];
                $datainput['kd_gudang_asal'] = $dataperfaktur[$i]['kd_gudang_asal'];
                //$datainput['kd_gudang_tujuan'] = $data_array[$i]['kd_gudang_tujuan'];
                $datainput['kd_gudang_tujuan'] = $dataperfaktur[$i]['kd_gudang_tujuan'];
                //$datainput['ref']       = $data_array[$i]['ref'];
                $datainput['ref']       = $dataperfaktur[$i]['ref'];                
                //$datainput['st_dokumen'] = $data_array[$i]['st_dokumen'];
                $datainput['st_dokumen'] = $dataperfaktur[$i]['st_dokumen'];   
                
                if(count($dataperfaktur[$i]['datadetail'])>0)
                {
                    if($this->barang_mutasi_model->simpan($datainput))
                    {
                        for($j=0;$j<count($dataperfaktur[$i]['datadetail']);$j++)
                        {
                            if($j==(count($dataperfaktur[$i]['datadetail'])-1))
                            {
                                if($dataperfaktur[$i]['datadetail'][$j]['no_faktur']=='EOF')
                                {
                                    $hasil = true;
                                }
                                else
                                {
                                    $hasil = false;
                                }
                                break;
                            }
                            $datainputdtl['no_faktur']  = $dataperfaktur[$i]['datadetail'][$j]['no_faktur'];
                            $datainputdtl['kd_barang']  = $dataperfaktur[$i]['datadetail'][$j]['kd_barang'];
                            $datainputdtl['qty']        = $dataperfaktur[$i]['datadetail'][$j]['qty'];
                            $datainputdtl['satuan']     = $dataperfaktur[$i]['datadetail'][$j]['satuan'];
                            $datainputdtl['urutan']     = $dataperfaktur[$i]['datadetail'][$j]['urutan'];
                            if($this->barang_mutasi_model->simpan_dtl($datainputdtl))
                            {
                                $databrg                = $this->barang_model->get($dataperfaktur[$i]['datadetail'][$j]['kd_barang'],'','','','','');
                                if($databrg->num_rows() == 0)
                                {
                                    $databarang['barang_kd']    = $dataperfaktur[$i]['datadetail'][$j]['kd_barang'];
                                    $databarang['barang_nm']    = $dataperfaktur[$i]['datadetail'][$j]['nm_barang'];
                                    $databarang['barang_group'] = $dataperfaktur[$i]['datadetail'][$j]['kd_group'];
                                    $this->barang_model->simpan($databarang);
                                    $data['barangbaru']         = 'Ada Barang Baru!';
                                }
                                if(count($dataperfaktur[$i]['datadetail'][$j]['imei'])>0)
                                {
                                    $datainputdtlimei['kd_barang']  = $dataperfaktur[$i]['datadetail'][$j]['kd_barang'];
                                    $datainputdtlimei['no_faktur']  = $dataperfaktur[$i]['datadetail'][$j]['no_faktur'];
                                    for($k=0;$k<count($dataperfaktur[$i]['datadetail'][$j]['imei']);$k++)
                                    {
                                        $datainputdtlimei['imei']   = $dataperfaktur[$i]['datadetail'][$j]['imei'][$k];
                                        $this->barang_mutasi_model->simpan_dtl_imei($datainputdtlimei);
                                        $cekimei                    = $this->barang_imei_model->get($datainputdtlimei['kd_barang'],$datainputdtlimei['imei'],'');
//                                        echo $this->db->last_query();
                                        if($cekimei->num_rows() > 0)
                                        {
                                            $imeibarang['imei_barang']      = $datainputdtlimei['kd_barang'];
                                            $imeibarang['imei_no']          = $datainputdtlimei['imei'];
                                            $imeibarang['imei_ref']         = $datainputdtlimei['no_faktur'];
                                            $imeibarang['imei_status']      = 1;
                                            $imeibarang['uid_edit']         = $this->session->userdata('user_kd');
                                            $imeibarang['doe_edit']         = $this->session->userdata('tanggal').' '.date('H:i:s');
                                            $this->barang_imei_model->update($datainputdtlimei['kd_barang'],$datainputdtlimei['imei'],$imeibarang);
                                        }
                                        else
                                        {
                                            $imeibarang['imei_barang']      = $datainputdtlimei['kd_barang'];
                                            $imeibarang['imei_no']          = $datainputdtlimei['imei'];
                                            $imeibarang['imei_ref']         = $datainputdtlimei['no_faktur'];
                                            $imeibarang['imei_status']      = 1;
                                            $imeibarang['uid']              = $this->session->userdata('user_kd');
                                            $imeibarang['doe']              = $this->session->userdata('tanggal').' '.date('H:i:s');
                                            $this->barang_imei_model->simpan($imeibarang);
                                        }
                                    }
                                }
                            }
                            else
                            {
                                $data['error']          .= 'Ada Kesalahan data!\r\n';
                            }
                        }
                    }
                }
                break;
            }
        }
//        if($dataperfaktur[1]=='EOF')
//        {
//            $hasil              = true;
//        }
//        else
//        {
//            $data['error']      .= 'Tidak mencapai akhir dari file,tutup jendela ini, lalu tekan F5.\r\n Ulangi Proses!\r\n';
//            $hasil              = false;
//        }
        if ($this->db->trans_status() === FALSE) //CEK JIKA GAGAL
        {
            $this->db->trans_rollback(); //ROLLBACK
            echo '<script type="text/javascript">alert("'.$data['error'].'");history.go(-1);</script>';
        }
        else
        {
            if($hasil==false)
            {
                $this->db->trans_rollback();
                $data['error']      = 'Tidak mencapai akhir dari file,tutup jendela ini, lalu tekan F5.\r\n Ulangi Proses!\r\n';
                echo '<script type="text/javascript">alert("'.$data['error'].'");history.go(-1);</script>';
            }
            else
            {
                $this->db->trans_commit(); //DILAKUKAN
                $alertbarang            = ($data['barangbaru']!='') ? 'alert("Ada Barang Baru, harap Update Harga!");' : '';
                if($data['sumber']=='server')
                {
                    echo '<script type="text/javascript">window.opener.segarkembali();window.close();</script>';
                }
                else
                {
                    echo '<script type="text/javascript">alert("Berhasil simpan data!");'.$alertbarang.'parent.iclose();</script>';
                }
            }
        }
    }
}
