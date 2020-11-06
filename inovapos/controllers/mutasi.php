<?php

class Mutasi extends CI_Controller
{
    function __construct()
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
        $this->load->model('promosi_model');
        $this->load->model('log_proses_model');
    }
    function daftar()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                           = $this->app_model->general();
        $data['option_tampilan']        = 'tanpa_menu';
        $data['halaman']                = 'mutasi/index';
        $data['data']                   = $this->barang_mutasi_model->get_history_notsync('');
        //echo $this->db->last_query();   
        $this->load->view('layout/index',$data);
    }
    function kirim_mutasi($tgl)
    {
        $tanggal                    = $tgl;
        $query                      = $this->barang_mutasi_model->kirim_mutasi_history($tanggal);
        $data                       = json_encode($query);
        //die(print_r($query,true));
        $this->curl->create($this->app_model->system('service_url') . 'api/service_barang/mutasi/format/json');  
        $this->curl->post($data);  

        $result = json_decode($this->curl->execute());
        
        if(isset($result) && $result == 'sukses')  
        {  
            for($i=0;$i<count($query);$i++)
            {
                $datas['sinkronisasi']  = 'OK';
                $this->barang_mutasi_model->update_history($query[$i]['no_faktur'],$datas);
            }
            echo 'Proses Pengiriman Sukses!';  
        }  
        else  
        {  
            echo $result;
            //echo 'Proses Pengiriman sudah dilakukan atau ada error ketika dikirim!';
        }  
    }
    function lihat_mutasi($tgl)
    {
        $data                       = $this->app_model->general();
        $data_all                   = $this->barang_mutasi_model->get_history_hdr($tgl);
        $data['data_array']         = array();
        $i                          = 0;
        if($data_all->num_rows()>0)
        {
            foreach($data_all->result() as $row)
            {
                $data_hdr                                       = $this->barang_mutasi_model->get_history_hdr('',$row->no_faktur)->row();
                $data['data_array'][$i]['no_faktur']            = $data_hdr->no_faktur;
                $data['data_array'][$i]['tgl']                  = $data_hdr->tgl;
                $data['data_array'][$i]['kd_gudang_asal']       = $data_hdr->kd_gudang_asal;
                $data['data_array'][$i]['kd_gudang_tujuan']     = $data_hdr->kd_gudang_tujuan;
                $data['data_array'][$i]['ref']                  = $data_hdr->ref;
                $data['data_array'][$i]['ket']                  = $data_hdr->ket;
                $data['data_array'][$i]['st_dokumen']           = $data_hdr->st_dokumen;
                $data['data_array'][$i]['datadetail']           = array();
                $data_dtl                                       = $this->barang_mutasi_model->get_history_dtl($row->no_faktur);
                if($data_dtl->num_rows()>0)
                {
                    $j              = 0;
                    foreach($data_dtl->result() as $rowdtl)
                    {
                        $data['data_array'][$i]['datadetail'][$j]['no_faktur']  = $rowdtl->no_faktur;
                        $data['data_array'][$i]['datadetail'][$j]['kd_barang']  = $rowdtl->kd_barang;
                        $data['data_array'][$i]['datadetail'][$j]['qty']        = $rowdtl->qty;
                        $data['data_array'][$i]['datadetail'][$j]['satuan']     = $rowdtl->satuan;
                        $data['data_array'][$i]['datadetail'][$j]['urutan']     = $rowdtl->urutan;
                        $data['data_array'][$i]['datadetail'][$j]['imei']       = array();
                        $dataimei                                               = $this->barang_mutasi_model->get_history_dtl_imei($row->no_faktur,$rowdtl->kd_barang);
                        if($dataimei->num_rows()>0)
                        {
                            $k = 0;
                            foreach($dataimei->result() as $rowimei)
                            {
                                $data['data_array'][$i]['datadetail'][$j]['imei'][$k] = $rowimei->imei;
                                $k++;
                            }
                        }
                        $j++;
                    }
                }
                $i++;
            }
        }
        //print_r($data['data_array']);die();
        $data['data']               = json_encode($data['data_array']);
        //$data['no_faktur']          = $nofaktur;
        $data['option_tampilan']    = 'tanpa_menu';
        $data['halaman']            = 'mutasi/daftar_mutasi_detail';
        $data['judulweb']           = ' | Mutasi Barang Detail';
        $this->load->view('layout/index',$data); 
    }
    
    
