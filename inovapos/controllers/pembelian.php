<?php
class Pembelian extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('group_barang_model');
        $this->load->model('barang_imei_model');
        $this->load->model('barang_model');
        $this->load->model('barang_elektrik_model');
        $this->load->model('kasir_elektrik_model');
        $this->load->model('pelanggan_model');
        $this->load->model('karyawan_model');
        $this->load->model('pembelian_model');
        $this->load->model('outlet_model');
        $this->load->model('user_model');
        $this->load->model('promosi_model');
        $this->load->model('log_proses_model');
        $this->load->model('sys_var_model');
        
        if($this->log_proses_model->cekTutupShift($this->session->userdata('tanggal'),$this->session->userdata('shift')))
        {
            redirect('');
        }

    }
    function index()
    {     
        if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=='SPV')
        {
            redirect('');
        }
        $data                           = $this->app_model->general();
        $data['option_tampilan']        = 'tanpa_menu';
        $data['halaman']                = 'kasir/index';
        $this->load->view('layout/index',$data);
    }
    function pembelian_temp()
    {     
        if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=='SPV')
        {
            redirect('');
        }
        $data                           = $this->app_model->general();
        $data['option_tampilan']        = 'tanpa_menu';
        $data['halaman']                = 'pembelian/index_temp_pelanggan';
        $this->load->view('layout/index',$data);
    }
    function simpan()
    {
        
        $res= json_decode($this->input->post('data'),true);
        if($res == null)
        {
            die('E#Data Kosong!');
        }

        $error                  = '';     
        $etgl                   = explode('-',$this->session->userdata('tanggal'));
        $tgls                   = substr($etgl[0],2,2).$etgl[1];
        $tglsekarang            = substr($etgl[0],2,2).$etgl[1].$etgl[2];
        $kd_akhir               = $this->pembelian_model->get_kdakhir($tglsekarang);
        $kd_akhir_history       = $this->pembelian_model->get_kdakhirhistory($tglsekarang);
        $fakturkdakhir          = ''; 
        if($kd_akhir > $kd_akhir_history)
        {
            $fakturkdakhir      = $kd_akhir;
        }
        else
        {
            $fakturkdakhir      = $kd_akhir_history;
        }
        
        $data['kd_outlet']      = $this->session->userdata('outlet_kd');
        
        $data['no_faktur']      = 'OSI'.$tglsekarang.$fakturkdakhir;   
        $data['tgl']            = $this->session->userdata('tanggal').' '.date('H:i:s');
        $data['shift']          = $this->session->userdata('shift');
        
        $data['kd_pelanggan']   = "";
        $data['ket']            = "";
        $data['nik']            = $this->session->userdata('user_kd');
        
        $data['kd_term']        = 0;
        $data['nomor_dk']       = $res['nomor_kartu'];
        $data['jmh']            = str_replace('.','',$res['jmh_belanja']);
        $data['diskon_p']       = str_replace('.','',$res['diskon_p']);
        $data['diskon_nominal'] = str_replace('.','',$res['diskon_nominal']);
        $data['pajak']          = 0;
        $data['biaya_kirim']    = str_replace('.','',$res['biaya_kirim']);
        $data['total']          = str_replace('.','',$res['total_belanja']);
        $data['tunai']          = str_replace('.','',$res['jmh_uang']);
        $data['kembali']        = str_replace('.','',$res['jmh_kembali']);
        $data['lunas']          = 0;
        
        $data['jmh_debet']      = str_replace('.','',$res['jmh_debet']);
        $data['jmh_kredit']     = str_replace('.','',$res['jmh_kredit']);
        $data['jmh_tunai']      = str_replace('.','',$res['jmh_tunai']);
        $data['jmh_biaya_kartu'] = str_replace('.','',$res['biaya_kartu']);
        $dk                     = $res['dk'];
        
        //Validasi Ulang!
        if($dk=='Kredit')
        {
            $data['jmh_debet']  = 0;
            $data['jmh_kredit'] = $data['jmh_kredit'];
        }

        if($dk=='Debit')
        {
            $data['jmh_debet']  = $data['jmh_debet'];
            $data['jmh_kredit'] = 0;
        }
        //end validasi ulang
        
        //Pilih Array Barang
        foreach ($res as $item => $assoc) 
        {
            if ($item=='rows')
            {
                $items = $res['rows']; 
            }
        }
        
        //-- end pilih array barang       
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        if($this->pembelian_model->simpan($data)) //Simpan Header Transaksi
        {
            $seq                    = 0;
            $i                      = 0;
            foreach ($items as $item => $baris) 
            {
                if($baris!=null)
                {
                    $kdBarang       = $baris[1];
                    $nmBarang       = $baris[2];
                    $qty            = (float)str_replace('.','',$baris[3]);
                    $harga          = (float)str_replace('.','',$baris[4]);
                    $diskonPersen   = (float)str_replace('.','',$baris[5]);
                    $jmh            = $qty * ($harga - ($harga*$diskonPersen/100)); //Validasi Jumlah 
                    
                    $n              = strpos($nmBarang,"IMEI");
                    $koleksiIMEI    = array();
                    if ($n > 0)
                    {
                        $strIMEI    = substr($nmBarang,$n+5);
                        $koleksiIMEI = explode('#',trim($strIMEI));
                    }
                    
                    if($kdBarang != '' && $nmBarang != '' && $qty > 0 && $harga > 0) //Memastikan Barang Adalah Valid (Kode dan Nama Barang Harus Ada)
                    {              
                        $datadtl['no_faktur']   = $data['no_faktur'];
                        $datadtl['kd_barang']   = $kdBarang;
                        $datadtl['urutan']      = $seq;
                        $datadtl['qty']         = $qty;
                        $datadtl['satuan']      = "";
                        $datadtl['harga']       = $harga;
                        $datadtl['diskon_p']    = $diskonPersen;
                        $datadtl['pajak_p']     = 0;
                        $datadtl['jmh']         = $jmh;
                                
                        /*
                         * Data untuk di print
                         */
                        $data['detail'][$i]['no_faktur']    = $datadtl['no_faktur'];
                        $data['detail'][$i]['kd_barang']    = $datadtl['kd_barang'];
                        $data['detail'][$i]['urutan']       = $datadtl['urutan'];
                        $data['detail'][$i]['qty']          = $datadtl['qty'];
                        $data['detail'][$i]['satuan']       = $datadtl['satuan'];
                        $data['detail'][$i]['harga']        = $datadtl['harga'];
                        $data['detail'][$i]['diskon_p']     = $datadtl['diskon_p'];
                        $data['detail'][$i]['pajak_p']      = $datadtl['pajak_p'];
                        $data['detail'][$i]['jmh']          = $datadtl['jmh'];
                        $data['detail'][$i]['imei']         = array();
                        
                        if($this->pembelian_model->simpan_dtl($datadtl))
                        {
                            for($x=0;$x<count($koleksiIMEI);$x++)
                            {
                                $dataimei['no_faktur']      = $datadtl['no_faktur'];
                                $dataimei['kd_barang']      = $datadtl['kd_barang'];
                                $dataimei['imei']           = $koleksiIMEI[$x];
                                $dataimei['urutan']         = $datadtl['urutan'];
                                
                                $this->pembelian_model->simpan_dtl_imei($dataimei);
                                
                                $cekimei                    = $this->barang_imei_model->get($datadtl['kd_barang'],$koleksiIMEI[$x],'');
                                if($cekimei->num_rows() > 0)
                                {
                                    $imeibarang['imei_barang']  = $datadtl['kd_barang'];
                                    $imeibarang['imei_no']      = $koleksiIMEI[$x];
                                    $imeibarang['imei_ref']     = $data['no_faktur'];
                                    $imeibarang['imei_status']  = 0;
                                    $this->barang_imei_model->update($datadtl['kd_barang'],$koleksiIMEI[$x],$imeibarang);
                                }
                                else
                                {
                                    $imeibarang['imei_barang']  = $datadtl['kd_barang'];
                                    $imeibarang['imei_no']      = $koleksiIMEI[$x];
                                    //$imeibarang['imeis']      = $koleksiIMEI[$x];
                                    $imeibarang['imei_ref']     = $datadtl['no_faktur'];
                                    $imeibarang['imei_status']  = 0;
                                    $this->barang_imei_model->simpan($imeibarang);
                                }
                                
                                if($koleksiIMEI[$x]!='')
                                {
                                    $data['detail'][$i]['imei'][$x] = $koleksiIMEI[$x];
                                }
                                
                            }
                        }
                    }
                    else
                    {
                        $error              = 'Ada kesalahan pada Data Detail!';
                        break;
                    }
                    $i++;
                    $seq++;
                }
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
        if($error!='')
        {
            echo 'E#'.$error;
        }
        else
        {
            echo 'S#'.$data['no_faktur'];
        }
    }
    function simpan_temp()
    {
        $res= json_decode($this->input->post('data'),true);
//        $datadetail = $res['rows'];
//        if(count($datadetail)>0)
//        {
//            for($i=1;$i<count($datadetail);$i++)
//            {
//                $datadtl['kd_barang'] = $datadetail[$i][0];
//                $nmBarang       = $datadetail[$i][1];
//                $koleksiIMEI    = explode('#',$nmBarang);
//                for($j=1;$j<count($koleksiIMEI);$j++)
//                {
//                    $imei = str_replace("\n",'',$koleksiIMEI[$j]);
//                    $cekimei                    = $this->barang_imei_model->get($datadtl['kd_barang'],$imei,'');
//                    if($cekimei->num_rows() > 0)
//                    {
//                        echo $this->db->last_query();
//                    }
//                    else
//                    {
//                        echo $this->db->last_query().'gakada';
//                    }
//                }
//            }
//        }
        if($res == null)
        {
            die('E#Data Kosong!');
        }
        //die();
        $error                  = '';     
        $etgl                   = explode('-',$this->session->userdata('tanggal'));
        $tgls                   = substr($etgl[0],2,2).$etgl[1];
        $tglsekarang            = substr($etgl[0],2,2).$etgl[1].$etgl[2];
        $kd_akhir               = $this->pembelian_model->get_kdakhir($tglsekarang);
        $kd_akhir_history       = $this->pembelian_model->get_kdakhirhistory($tglsekarang);
        $fakturkdakhir          = ''; 
        if($kd_akhir > $kd_akhir_history)
        {
            $fakturkdakhir      = $kd_akhir;
        }
        else
        {
            $fakturkdakhir      = $kd_akhir_history;
        }
        $data['kd_outlet']      = $this->session->userdata('outlet_kd');
        $data['no_faktur']      = 'OSI'.$tglsekarang.$fakturkdakhir; 
        $data['tgl']            = $this->session->userdata('tanggal').' '.date('H:i:s');
        $data['shift']          = $this->session->userdata('shift');
        $data['kd_pelanggan']   = (int)"0";
        $data['ket']            = "";
        $data['nik']            = $this->session->userdata('user_kd');
        $data['kd_term']        = 0;
        $data['nomor_dk']       = $res['nomor_kartu'];
        $data['jmh']            = str_replace('.','',$res['jmh_belanja']);
        $data['diskon_p']       = str_replace('.','',$res['diskon_p']);
        $data['diskon_nominal'] = str_replace('.','',$res['diskon_nominal']);
        $data['pajak']          = 0;
        $data['biaya_kirim']    = str_replace('.','',$res['biaya_kirim']);
        $data['total']          = str_replace('.','',$res['total_belanja']);
        $data['tunai']          = str_replace('.','',$res['jmh_uang']);
        $data['kembali']        = str_replace('.','',$res['jmh_kembali']);
        $data['lunas']          = 0;
        
        $data['jmh_debet']      = str_replace('.','',$res['jmh_debet']);
        $data['jmh_kredit']     = str_replace('.','',$res['jmh_kredit']);
        $data['jmh_tunai']      = str_replace('.','',$res['jmh_tunai']);
        $data['jmh_biaya_kartu'] = str_replace('.','',$res['biaya_kartu']);
        $dk                     = $res['dk'];
        $datadetail             = $res['rows'];
        
        //Validasi Ulang!
        if($dk=='Kredit')
        {
            $data['jmh_debet']  = 0;
            $data['jmh_kredit'] = $data['jmh_kredit'];
        }

        if($dk=='Debit')
        {
            $data['jmh_debet']  = $data['jmh_debet'];
            $data['jmh_kredit'] = 0;
        }
         
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        if($this->pembelian_model->simpan($data)) //Simpan Header Transaksi
        { 
            $seq                    = 0;
            if(count($datadetail)>0)
            {
                for($i=1;$i<count($datadetail);$i++)
                {
                    $kdBarang       = $datadetail[$i][0];
                    $nmBarang       = $datadetail[$i][1];
                    $qty            = $datadetail[$i][2]; 
                    $harga          = /*str_replace('.','',$data['rows'][$i][3]);*/$this->barang_model->get($datadetail[$i][0],'','','')->row()->barang_harga_jual;
                    $diskonPersen   = (float)str_replace('.','',$datadetail[$i][4]);
                    $diskon         = ($diskonPersen/100)*$harga;
                    $jmh            = $qty * ($harga - $diskon);
                    
                    $datadtl['no_faktur']   = $data['no_faktur'];
                    $datadtl['kd_barang']   = $kdBarang;
                    $datadtl['urutan']      = $seq;
                    $datadtl['qty']         = $qty;
                    $datadtl['satuan']      = "";
                    $datadtl['harga']       = $harga;
                    $datadtl['diskon_p']    = $diskonPersen;
                    $datadtl['pajak_p']     = 0;
                    $datadtl['jmh']         = $jmh;
                    /*
                     * Data untuk di print
                     */
                    $data['detail'][$i]['no_faktur']    = $datadtl['no_faktur'];
                    $data['detail'][$i]['kd_barang']    = $datadtl['kd_barang'];
                    $data['detail'][$i]['urutan']       = $datadtl['urutan'];
                    $data['detail'][$i]['qty']          = $datadtl['qty'];
                    $data['detail'][$i]['satuan']       = $datadtl['satuan'];
                    $data['detail'][$i]['harga']        = $datadtl['harga'];
                    $data['detail'][$i]['diskon_p']     = $datadtl['diskon_p'];
                    $data['detail'][$i]['pajak_p']      = $datadtl['pajak_p'];
                    $data['detail'][$i]['jmh']          = $datadtl['jmh'];
                    $data['detail'][$i]['imei']         = array();
                    
                    if($this->pembelian_model->simpan_dtl($datadtl))
                    {
                        $n              = strpos($nmBarang,"#");
                        $koleksiIMEI    = array();
                        if($n > 0)
                        {
                            $koleksiIMEI = explode('#',$nmBarang);
                            for($j=1;$j<count($koleksiIMEI);$j++)
                            {
                                $dataimei['no_faktur']      = $datadtl['no_faktur'];
                                $dataimei['kd_barang']      = $datadtl['kd_barang'];
                                $dataimei['imei']           = str_replace("\n",'',$koleksiIMEI[$j]);
                                $dataimei['urutan']         = $datadtl['urutan'];
                                
                                $this->pembelian_model->simpan_dtl_imei($dataimei);
                                
                                $noimei                     = str_replace("\n",'',$koleksiIMEI[$j]);
                                $cekimei                    = $this->barang_imei_model->get($datadtl['kd_barang'],$noimei,'');
                                if($cekimei->num_rows() > 0)
                                {
                                    $imeibarang['imei_barang']  = $datadtl['kd_barang'];
                                    $imeibarang['imei_no']      = $noimei;
                                    $imeibarang['imei_ref']     = $data['no_faktur'];
                                    $imeibarang['imei_status']  = 0;
                                    $this->barang_imei_model->update($datadtl['kd_barang'],$noimei,$imeibarang);
                                }
                                else
                                {
                                    $imeibarang['imei_barang']  = $datadtl['kd_barang'];
                                    $imeibarang['imei_no']      = $noimei;
                                    $imeibarang['imei_ref']     = $datadtl['no_faktur'];
                                    $imeibarang['imei_status']  = 0;
                                    $this->barang_imei_model->simpan($imeibarang);
                                }
                                
                                if($noimei!='')
                                {
                                    $data['detail'][$i]['imei'][$j] = $noimei;
                                }
                            }
                        }
                    }
                    else
                    {
                        $error              = 'Ada kesalahan pada Data Detail!';
                        break;
                    }
                    $seq++;
                }
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
        if($error!='')
        {
            echo 'E#'.$error;
        }
        else
        {
            echo 'S#'.$data['no_faktur'];
        }
    }
    function simpan_temp_pelanggan()
    {
        $res= json_decode($this->input->post('data'),true);
        
        if(count($res)<=0)
        {
            die('E#Data Kosong!');
        }
        $error                  = '';     
        
        $data['no_faktur']      = $res['no_faktur'];
        $data['tgl']            = $res['tgl'].' '.date('H:i:s');
        $data['ket']            = $res['ket'];
        $data['kd_term']        = 0;
        $data['total']          = str_replace('.','',$res['total']);
        
        $datadetail             = $res['rows'];
        
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        if($this->pembelian_model->simpan($data)) //Simpan Header Transaksi
        { 
            $seq                    = 0;
            if(count($datadetail)>0)
            {
                for($i=1;$i<count($datadetail);$i++)
                {
                    $kdBarang       = $datadetail[$i][0];
                    $nmBarang       = $datadetail[$i][1];
                    $qty            = $datadetail[$i][2]; 
                    $harga          = str_replace('.','',$datadetail[$i][3]);
                    $diskonPersen   = ((float)str_replace('.','',$datadetail[$i][4])/$harga)*100;
                    $diskon         = $datadetail[$i][4];
                    $jmh            = ($qty * $harga) - $diskon;
                    
                    $datadtl['no_faktur']   = $data['no_faktur'];
                    $datadtl['kd_barang']   = $kdBarang;
                    $datadtl['urutan']      = $seq;
                    $datadtl['qty']         = $qty;
                    $datadtl['satuan']      = "";
                    $datadtl['harga']       = $harga;
                    $datadtl['diskon_p']    = $diskonPersen;
                    $datadtl['jmh']         = $jmh;
                    /*
                     * Data untuk di print
                     */
                    $data['detail'][$i]['no_faktur']    = $datadtl['no_faktur'];
                    $data['detail'][$i]['kd_barang']    = $datadtl['kd_barang'];
                    $data['detail'][$i]['urutan']       = $datadtl['urutan'];
                    $data['detail'][$i]['qty']          = $datadtl['qty'];
                    $data['detail'][$i]['satuan']       = $datadtl['satuan'];
                    $data['detail'][$i]['harga']        = $datadtl['harga'];
                    $data['detail'][$i]['diskon']       = $datadtl['diskon'];
                    $data['detail'][$i]['jmh']          = $datadtl['jmh'];
                    
                    if($this->pembelian_model->simpan_dtl($datadtl))
                    {
                        strpos($nmBarang,"#");
                    }
                    else
                    {
                        $error              = 'Ada kesalahan pada Data Detail!';
                        break;
                    }
                    $seq++;
                }
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
        if($error!='')
        {
            echo 'E#'.$error;
        }
        else
        {
            echo 'S#'.$data['no_faktur'];
        }
    }
    function cetak($data)
    {
        $dataglobal     = $this->app_model->general();
        $namagudang     = $this->outlet_model->outlet_ambil($data['kd_outlet'])->row()->outlet_nm;
        $alamatgudang   = $this->outlet_model->outlet_ambil($data['kd_outlet'])->row()->outlet_alamat;
        $no             = $data['no_faktur'];
        $kasir          = $this->user_model->user_ambil($data['nik'])->row()->user_nm;
        // $handle         = printer_open($dataglobal['nama_printer']);
        // printer_set_option($handle, PRINTER_MODE, "RAW");
        // printer_start_doc($handle, "PrintKasir");
        // printer_start_page($handle);

        $this->load->library('escpos');// me-load library escpos
        // membuat connector printer ke shared printer bernama "POS-58" (yang telah disetting sebelumnya)
        $connector = new Escpos\PrintConnectors\WindowsPrintConnector($dataglobal['nama_printer']);
        // membuat objek $printer agar dapat di lakukan fungsinya
        $printer = new Escpos\Printer($connector);
        $cetak          = $this->app_model->maksimal(32,$namagudang,'tengah');
        //$cetak          .= $this->app_model->maksimal(32,' ','kiri');
        $cetak          .= $this->app_model->maksimal(32,'No.'.$data['no_faktur'],'kiri');
        $cetak          .= $this->app_model->maksimal(20,'Shift.'.$data['shift'],'kiri');
        $cetak          .= $this->app_model->maksimal(20,"Kasir:".$kasir,'kanan');
        $cetak          .= $this->app_model->garis_tigadua();
        for($i=0;$i<count($data['detail']);$i++)
        {
            $nmbarang   = $this->barang_model->get($data['detail'][$i]['kd_barang'],'','','')->row()->barang_nm;
            $cetak      .= $this->app_model->maksimal(32,$nmbarang,'kiri');
            $jmhimei    = 0;
            if(count($data['detail'][$i]['imei'])>0)
            {
                for($j=0;$j<count($data['detail'][$i]['imei']);$j++)
                {
                    $kataimei = ($jmhimei=='0') ? 'IMEI:' : ' ';
                    $cetak  .= $this->app_model->maksimal(7,$kataimei,'kiri');
                    $cetak  .= $this->app_model->maksimal(33,$data['detail'][$i]['imei'][$jmhimei],'kiri');
                    $jmhimei++;
                }
            }
            $cetak      .= $this->app_model->maksimal(14,$data['detail'][$i]['kd_barang'],'kiri');
            $cetak      .= $this->app_model->maksimal(13,$data['detail'][$i]['qty'].'x'.number_format($data['detail'][$i]['harga'],0,',','.'),'kanan');
            $cetak      .= $this->app_model->maksimal(13,number_format($data['detail'][$i]['jmh'],0,',','.'),'kanan');
        }
        $cetak          .= $this->app_model->garis_tigadua();
        $cetak          .= $this->app_model->maksimal(10,'TOTAL:','kiri');
        $cetak          .= $this->app_model->maksimal(30,number_format($data['total'],0,',','.'),'kanan');
        
        $cetak          .= $this->app_model->maksimal(11,'DEBIT CARD:','kiri');
        $cetak          .= $this->app_model->maksimal(29,number_format($data['jmh_debet'],0,',','.'),'kanan');

        $cetak          .= $this->app_model->maksimal(12,'CREDIT CARD:','kiri');
        $cetak          .= $this->app_model->maksimal(28,number_format($data['jmh_kredit'],0,',','.'),'kanan');

        $cetak          .= $this->app_model->maksimal(10,'TUNAI:','kiri');
        $cetak          .= $this->app_model->maksimal(30,number_format($data['tunai'],0,',','.'),'kanan');

        $cetak          .= $this->app_model->maksimal(15,'UANG KEMBALI :','kiri');
        $cetak          .= $this->app_model->maksimal(25,number_format($data['kembali'],0,',','.'),'kanan');

        //$cetak          .= $this->app_model->maksimal(15,'UANG KEMBALI:','kiri');
        //$cetak          .= $this->app_model->maksimal(25,number_format($data['kembali'],0,',','.'),'kanan');
        $cetak          .= $this->app_model->garis_tigadua();
        $cetak          .= ($data['nomor_dk']!='') ? $this->app_model->maksimal(32,'Nomor Kartu Anda : '.$data['nomor_dk'],'tengah') : '';
        $promosi        = $this->promosi_model->get()->row()->promosi_teks;
        $cetak          .= $this->app_model->maksimal(32,$promosi,'tengah');
        $cetak          .= $this->app_model->maksimal(32,'Terima Kasih','tengah');
        $cetak          .= $this->app_model->maksimal(32,$data['tgl'],'tengah');
        $cetak          .= $this->app_model->maksimal(32,' ','kiri');
        $cetak          .= $this->app_model->maksimal(32,' ','kiri');
        $printer->initialize();
        $printer->text($cetak);
        $printer->text("\n");
        $printer->feed(4); // mencetak 2 baris kosong, agar kertas terangkat ke atas
        $printer->close();

        // printer_write($handle,$cetak);
        // printer_end_page($handle);
        // printer_end_doc($handle);
        // printer_close($handle);
        redirect('kasir');
    }
    function cetak_dari_faktur($faktur,$balik='')
    {
        $general                = $this->app_model->general();
        $datadb                 = $this->pembelian_model->ambil($faktur);
        $data                   = array();
        $datareplace            = array(
                                    'jmh'           => 0,
                                    'total'         => 0,
                                    'tunai'         => 0,
                                    'kembali'       => 0
                                    );
        $data['no_faktur']      = $faktur;   
        $data['tgl']            = $datadb->row()->tgl;
        $data['kd_pelanggan']   = $datadb->row()->kd_pelanggan;
        $data['ket']            = $datadb->row()->ket;
        $data['nik']            = $datadb->row()->nik;
        $data['kd_term']        = $datadb->row()->kd_term;
        $data['nomor_dk']       = $datadb->row()->nomor_dk;
        $data['diskon_p']       = $datadb->row()->diskon_p;
        $data['diskon_nominal'] = $datadb->row()->diskon_nominal;
        $data['pajak']          = $datadb->row()->pajak;
        $data['jmh']            = $datadb->row()->jmh;
        $data['biaya_kirim']    = $datadb->row()->biaya_kirim;
        $data['total']          = $datadb->row()->total;
        $data['tunai']          = $datadb->row()->tunai;
        $data['kembali']        = $datadb->row()->kembali;
        $data['lunas']          = $datadb->row()->lunas;
        $data['kd_outlet']      = $datadb->row()->kd_outlet;
        $data['shift']          = $datadb->row()->shift;
        $data['jmh_debet']      = $datadb->row()->jmh_debet;
        $data['jmh_kredit']     = $datadb->row()->jmh_kredit;
        $data['jmh_tunai']      = $datadb->row()->jmh_tunai;
        $data['jmh_biaya_kartu'] = $datadb->row()->jmh_biaya_kartu;
        $data['detail']         = array();
        
        $datadbdtl              = $this->pembelian_model->ambil_dtl($faktur);
        $datasysvar             = $this->sys_var_model->get('hidden_kd_barang');
        $datasysvars            = explode(';',$datasysvar);
        
        if($datadbdtl->num_rows() > 0)
        {
            $i                = 0;
            foreach($datadbdtl->result() as $rowdbdtl)
            {
                $nampil                             = 1;
                for($j=0;$j<count($datasysvars);$j++)
                {
                    if($datasysvars[$j]!='')
                    {
                        if($datasysvars[$j]==$rowdbdtl->kd_barang)
                        {
                            $nampil                 = 0;
                        }
                    }
                }
                if($nampil==1)
                {
                    $data['detail'][$i]['no_faktur']    = $rowdbdtl->no_faktur;
                    $data['detail'][$i]['kd_barang']    = $rowdbdtl->kd_barang;
                    $data['detail'][$i]['urutan']       = $rowdbdtl->urutan;
                    $data['detail'][$i]['qty']          = $rowdbdtl->qty;
                    $data['detail'][$i]['satuan']       = $rowdbdtl->satuan;
                    $data['detail'][$i]['harga']        = $rowdbdtl->harga;
                    $data['detail'][$i]['diskon_p']     = $rowdbdtl->diskon_p;
                    $data['detail'][$i]['pajak_p']      = $rowdbdtl->pajak_p;
                    $data['detail'][$i]['jmh']          = $rowdbdtl->jmh;
                    $data['detail'][$i]['imei']         = array();
                    $datareplace['jmh']                 += $data['detail'][$i]['qty'] * $data['detail'][$i]['harga'];
                    $datareplace['total']               = $datareplace['jmh'];
                    //$datadbdtlimei                      = $this->barang_imei_model->get($rowdbdtl->kd_barang,'','',$faktur);
                    $datadbdtlimei                      = $this->pembelian_model->ambil_dtl_imei($faktur,$rowdbdtl->kd_barang,$rowdbdtl->urutan);
                    if($datadbdtlimei->num_rows() > 0)
                    {
                        $x                          = 0;
                        foreach($datadbdtlimei->result() as $rowdbdtlimei)
                        {
                            $data['detail'][$i]['imei'][$x] = $rowdbdtlimei->imei;
                            $x++;
                        }
                    }
                    $i++;
                }
            }
            
            $datareplace['tunai']                       = $datareplace['jmh'] - ($data['jmh_kredit'] + $data['jmh_debet']);
            $datareplace['kembali']                     = $datareplace['jmh'] - ($data['jmh_kredit']+$data['jmh_debet']+$datareplace['tunai']);
        }
        $data['jmh']    = $datareplace['jmh'];
        $data['total']  = $datareplace['total'];
        $data['tunai']  = $datareplace['tunai'];
        $data['kembali']= $datareplace['kembali'];
        $dataglobal     = $this->app_model->general();
        $namagudang     = $this->outlet_model->outlet_ambil($data['kd_outlet'])->row()->outlet_nm;
        $alamatgudang   = $this->outlet_model->outlet_ambil($data['kd_outlet'])->row()->outlet_alamat;
        $no             = $data['no_faktur'];
        $kasir          = $this->user_model->user_ambil($data['nik'])->row()->user_nm;
        //print_r($datareplace);  
        //print_r($data);die();
        // $handle         = printer_open($dataglobal['nama_printer']);
        // printer_set_option($handle, PRINTER_MODE, "RAW");
        // printer_start_doc($handle, "PrintKasir");
        // printer_start_page($handle);

        $this->load->library('escpos');// me-load library escpos
        // membuat connector printer ke shared printer bernama "POS-58" (yang telah disetting sebelumnya)
        $connector = new Escpos\PrintConnectors\WindowsPrintConnector($dataglobal['nama_printer']);
        // membuat objek $printer agar dapat di lakukan fungsinya
        $printer = new Escpos\Printer($connector);
        $cetak          = $this->app_model->maksimal(32,$namagudang,'tengah');
//        $cetak          .= "<br/>";
        if($alamatgudang!='')
        {
            $cetak      .= $this->app_model->maksimal(32,$alamatgudang,'tengah');
//            $cetak      .= "<br/>";
        }
        $cetak          .= $this->app_model->maksimal(32,' ','kiri');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,'No.'.$data['no_faktur'],'kiri');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(20,'Shift.'.$data['shift'],'kiri');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(20,"Kasir:".$kasir,'kanan');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->garis_tigadua();
//        $cetak          .= "<br/>";
        for($i=0;$i<count($data['detail']);$i++)
        {
            $nmbarang   = $this->barang_model->get($data['detail'][$i]['kd_barang'],'','','')->row()->barang_nm;
            $cetak      .= $this->app_model->maksimal(32,$nmbarang,'kiri');
//            $cetak      .= "<br/>";
            $jmhimei    = 0;
            if(count($data['detail'][$i]['imei'])>0)
            {
                for($j=0;$j<count($data['detail'][$i]['imei']);$j++)
                {
                    $kataimei = ($jmhimei=='0') ? 'IMEI:' : ' ';
                    $cetak  .= $this->app_model->maksimal(7,$kataimei,'kiri');
                    $cetak  .= $this->app_model->maksimal(33,trim($data['detail'][$i]['imei'][$jmhimei]),'kiri');
//                    $cetak          .= "<br/>";
                    $jmhimei++;
                }
            }
            $cetak      .= $this->app_model->maksimal(14,$data['detail'][$i]['kd_barang'],'kiri');
            $cetak      .= $this->app_model->maksimal(13,$data['detail'][$i]['qty'].'x'.number_format($data['detail'][$i]['harga'],0,',','.'),'kanan');
            $cetak      .= $this->app_model->maksimal(13,number_format($data['detail'][$i]['jmh'],0,',','.'),'kanan');
//            $cetak      .= "<br/>";
        }
        $cetak          .= $this->app_model->garis_tigadua();
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(10,'TOTAL:','kiri');
        $cetak          .= $this->app_model->maksimal(30,number_format($data['total'],0,',','.'),'kanan');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(11,'DEBIT CARD:','kiri');
        $cetak          .= $this->app_model->maksimal(29,number_format($data['jmh_debet'],0,',','.'),'kanan');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(12,'CREDIT CARD:','kiri');
        $cetak          .= $this->app_model->maksimal(28,number_format($data['jmh_kredit'],0,',','.'),'kanan');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(10,'TUNAI:','kiri');
        $cetak          .= $this->app_model->maksimal(30,number_format($data['jmh_tunai'],0,',','.'),'kanan');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(15,'UANG KEMBALI:','kiri');
        $cetak          .= $this->app_model->maksimal(25,number_format(($data['jmh_tunai']-$data['tunai']),0,',','.'),'kanan');
//        $cetak          .= "<br/>";
        
        if($data['kd_pelanggan']!=''&&$data['kd_pelanggan']!='0')
        {
            $cetak      .= $this->app_model->maksimal(20,'DISKON PELANGGAN :','kiri');
            $cetak      .= $this->app_model->maksimal(20,number_format($data['diskon_p'],0,',','.'),'kanan');
//            $cetak      .= "<br/>";
        }
        $cetak          .= $this->app_model->garis_tigadua();
//        $cetak          .= "<br/>";
        $cetak          .= ($data['nomor_dk']!='') ? $this->app_model->maksimal(32,'Nomor Kartu Anda : '.$data['nomor_dk'],'tengah') : '';
//        $cetak          .= "<br/>";
        $promosi        = $this->promosi_model->get()->row()->promosi_teks;
        $epromosi       = wordwrap($promosi,40,'@');
        $epromosi       = explode('@',$epromosi);
        for($j=0;$j<count($epromosi);$j++)
        {
            $cetak      .= $this->app_model->maksimal(32,$epromosi[$j],'tengah');
//            $cetak      .= "<br/>";
        }
        $cetak          .= $this->app_model->maksimal(32,'Terima Kasih','tengah');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,$data['tgl'],'tengah');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,'inovaPOS v.' . $general['versi'],'tengah');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,' ','tengah');
//        $cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,' ','tengah');
//        $cetak          .= "<br/>";
//        echo $cetak;
//        die();
        $printer->initialize();
        $printer->text($cetak);
        $printer->text("\n");
        $printer->feed(4); // mencetak 2 baris kosong, agar kertas terangkat ke atas
        $printer->close();

        // printer_write($handle,$cetak);
        // printer_end_page($handle);
        // printer_end_doc($handle);
        // printer_close($handle);
        if($balik=='')
        {
            redirect('laporan/penjualan');
        }
    }
    function cetak_screen()
    {
        $data           = $this->input->post('data');//var_dump($data);exit();
        $dataglobal     = $this->app_model->general();
        $namagudang     = $this->outlet_model->outlet_ambil($this->session->userdata('outlet_kd'))->row()->outlet_nm;
        $alamatgudang   = $this->outlet_model->outlet_ambil($this->session->userdata('outlet_kd'))->row()->outlet_alamat;
        $tanggal        = $this->session->userdata('tanggal');
        $shift          = $this->session->userdata('shift');
        $kasir          = $this->session->userdata('user_kd');
        // $handle         = printer_open($dataglobal['nama_printer']);
        // printer_set_option($handle, PRINTER_MODE, "RAW");
        // printer_start_doc($handle, "PrintKasir");
        // printer_start_page($handle);

        $this->load->library('escpos');// me-load library escpos
        // membuat connector printer ke shared printer bernama "POS-58" (yang telah disetting sebelumnya)
        $connector = new Escpos\PrintConnectors\WindowsPrintConnector($dataglobal['nama_printer']);
        // membuat objek $printer agar dapat di lakukan fungsinya
        $printer = new Escpos\Printer($connector);
        $cetak          = $this->app_model->maksimal(32,$namagudang,'tengah');
        //$cetak          .= "\n\r";
        $cetak          .= $this->app_model->maksimal(32,' ','kiri');
        //$cetak          .= "\n\r";
        //$cetak          .= $this->app_model->maksimal(20,'Shift.'.$shift,'kiri');
        $cetak          .= $this->app_model->maksimal(32,'Pastikan Barang Yang Dipesan Sudah Benar!','kiri');
        //$cetak          .= $this->app_model->maksimal(20,"Kasir:".$kasir,'kanan');
        //$cetak          .= "\n\r";
        $cetak          .= $this->app_model->garis_tigadua();
        //$cetak          .= "\n\r";
        $jumlahsemua    = 0;
       
        for($i=1;$i<=count($data);$i++)
        {
            if(strstr($data[$i][1],':')!='')
            {
                $nmbarangimei = explode(':',$data[$i][1]);
                $nmbarang   = $nmbarangimei[0];
                $imei       = explode('#',$nmbarangimei[1]);
                $cetak      .= $this->app_model->maksimal(32,trim($nmbarang),'kiri');
                //$detailimei = explode('#',$imei[1]);
                $jmhimei    = 0;
                if(count($imei)>0)
                {
                    $kataimei = "";
                    for($j=1;$j<count($imei);$j++)
                    {
                        $kataimei = ($jmhimei==0) ? 'IMEI:' : ' ';
                        $cetak  .= $this->app_model->maksimal(7,$kataimei,'kiri');
                        $cetak  .= $this->app_model->maksimal(33,trim($imei[$j]),'kiri');
                        //$cetak          .= "\n\r";
                        $jmhimei++;
                    }
                }
            }
            else
            {
                $cetak      .= $this->app_model->maksimal(32,$data[$i][1],'kiri');
            }
            $cetak      .= $this->app_model->maksimal(14,$data[$i][0],'kiri');
            $cetak      .= $this->app_model->maksimal(13,$data[$i][3].'x'.$data[$i][2],'kanan');
            $cetak      .= $this->app_model->maksimal(13,$data[$i][5],'kanan');
            //$cetak          .= "\n\r";
            $jumlahsemua += str_replace('.','',$data[$i][5]);
        }
        $cetak          .= $this->app_model->garis_tigadua();
        $cetak          .= $this->app_model->maksimal(10,'TOTAL:','kiri');
        $cetak          .= $this->app_model->maksimal(30,number_format($jumlahsemua,0,',','.'),'kanan');
        //$cetak          .= "\n\r";
        //$cetak          .= $this->app_model->maksimal(15,'UANG KEMBALI:','kiri');
        //$cetak          .= $this->app_model->maksimal(25,number_format($data['kembali'],0,',','.'),'kanan');
        $cetak          .= $this->app_model->garis_tigadua();
        $cetak          .= $this->app_model->maksimal(32,'Ini bukan faktur Penjualan','tengah');
        //$cetak          .= "\n\r";
        $cetak          .= $this->app_model->maksimal(32,$tanggal,'tengah');
        $cetak          .= $this->app_model->maksimal(32,'inovaPOS v.' . $this->config->item('versi'),'tengah');
        //$cetak          .= "\n\r";
        $cetak          .= $this->app_model->maksimal(32,' ','kiri');
        //$cetak          .= "\n\r";
        $cetak          .= $this->app_model->maksimal(32,' ','kiri');
        //$cetak          .= "\n\r";
        //echo $cetak;
        //die();
        $printer->initialize();
        $printer->text($cetak);
        $printer->text("\n");
        $printer->feed(4); // mencetak 2 baris kosong, agar kertas terangkat ke atas
        $printer->close();

        // printer_write($handle,$cetak);
        // printer_end_page($handle);
        // printer_end_doc($handle);
        // printer_close($handle);
        //redirect('kasir');
    }
    function tampil_cd()
    {
        $data               = $this->app_model->general();
        $error              = '';
        $port               = $data['port_cd'];
        $res                = json_decode($this->input->post('data'),true);
        $nms                = explode('#',$res['nm']);
        $nm                 = $nms[0];
        $harga              = number_format($res['hrg'],0,',','.');
        exec("mode $port BAUD=9600 PARITY=N data=8 stop=1 xon=off");

        $fp = fopen($port, "w");
        if (!$fp) 
        {
            $error      = 'Tidak ada alatnya, atau salah PORT!!!';
        } 
        else 
        {
            $teks           = $this->app_model->maksimal(20,$nm,'kiri');
            fwrite($fp,$teks);
            $teks           = $this->app_model->maksimal(20,$harga,'kanan');
            fwrite($fp,$teks);
        } 
        fclose($fp);
        if($error!='')
        {
            echo $error;
        }
    }
    function kasir_elektrik()
    {     
        if($this->session->userdata('user_nm')=='' || $this->session->userdata('user_group')=='SPV')
        {
            redirect('');
        }
        $data                           = $this->app_model->general();
        $data['option_tampilan']        = 'tanpa_menu';
        $data['halaman']                = 'kasir/index_elektrik';
        $this->load->view('layout/index',$data);
    }
    function simpan_elektrik()
    {
        $res= json_decode($this->input->post('data'),true);
        //int_r($res);die();
        if($res == null)
        {
            die('E#Data Kosong!');
        }
        $error                  = '';     
        $etgl                   = explode('-',$this->session->userdata('tanggal'));
        $tgls                   = substr($etgl[0],2,2).$etgl[1];
        $tglsekarang            = substr($etgl[0],2,2).$etgl[1].$etgl[2];
        $kd_akhir               = $this->pembelian_model->get_kdakhir($tglsekarang);
        $kd_akhir_history       = $this->pembelian_model->get_kdakhirhistory($tglsekarang);
        $fakturkdakhir          = ''; 
        if($kd_akhir > $kd_akhir_history)
        {
            $fakturkdakhir      = $kd_akhir;
        }
        else
        {
            $fakturkdakhir      = $kd_akhir_history;
        }
        $data['kd_outlet']      = $this->session->userdata('outlet_kd');
        $data['kd_gudang']      = $this->session->userdata('outlet_kd');
        $data['no_faktur']      = 'OSI'.$tglsekarang.$fakturkdakhir; 
        $data['tgl']            = $this->session->userdata('tanggal').' '.date('H:i:s');
        $data['shift']          = $this->session->userdata('shift');
        $data['kd_pelanggan']   = ''; 
        $data['ket']            = "";
        $data['nik']            = $this->session->userdata('user_kd');
        $data['kd_term']        = 0;
        $data['nomor_dk']       = $res['nomor_kartu'];
        $data['jmh']            = str_replace('.','',$res['jmh_belanja']);
        $data['diskon_p']       = str_replace('.','',$res['diskon_p']);
        $data['diskon_nominal'] = str_replace('.','',$res['diskon_nominal']);
        $data['pajak']          = 0;
        $data['biaya_kirim']    = str_replace('.','',$res['biaya_kirim']);
        $data['total']          = str_replace('.','',$res['total_belanja']);
        $data['tunai']          = str_replace('.','',$res['jmh_uang']);
        $data['kembali']        = str_replace('.','',$res['jmh_kembali']);
        $data['lunas']          = 0;
        $data['jmh_debet']      = str_replace('.','',$res['jmh_debet']);
        $data['jmh_kredit']     = str_replace('.','',$res['jmh_kredit']);
        $data['jmh_tunai']      = str_replace('.','',$res['jmh_uang']);
        $data['jmh_biaya_kartu'] = str_replace('.','',$res['biaya_kartu']);
        
        $this->db->trans_begin(); //TRANSAKSI DIMULAI
        if($this->pembelian_model->simpan($data)) //Simpan Header Transaksi
        { 
            $kdbarangelektrik               = $this->barang_elektrik_model->get();
            $barangelektrik                 = $this->barang_model->get($kdbarangelektrik['barang_kd'],'','','')->row_array();
            
            $seq                            = 0;
            $datadetail['no_faktur']        = $data['no_faktur'];
            $datadetail['kd_barang']        = $kdbarangelektrik['barang_kd'];
            $datadetail['urutan']           = $seq;
            $datadetail['qty']              = str_replace('.','',$res['total_belanja']);
            $datadetail['satuan']           = '';
            $datadetail['harga']            = ($barangelektrik['barang_harga_jual']=='') ? 0 : $barangelektrik['barang_harga_jual'];
            $datadetail['harga_pokok']      = 0;
            $datadetail['diskon_p']         = 0;
            $datadetail['pajak_p']          = 0;
            $datadetail['jmh']              = ($datadetail['qty'] * $datadetail['harga']);
            $this->pembelian_model->simpan_dtl($datadetail);
            if(count($res['rows'])>0)
            {
                for($i=1;$i<count($res['rows']);$i++)
                {
                    $dataelektrik['no_faktur']  = $data['no_faktur'];
                    $dataelektrik['kd_barang']  = $res['rows'][$i][0];
                    $dataelektrik['kd_gudang']  = $this->session->userdata('outlet_kd');
                    $dataelektrik['no_urut']    = $seq;
                    $dataelektrik['tgl']        = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $dataelektrik['shift']      = $this->session->userdata('shift');
                    $dataelektrik['qty']        = $res['rows'][$i][2];
                    $dataelektrik['harga']      = str_replace('.','',$res['rows'][$i][3]);
                    $dataelektrik['harga_pokok']= str_replace('.','',$res['rows'][$i][5]);
                    $nohp                       = explode('#',$res['rows'][$i][1]);
                    $dataelektrik['no_hp']      = $nohp[1];
                    $dataelektrik['status']     = 0;
                    $dataelektrik['uid']        = $this->session->userdata('user_kd');
                    $dataelektrik['doe']        = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $this->kasir_elektrik_model->simpan($dataelektrik);
                }
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
        if($error!='')
        {
            echo 'E#'.$error;
        }
        else
        {
            echo 'S#'.$data['no_faktur'];
        }
    }
    function cetak_elektrik_dari_faktur($faktur,$balik='')
    {
        $general                = $this->app_model->general();
        $datadb                 = $this->pembelian_model->ambil($faktur);
        $data                   = array();
        $datareplace            = array(
                                    'jmh'           => 0,
                                    'total'         => 0,
                                    'tunai'         => 0,
                                    'kembali'       => 0
                                    );
        $data['no_faktur']      = $faktur;   
        $data['tgl']            = $datadb->row()->tgl;
        $data['kd_pelanggan']   = $datadb->row()->kd_pelanggan;
        $data['ket']            = $datadb->row()->ket;
        $data['nik']            = $datadb->row()->nik;
        $data['kd_term']        = $datadb->row()->kd_term;
        $data['nomor_dk']       = $datadb->row()->nomor_dk;
        $data['diskon_p']       = $datadb->row()->diskon_p;
        $data['diskon_nominal'] = $datadb->row()->diskon_nominal;
        $data['pajak']          = $datadb->row()->pajak;
        $data['jmh']            = $datadb->row()->jmh;
        $data['biaya_kirim']    = $datadb->row()->biaya_kirim;
        $data['total']          = $datadb->row()->total;
        $data['tunai']          = $datadb->row()->tunai;
        $data['kembali']        = $datadb->row()->kembali;
        $data['lunas']          = $datadb->row()->lunas;
        $data['kd_outlet']      = $datadb->row()->kd_outlet;
        $data['shift']          = $datadb->row()->shift;
        $data['jmh_debet']      = $datadb->row()->jmh_debet;
        $data['jmh_kredit']     = $datadb->row()->jmh_kredit;
        $data['jmh_tunai']      = $datadb->row()->jmh_tunai;
        $data['jmh_biaya_kartu'] = $datadb->row()->jmh_biaya_kartu;
        $data['detail']         = array();
		$datadbdtl				= $this->kasir_elektrik_model->get('','','','','','',$faktur);
        //$datadbdtl              = $this->kasir_elektrik_model->get($this->session->userdata('tanggal'),$this->session->userdata('shift'),'','','',$faktur);
		
        //echo $this->db->last_query();die();
        $datasysvar             = $this->sys_var_model->get('hidden_kd_barang');
        $datasysvars            = explode(';',$datasysvar);
        if($datadbdtl->num_rows() > 0)
        {
            $i                = 0;
            foreach($datadbdtl->result() as $rowdbdtl)
            {
                $nampil                             = 1;
                for($j=0;$j<count($datasysvars);$j++)
                {
                    if($datasysvars[$j]!='')
                    {
                        if($datasysvars[$j]==$rowdbdtl->kd_barang)
                        {
                            $nampil                 = 0;
                        }
                    }
                }
                if($nampil==1)
                {
                    $data['detail'][$i]['no_faktur']    = $rowdbdtl->no_faktur;
                    $data['detail'][$i]['kd_barang']    = $rowdbdtl->kd_barang;
                    $data['detail'][$i]['urutan']       = $rowdbdtl->no_urut;
                    $data['detail'][$i]['qty']          = $rowdbdtl->qty;
                    $data['detail'][$i]['satuan']       = 0;
                    $data['detail'][$i]['harga']        = $rowdbdtl->harga;
                    $data['detail'][$i]['diskon_p']     = 0;
                    $data['detail'][$i]['pajak_p']      = 0;
                    $data['detail'][$i]['jmh']          = $rowdbdtl->qty * $rowdbdtl->harga;
                    $data['detail'][$i]['imei']         = array($rowdbdtl->no_hp);
                    $datareplace['jmh']                 += $data['detail'][$i]['qty'] * $data['detail'][$i]['harga'];
                    $datareplace['total']               = $datareplace['jmh'];
                    $i++;
                }
            }
            $datareplace['tunai']                       = $datareplace['jmh'] - ($data['jmh_kredit'] + $data['jmh_debet']);
            $datareplace['kembali']                     = $datareplace['jmh'] - ($data['jmh_kredit']+$data['jmh_debet']+$datareplace['tunai']);
        }
        $data['jmh']    = $datareplace['jmh'];
        $data['total']  = $datareplace['total'];
        $data['tunai']  = $datareplace['tunai'];
        $data['kembali']= $datareplace['kembali'];
        $dataglobal     = $this->app_model->general();
        $namagudang     = $this->outlet_model->outlet_ambil($data['kd_outlet'])->row()->outlet_nm;
        $alamatgudang   = $this->outlet_model->outlet_ambil($data['kd_outlet'])->row()->outlet_alamat;
        $no             = $data['no_faktur'];
        $kasir          = $this->user_model->user_ambil($data['nik'])->row()->user_nm;

        // $handle         = printer_open($dataglobal['nama_printer']);
        // printer_set_option($handle, PRINTER_MODE, "RAW");
        // printer_start_doc($handle, "PrintKasir");
        // printer_start_page($handle);

        $this->load->library('escpos');// me-load library escpos
        // membuat connector printer ke shared printer bernama "POS-58" (yang telah disetting sebelumnya)
        $connector = new Escpos\PrintConnectors\WindowsPrintConnector($dataglobal['nama_printer']);
        // membuat objek $printer agar dapat di lakukan fungsinya
        $printer = new Escpos\Printer($connector);
        $cetak          = $this->app_model->maksimal(32,$namagudang,'tengah');
        //$cetak          .= "<br/>";
        if($alamatgudang!='')
        {
            $cetak      .= $this->app_model->maksimal(32,$alamatgudang,'tengah');
            //$cetak      .= "<br/>";
        }
        $cetak          .= $this->app_model->maksimal(32,' ','kiri');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,'No.'.$data['no_faktur'],'kiri');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(20,'Shift.'.$data['shift'],'kiri');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(20,"Kasir:".$kasir,'kanan');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->garis_tigadua();
        //$cetak          .= "<br/>";
        for($i=0;$i<count($data['detail']);$i++)
        {
            $nmbarang   = $this->barang_elektrik_model->get($data['detail'][$i]['kd_barang'],'test');
            $cetak      .= $this->app_model->maksimal(32,$nmbarang['barang_nm'],'kiri');
            //$cetak      .= "<br/>";
            $jmhimei    = 0;
            if(count($data['detail'][$i]['imei'])>0)
            {
                for($j=0;$j<count($data['detail'][$i]['imei']);$j++)
                {
                    $kataimei = ($jmhimei=='0') ? 'No. HP:' : ' ';
                    $cetak  .= $this->app_model->maksimal(7,$kataimei,'kiri');
                    $cetak  .= $this->app_model->maksimal(33,trim($data['detail'][$i]['imei'][$jmhimei]),'kiri');
                    //$cetak          .= "<br/>";
                    $jmhimei++;
                }
            }
            $cetak      .= $this->app_model->maksimal(14,$data['detail'][$i]['kd_barang'],'kiri');
            $cetak      .= $this->app_model->maksimal(13,$data['detail'][$i]['qty'].'x'.number_format($data['detail'][$i]['harga'],0,',','.'),'kanan');
            $cetak      .= $this->app_model->maksimal(13,number_format($data['detail'][$i]['jmh'],0,',','.'),'kanan');
            //$cetak      .= "<br/>";
        }
        $cetak          .= $this->app_model->garis_tigadua();
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(10,'TOTAL:','kiri');
        $cetak          .= $this->app_model->maksimal(30,number_format($data['total'],0,',','.'),'kanan');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(11,'DEBIT CARD:','kiri');
        $cetak          .= $this->app_model->maksimal(29,number_format($data['jmh_debet'],0,',','.'),'kanan');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(12,'CREDIT CARD:','kiri');
        $cetak          .= $this->app_model->maksimal(28,number_format($data['jmh_kredit'],0,',','.'),'kanan');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(10,'TUNAI:','kiri');
        $cetak          .= $this->app_model->maksimal(30,number_format($data['jmh_tunai'],0,',','.'),'kanan');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(15,'UANG KEMBALI:','kiri');
        $cetak          .= $this->app_model->maksimal(25,number_format($data['jmh_tunai']-$data['tunai'],0,',','.'),'kanan');
        //$cetak          .= "<br/>";
        
        if($data['kd_pelanggan']!=''&&$data['kd_pelanggan']!='0')
        {
            $cetak      .= $this->app_model->maksimal(20,'DISKON PELANGGAN :','kiri');
            $cetak      .= $this->app_model->maksimal(20,number_format($data['diskon_p'],0,',','.'),'kanan');
            //$cetak      .= "<br/>";
        }
        $cetak          .= $this->app_model->garis_tigadua();
        //$cetak          .= "<br/>";
        $cetak          .= ($data['nomor_dk']!='') ? $this->app_model->maksimal(32,'Nomor Kartu Anda : '.$data['nomor_dk'],'tengah') : '';
        //$cetak          .= "<br/>";
        $promosi        = $this->promosi_model->get()->row()->promosi_teks;
        $epromosi       = wordwrap($promosi,40,'@');
        $epromosi       = explode('@',$epromosi);
        for($j=0;$j<count($epromosi);$j++)
        {
            $cetak      .= $this->app_model->maksimal(32,$epromosi[$j],'tengah');
            //$cetak      .= "<br/>";
        }
        $cetak          .= $this->app_model->maksimal(32,'Terima Kasih','tengah');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,$data['tgl'],'tengah');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,'inovaPOS v.' . $general['versi'],'tengah');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,' ','tengah');
        //$cetak          .= "<br/>";
        $cetak          .= $this->app_model->maksimal(32,' ','tengah');
        //$cetak          .= "<br/>";
        //echo $cetak;
        //die();
        $printer->initialize();
        $printer->text($cetak);
        $printer->text("\n");
        $printer->feed(4); // mencetak 2 baris kosong, agar kertas terangkat ke atas
        $printer->close();

        // printer_write($handle,$cetak);
        // printer_end_page($handle);
        // printer_end_doc($handle);
        // printer_close($handle);
        if($balik=='')
        {
            redirect('laporan/penjualan');
        }
    }
    function kirim_elektrik($nofaktur)
    {
        $update['status']       = 1;
        $where                  = "no_faktur = '$nofaktur'";
        $updates                = $this->kasir_elektrik_model->edit($where,$update);
        if($updates)
        {
            echo 'Sukses';
        }
        else
        {
            echo 'Gagal';
        }
    }
}
           