/**
 *                 $data['data_hdr'][$i]       = $this->barang_mutasi_model->get_history_hdr($tgl,$row->no_faktur);
 *                 //echo $this->db->last_query();die();
 *                 $seqhdr                     = 0;
 *                 foreach($data['data_hdr'][$i]->result() as $rowhdr)
 *                 {
 *                     //if($rowhdr->kd_gudang_asal == $this->session->userdata('outlet_kd'))
 *                     //{
 *                         $data['data_array'][$i][$seqhdr]['no_faktur']   = $rowhdr->no_faktur;
 *                         $data['data_array'][$i][$seqhdr]['tgl']         = $rowhdr->tgl;
 *                         $data['data_array'][$i][$seqhdr]['kd_gudang_asal'] = $rowhdr->kd_gudang_asal;
 *                         $data['data_array'][$i][$seqhdr]['kd_gudang_tujuan'] = $rowhdr->kd_gudang_tujuan;
 *                         $data['data_array'][$i][$seqhdr]['ref']         = $rowhdr->ref;
 *                         $data['data_array'][$i][$seqhdr]['ket']         = $rowhdr->ket;
 *                         $data['data_array'][$i][$seqhdr]['st_dokumen']  = $rowhdr->st_dokumen;
 *                         $data['data_array'][$i][$seqhdr]['datadetail']  = array();
 *                         $data['data_dtl']       = $this->barang_mutasi_model->get_history_dtl($rowhdr->no_faktur);
 *                         if($data['data_dtl']->num_rows() > 0)
 *                         {
 *                             $seqdtl                                 = 0;
 *                             foreach($data['data_dtl']->result() as $rowdtl)
 *                             {
 *                                 $data['data_array'][$i][$seqhdr]['datadetail'][$seqdtl]['no_faktur']    = $rowdtl->no_faktur;
 *                                 $data['data_array'][$i][$seqhdr]['datadetail'][$seqdtl]['kd_barang']    = $rowdtl->kd_barang;
 *                                 $data['data_array'][$i][$seqhdr]['datadetail'][$seqdtl]['qty']          = $rowdtl->qty;
 *                                 $data['data_array'][$i][$seqhdr]['datadetail'][$seqdtl]['satuan']       = $rowdtl->satuan;
 *                                 $data['data_array'][$i][$seqhdr]['datadetail'][$seqdtl]['urutan']       = $rowdtl->urutan;
 *                                 $data['data_array'][$i][$seqhdr]['datadetail'][$seqdtl]['imei']         = array();
 *                                 
 *                                 //$data['data_imei']                                                  = $this->barang_imei_model->get($rowdtl->kd_barang,'','',$rowhdr->no_faktur);
 *                                 $data['data_imei']                                                  = $this->barang_mutasi_model->get_dtl_imei($rowhdr->no_faktur,$rowdtl->kd_barang);
 *                                 $seqimei                                                            = 0;
 *                                 if($data['data_imei']->num_rows() > 0)
 *                                 {
 *                                     foreach($data['data_imei']->result() as $rowimei)
 *                                     {
 *                                         $data['data_array'][$i][$seqhdr]['datadetail'][$seqdtl]['imei'][$seqimei] = $rowimei->imei;
 *                                         $seqimei++;
 *                                     }   
 *                                 }
 *                                 $seqdtl++;
 *                             }
 *                         }
 *                         $seqhdr++;
 *                     //}
 *                 }
 */
}