<?php

class Tutup_shift extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('curl');  
        $this->load->model('barang_model');
        $this->load->model('log_proses_model','log');
        $this->load->model('jual_model');
        $this->load->model('barang_saldo_model');
        $this->load->model('barang_mutasi_model');
        $this->load->model('kasir_model');
        $this->load->model('history_kasir_model');
        $this->load->model('history_mutasi_model');
        $this->load->model('tukar_model','tukar');
    }
    
    function index()
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                   = $this->app_model->general();
        $data['belum_dikirim']  = $this->log->getLaporanBelumDikirim();
        $data['retur']          = $this->tukar->getBelumSinkron();

        
        $data['judulweb']       = ' | Tutup Shift ';
        $data['halaman']        = 'Tutup_shift/index';
        $this->load->view('layout/index',$data);
    }

    function simpan()
    {
        /*$data['promosi_teks']   = $this->input->post('promosi_teks');
        if($this->promosi_model->update($data))
        {
            echo '<script type="text/javascript">alert("Berhasil disimpan!");window.location="'.base_url().'index.php/promosi";</script>';
        }
        else
        {
            echo '<script type="text/javascript">alert("Gagal disimpan!");history.go(-1);</script>';
        }*/
    }
    
    function close_transaction2()
    {
        $this->db->trans_begin();
        if($this->jual_model->jmh_bon_real($this->session->userdata('tanggal'),$this->session->userdata('shift')) > 0)
        {
            $daftarmutasibelumsinkronisasi = $this->barang_mutasi_model->kirim_mutasi($this->session->userdata('tanggal'));
            //print_r($daftarmutasibelumsinkronisasi);
            //die();
            if(count($daftarmutasibelumsinkronisasi)<=0)
            {
                if($this->barang_saldo_model->saldo_awal_baru())
                {
                    $hdrkasir       = $this->kasir_model->ambil('','','','');
                    if($hdrkasir->num_rows() > 0)
                    {
                        foreach($hdrkasir->result() as $rowhdrkasir)
                        {
                            $ac_tjual['no_faktur']              = $rowhdrkasir->no_faktur;
                            $ac_tjual['tgl']                    = $rowhdrkasir->tgl;
                            $ac_tjual['kd_pelanggan']           = $rowhdrkasir->kd_pelanggan;
                            $ac_tjual['ket']                    = $rowhdrkasir->ket;
                            $ac_tjual['nik']                    = $rowhdrkasir->nik;
                            $ac_tjual['kd_term']                = $rowhdrkasir->kd_term;
                            $ac_tjual['diskon_p']               = $rowhdrkasir->diskon_p;
                            $ac_tjual['pajak']                  = $rowhdrkasir->pajak;
                            $ac_tjual['jmh']                    = $rowhdrkasir->jmh;
                            $ac_tjual['biaya_kirim']            = $rowhdrkasir->biaya_kirim;
                            $ac_tjual['total']                  = $rowhdrkasir->total;
                            $ac_tjual['lunas']                  = $rowhdrkasir->lunas;
                            $ac_tjual['kd_gudang']              = $rowhdrkasir->kd_gudang;
                            $ac_tjual['coa_biaya']              = $rowhdrkasir->coa_biaya;
                            $ac_tjual['shift']                  = $rowhdrkasir->shift;
                            $ac_tjual['uid']                    = $rowhdrkasir->uid;
                            $ac_tjual['last_update']            = $rowhdrkasir->last_update;
                            $ac_tjual['nomor_dk']               = $rowhdrkasir->nomor_dk;
                            $ac_tjual['diskon_nominal']         = $rowhdrkasir->diskon_nominal;
                            $ac_tjual['tunai']                  = $rowhdrkasir->tunai;
                            $ac_tjual['kembali']                = $rowhdrkasir->kembali;
                            $ac_tjual['kd_outlet']              = $rowhdrkasir->kd_outlet;
                            $ac_tjual['jmh_dk']                 = $rowhdrkasir->jmh_dk;
                            $ac_tjual['jmh_tunai']              = $rowhdrkasir->jmh_tunai;
                            $ac_tjual['jmh_debet']              = $rowhdrkasir->jmh_debet;
                            $ac_tjual['jmh_kredit']             = $rowhdrkasir->jmh_kredit;
                            $ac_tjual['jmh_biaya_kartu']        = $rowhdrkasir->jmh_biaya_kartu;
                            $this->history_kasir_model->simpan_kasir($ac_tjual);
                            $dtlkasir                           = $this->kasir_model->ambil_dtl($rowhdrkasir->no_faktur);
                            if($dtlkasir->num_rows() > 0)
                            {
                                foreach($dtlkasir->result() as $rowdtlkasir)
                                {
                                    $ac_tjual_dtl['no_faktur']  = $rowdtlkasir->no_faktur;
                                    $ac_tjual_dtl['kd_barang']  = $rowdtlkasir->kd_barang;
                                    $ac_tjual_dtl['urutan']     = $rowdtlkasir->urutan;
                                    $ac_tjual_dtl['qty']        = $rowdtlkasir->qty;
                                    $ac_tjual_dtl['satuan']     = $rowdtlkasir->satuan;
                                    $ac_tjual_dtl['harga']      = $rowdtlkasir->harga;
                                    $ac_tjual_dtl['diskon_p']   = $rowdtlkasir->diskon_p;
                                    $ac_tjual_dtl['pajak_p']    = $rowdtlkasir->pajak_p;
                                    $ac_tjual_dtl['jmh']        = $rowdtlkasir->jmh;
                                    $this->history_kasir_model->simpan_kasir_dtl($ac_tjual_dtl);
                                    $imeikasir                  = $this->kasir_model->ambil_dtl_imei($rowdtlkasir->no_faktur,$rowdtlkasir->kd_barang,$rowdtlkasir->urutan);
                                    if($imeikasir->num_rows() > 0)
                                    {
                                        foreach($imeikasir->result() as $rowimeikasir)
                                        {
                                            $ac_tjual_dtl_imei['no_faktur'] = $rowimeikasir->no_faktur;
                                            $ac_tjual_dtl_imei['kd_barang'] = $rowimeikasir->kd_barang;
                                            $ac_tjual_dtl_imei['imei']      = $rowimeikasir->imei;
                                            $ac_tjual_dtl_imei['urutan']    = $rowimeikasir->urutan;
                                            $this->history_kasir_model->simpan_kasir_dtl_imei($ac_tjual_dtl_imei);
                                        }
                                    }
                                }
                            }
                        }
                    } 
                    
                    $hdrpindah      = $this->barang_mutasi_model->get('','');
                    if($hdrpindah->num_rows() > 0)
                    {
                        foreach($hdrpindah->result() as $rowhdrpindah)
                        {
                            $im_tpindah_barang['no_faktur']         = $rowhdrpindah->no_faktur;
                            $im_tpindah_barang['tgl']               = $rowhdrpindah->tgl;
                            $im_tpindah_barang['ket']               = $rowhdrpindah->ket;
                            $im_tpindah_barang['kd_gudang_asal']    = $rowhdrpindah->kd_gudang_asal;
                            $im_tpindah_barang['kd_gudang_tujuan']  = $rowhdrpindah->kd_gudang_tujuan;
                            $im_tpindah_barang['ref']               = $rowhdrpindah->ref;
                            $im_tpindah_barang['tgl_terima']        = $rowhdrpindah->tgl_terima;
                            $im_tpindah_barang['st_dokumen']        = $rowhdrpindah->st_dokumen;
                            $im_tpindah_barang['status']            = $rowhdrpindah->status;
                            $im_tpindah_barang['sinkronisasi']      = $rowhdrpindah->sinkronisasi;
                            $this->history_mutasi_model->simpan_pindah($im_tpindah_barang);
                            $dtlpindah                              = $this->barang_mutasi_model->get_dtl_aja($rowhdrpindah->no_faktur);
                            if($dtlpindah->num_rows() > 0)
                            {
                                foreach($dtlpindah->result() as $rowdtlpindah)
                                {
                                    $im_tpindah_barang_dtl['no_faktur'] = $rowdtlpindah->no_faktur;
                                    $im_tpindah_barang_dtl['kd_barang'] = $rowdtlpindah->kd_barang;
                                    $im_tpindah_barang_dtl['qty']       = $rowdtlpindah->qty;
                                    $im_tpindah_barang_dtl['satuan']    = $rowdtlpindah->satuan;
                                    $im_tpindah_barang_dtl['urutan']    = $rowdtlpindah->urutan;
                                    $im_tpindah_barang_dtl['harga']     = $rowdtlpindah->harga;
                                    $this->history_mutasi_model->simpan_pindah_dtl($im_tpindah_barang_dtl);
                                    $imeipindah                         = $this->barang_mutasi_model->get_dtl_imei($rowdtlpindah->no_faktur,$rowdtlpindah->kd_barang);
                                    if($imeipindah->num_rows() > 0)
                                    {
                                        foreach($imeipindah->result() as $rowimeipindah)
                                        {
                                            $imei['no_faktur']          = $rowimeipindah->no_faktur;
                                            $imei['kd_barang']          = $rowimeipindah->kd_barang;
                                            $imei['imei']               = $rowimeipindah->imei;
                                            $this->history_mutasi_model->simpan_pindah_dtl_imei($imei);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                else
                {
                    echo '<script type="text/javascript">alert("Penutupan Transaksi Gagal!");window.location = "'. base_url(). 'index.php/tutup_shift";</script>';
                }
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                    echo '<script type="text/javascript">alert("Penutupan Transaksi Gagal!");history.go(-1);</script>';
                }
                else
                {
                    $datas['log_col']   = TUTUP_SHIFT;
                    $datas['tgl']       = $this->session->userdata('tanggal');
                    $etgl               = explode('-',$datas['tgl']);
                    $tgl_sesudahnya     = date('Y-m-d',mktime(0,0,0,$etgl[1],$etgl[2]+1,$etgl[0]));
                    $datas['shift']     = $this->session->userdata('shift');
                    $datas['tipe']      = TUTUP_SHIFT;
                    $data['log_val']    = 'CLOSE';
                    $data['uid_edit']   = $this->session->userdata('user_kd');
                    $data['tgl_edit']   = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $this->log->update($datas,$data);
                    $this->db->trans_commit();
                    redirect('tutup_shift/bukti_setor');
                }
            }
            else
            {
                echo '<script type="text/javascript">alert("Mohon maaf, tidak bisa tutup shift karena Mutasi belum disinkronisasi!");window.location = "'. base_url(). 'index.php/sinkronisasi/halaman_mutasi";</script>'; 
            }
        }
        else
        {
            echo '<script type="text/javascript">alert("Mohon maaf, tidak bisa tutup shift karena tidak ada penjualan!");window.location = "'. base_url(). 'index.php/tutup_shift";</script>';
        }
    }
    
    function close_transaction($cekpenjualan)
    {
        $this->db->trans_begin();
        $terus              = false;
        if($cekpenjualan=='')
        {
            if($this->jual_model->jmh_bon_real($this->session->userdata('tanggal'),$this->session->userdata('shift')) > 0)
            {
                $terus      = true;
            }
            else
            {
                $terus      = false;
            }
        }
        else
        {
            $terus          = true;
        }
        if($terus)
        {
            $daftarmutasibelumsinkronisasi = $this->barang_mutasi_model->kirim_mutasi('');
            
            if(count($daftarmutasibelumsinkronisasi)==0)
            {
                $daftarmutasibelumsinkronisasihistory = $this->barang_mutasi_model->get_history_notsync('');
                if($daftarmutasibelumsinkronisasihistory->num_rows()>0)
                {
                    echo '<script type="text/javascript">alert("Peringatan, ada Mutasi yang belum disinkronisasi!");</script>';
                }
            }
            else
            {
                echo '<script type="text/javascript">alert("Peringatan, ada Mutasi yang belum disinkronisasi!");</script>';
            }
                /*
                 | Membuat Saldo Awal Baru
                 */
                $error_saldo_awal_baru                  = '';
                $saldo_hari_ini                         = $this->barang_saldo_model->saldo_hari_ini();
                for($i=0;$i<count($saldo_hari_ini);$i++)
                {
                    if($saldo_hari_ini[$i]['saldo_qty']!=0)
                    {
                        $dataSaldoAwal['saldo_barang']  = $saldo_hari_ini[$i]['saldo_barang'];
                        $dataSaldoAwal['saldo_gudang']  = $saldo_hari_ini[$i]['saldo_gudang'];
                        $dataSaldoAwal['saldo_qty']     = $saldo_hari_ini[$i]['saldo_qty'];
                        if($saldo_hari_ini[$i]['saldo_shift']=='1')
                        {
                            $dataSaldoAwal['saldo_tgl'] = $saldo_hari_ini[$i]['saldo_tgl'];
                            $dataSaldoAwal['saldo_shift'] = '2'; 
                        }
                        else
                        {
                            $etgl                       = explode('-',$saldo_hari_ini[$i]['saldo_tgl']);
                            $dataSaldoAwal['saldo_tgl'] = date('Y-m-d',mktime(0,0,0,$etgl[1],$etgl[2]+1,$etgl[0],0));
                            $dataSaldoAwal['saldo_shift'] = '1';
                        }
                        
                        if(!$this->db->insert('im_msaldo_barang',$dataSaldoAwal))
                        {
                            $error_saldo_awal_baru      .= 'ERROR#';
                            break;
                        }
                    }
                }
                if($error_saldo_awal_baru=='')
                {
                    /*
                     | Pindahkan Seluruh Penjualan yang ada di tabel ac_tjual ke tabel history
                     */
                    $hdrkasir       = $this->kasir_model->ambil('','','','');
                    if($hdrkasir->num_rows() > 0)
                    {
                        $query      = '
                            INSERT INTO history_ac_tjual
                            SELECT * FROM ac_tjual
                            WHERE date(tgl) = '."'".$this->session->userdata('tanggal')."'".' and shift = '.$this->session->userdata('shift');
                        if($this->db->query($query))
                        {
                            $querydtl   = '
                                INSERT INTO history_ac_tjual_dtl
                                SELECT * FROM ac_tjual_dtl
                                WHERE no_faktur IN 
                                (
                                    SELECT no_faktur 
                                    FROM ac_tjual
                                    WHERE date(tgl) = '."'".$this->session->userdata('tanggal')."'".' and shift = '.$this->session->userdata('shift').'
                                )';
                            if($this->db->query($querydtl))
                            {
                                $querydtlimei   = '
                                    INSERT INTO history_ac_tjual_dtl_imei
                                    SELECT * FROM ac_tjual_dtl_imei
                                    WHERE no_faktur IN 
                                    (
                                        SELECT no_faktur 
                                        FROM ac_tjual
                                        WHERE date(tgl) = '."'".$this->session->userdata('tanggal')."'".' and shift = '.$this->session->userdata('shift').'
                                    )';
                                $this->db->query($querydtlimei);
                            }
                        }
                    }
                    
                    /*
                     | Pindahkan Seluruh Mutasi yang ada di tabel im_tpindah_barang ke tabel history
                     */
                    $hdrpindah      = $this->barang_mutasi_model->get('','');
                    if($hdrpindah->num_rows() > 0)
                    {
                        $query      = '
                            INSERT INTO history_im_tpindah_barang
                            SELECT * FROM im_tpindah_barang
                            /*WHERE date(tgl) = '."'".$this->session->userdata('tanggal')."'*/";
                        if($this->db->query($query))
                        {
                            $querydtl   = '
                                INSERT INTO history_im_tpindah_barang_dtl
                                SELECT * FROM im_tpindah_barang_dtl
                                WHERE no_faktur IN 
                                (
                                    SELECT no_faktur 
                                    FROM im_tpindah_barang
                                    /*WHERE date(tgl) = '."'".$this->session->userdata('tanggal')."'*/".'
                                )';
                            if($this->db->query($querydtl))
                            {
                                $querydtlimei   = '
                                    INSERT INTO history_im_tpindah_barang_dtl_imei
                                    SELECT * FROM im_tpindah_barang_dtl_imei
                                    WHERE no_faktur IN 
                                    (
                                        SELECT no_faktur 
                                        FROM im_tpindah_barang
                                        /*WHERE date(tgl) = '."'".$this->session->userdata('tanggal')."'*/".'
                                    )';
                                $this->db->query($querydtlimei);
                            }
                        }
                    }
                    
                    /*
                     | Masukkan log Tutup Shift ke log_proses
                     */
                    $datas['log_col']   = TUTUP_SHIFT;
                    $datas['tgl']       = $this->session->userdata('tanggal');
                    $etgl               = explode('-',$datas['tgl']);
                    $tgl_sesudahnya     = date('Y-m-d',mktime(0,0,0,$etgl[1],$etgl[2]+1,$etgl[0]));
                    $datas['shift']     = $this->session->userdata('shift');
                    $datas['tipe']      = TUTUP_SHIFT;
                    //$datas['tingtong']  = '';
                    $data['log_val']    = 'CLOSE';
                    $data['uid_edit']   = $this->session->userdata('user_kd');
                    $data['tgl_edit']   = $this->session->userdata('tanggal').' '.date('H:i:s');
                    $this->log->update($datas,$data);
                    
                    /*
                     | Hapus Tabel Penjualan dan Mutasi
                     */
                    $this->db->where("no_faktur!=''",'',false);
                    $this->db->delete('im_tpindah_barang');
                    $this->db->where("date(tgl)",$this->session->userdata('tanggal'));
                    $this->db->where("shift",$this->session->userdata('shift'));
                    $this->db->delete('ac_tjual'); 
                    
                    echo '<script type="text/javascript">window.location="'.base_url().'index.php/tutup_shift/sukses_tutup_shift";</script>';
                }
                else
                {
                    echo '<script type="text/javascript">alert("Penutupan Transaksi Gagalzz!");window.location = "'. base_url(). 'index.php/tutup_shift";</script>';
                }
                if($this->db->trans_status()===FALSE)
                {
                    $this->db->trans_rollback();
                    echo '<script type="text/javascript">alert("Penutupan Transaksi Gagal!");history.go(-1);</script>';
                }
                else
                {
                    $this->db->trans_commit();
                    redirect('tutup_shift/bukti_setor');
                }
        }
        else
        {
            echo '<script type="text/javascript">alert("Mohon maaf, tidak bisa tutup shift jika tidak ada penjualan!");window.location = "'. base_url(). 'index.php/tutup_shift";</script>';
        }
    }
    function sukses_tutup_shift()
    {
        echo '<html>
                <head>
                    <meta http-equiv="refresh" content="2; URL='.base_url().'index.php/barang/download_stok_opname2/">
                </head>
                <body>
                <script type="text/javascript" language="JavaScript">
                function Redirect() 
                {
                    window.location = "'.base_url().'index.php/tutup_shift";
                }
                window.onload = function() { setTimeout("Redirect()",2000); }
                </script>
            </body>
        </html>';
    }
    function simpan_tutup_shift()
    {
        $data['log_col']                = "SHIFT";
        $data['log_val']                = "CLOSE";
        $data['tipe']                   = "SHIFT";
        $data['tgl']                    = $this->session->userdata('tanggal');
        $data['shift']                  = $this->session->userdata('shift');
        $data['uid']                    = $this->session->userdata('user_kd');
        $this->log->simpanLogJual($data);
    }
    function update_tutup_shift()
    {
        $data['log_col']                = "SHIFT";
        $data['log_val']                = "CLOSE";
        $data['tipe']                   = "SHIFT";
        $data['tgl']                    = $this->session->userdata('tanggal');
        $data['shift']                  = $this->session->userdata('shift');
        $data['uid']                    = $this->session->userdata('user_kd');
        $this->log->simpanLogJual($data);
    }
    function simpan_history($tgl,$tgl_batas,$shift)
    {
        $this->db->trans_begin();
        $hdrkasir       = $this->kasir_model->ambil('','',$tgl_batas,'');
        if($hdrkasir->num_rows() > 0)
        {
            $ahdrkasir  = $hdrkasir->result_array();
            for($i=0;$i<count($ahdrkasir);$i++)
            {
                $this->history_kasir_model->simpan_kasir($ahdrkasir[$i]);
                $dtlkasir = $this->kasir_model->ambil_dtl($ahdrkasir[$i]['no_faktur']);
                if($dtlkasir->num_rows() >0)
                {
                    $adtlkasir = $dtlkasir->result_array();
                    for($j=0;$j<count($adtlkasir);$j++)
                    {
                        $this->history_kasir_model->simpan_kasir_dtl($adtlkasir[$j]);
                        $imeikasir = $this->kasir_model->ambil_dtl_imei($adtlkasir[$j]['no_faktur'],$adtlkasir[$j]['kd_barang']);
                        if($imeikasir->num_rows() > 0)
                        {
                            $aimeikasir = $imeikasir->result_array();
                            for($k=0;$k<count($aimeikasir);$k++)
                            {
                                $this->history_kasir_model->simpan_kasir_dtl_imei($aimeikasir[$k]);
                            }
                        }
                    }
                }
            }
        } 
        $hdrpindah      = $this->barang_mutasi_model->get('','');
        if($hdrpindah->num_rows() > 0)
        {
            $ahdrpindah = $hdrpindah->result_array();
            for($l=0;$l<count($ahdrpindah);$l++)
            {
                $kd = $ahdrpindah[$l]['no_faktur'];
                $numkd = $this->history_mutasi_model->get($kd)->num_rows();
                if($numkd == 0)
                {
                    $this->history_mutasi_model->simpan_pindah($ahdrpindah[$l]);
                }
                $dtlpindah = $this->barang_mutasi_model->get_dtl_aja($ahdrpindah[$l]['no_faktur']);
                if($dtlpindah->num_rows() > 0)
                {
                    $adtlpindah = $dtlpindah->result_array();
                    for($m=0;$m<count($adtlpindah);$m++)
                    {
                        if($numkd == 0)
                        {
                            $this->history_mutasi_model->simpan_pindah_dtl($adtlpindah[$m]);
                        }
                        //$this->history_mutasi_model->simpan_pindah_dtl($adtlpindah[$m]);
                        $imeipindah = $this->barang_mutasi_model->get_dtl_imei($adtlpindah[$m]['no_faktur'],$adtlpindah[$m]['kd_barang']);
                        if($imeipindah->num_rows() > 0)
                        {
                            $aimeipindah = $imeipindah->result_array();
                            for($n=0;$n<count($aimeipindah);$n++)
                            {
                                if($numkd == 0)
                                {
                                    //$this->history_mutasi_model->simpan_pindah_dtl($adtlpindah[$m]);
                                    $this->history_mutasi_model->simpan_pindah_dtl_imei($aimeipindah[$n]);
                                }
                            }
                        }
                    }
                }
            }
        }
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
            echo '<script type="text/javascript">alert("Ada kesalahan Ketika Memindahkan ke history!");window.location="'.base_url().'index.php/tutup_shift";</script>';
        }
        else
        {
            $this->db->trans_commit();
            redirect('tutup_shift/bukti_setor');
        }
    }
    function kirim_penjualan($kd)
    {
        //adn, 131006
        $args   = explode('.',$kd);        
        $this->load->model('kasir_model','kasir');
        $this->load->model('kasir_elektrik_model');
        $tanggal                    = $args[0];
        $shift                      = $args[1];
        $query                      = $this->kasir->kirim_get($tanggal,$shift);
        $querys                     = $this->kasir_elektrik_model->kirim_get($tanggal,$shift);
        $barang                     = array('barang'=>$query,'elektrik'=>$querys);
        $data                       = json_encode($barang);
       //var_dump($data);die();
//        $url                        = $this->app_model->system('service_url') . 'api/service_jual/jual/format/json';       
        $url                        = $this->app_model->system('ka_service_url').'KirimPenjualan'; 
        $this->curl->create($url);  
        $this->curl->post($data); 
//        echo $url;die();
        $result                     = "";
        $result                     = json_decode($this->curl->execute());

        if(trim(strtoupper($result)) == 'SUKSES')  
        {  
            $db['log_col']= LAP_JUAL;
            $db['log_val']= 'SUKSES';
            $db['tgl']    = $tanggal;
            $db['shift']  = $shift;
            $db['tipe']   = LAP_JUAL;
            $this->log->simpanLogJual($db);
            echo 'Proses Pengiriman Sukses!';  
        }  
        else  
        {  
            echo 'Proses Pengiriman sudah dilakukan atau ada error ketika dikirim!' . ' > Error: ' .$result;
        }
    } 
    function bukti_setor()
    {
        $dataglobal     = $this->app_model->general();
        $this->load->model('outlet_model');
        $this->load->model('jual_model', 'jual');
        $namagudang     = $this->outlet_model->outlet_ambil($this->session->userdata('outlet_kd'))->row()->outlet_nm;
        
        $jmh_bon        = $this->jual->jmh_bon($this->session->userdata('tanggal'),$this->session->userdata('shift'));
        $jmh_penjualan  = $this->jual->jmh_penjualan($this->session->userdata('tanggal'),$this->session->userdata('shift'));
        $jmh_tunai      = $this->jual->jmh_tunai($this->session->userdata('tanggal'),$this->session->userdata('shift'));
        $jmh_debet      = $this->jual->jmh_debet($this->session->userdata('tanggal'),$this->session->userdata('shift'));
        $jmh_kredit     = $this->jual->jmh_kredit($this->session->userdata('tanggal'),$this->session->userdata('shift'));
        $update_harga_terakhir = ($this->log->get('UPDATE HARGA','','kd_log')->num_rows()>0) ? $this->log->get('UPDATE HARGA','','kd_log')->row()->tgl : '';
        $ositerakhir    = $this->jual->osi_terakhir($this->session->userdata('tanggal'),$this->session->userdata('shift'));
        $faktur_akhir               = $this->barang_mutasi_model->get_max_faktur_dari_pusat()->row()->no_faktur;
        $faktur_akhir               = ($faktur_akhir=='') ? '0' : $faktur_akhir;
        $faktur_akhir_history       = $this->barang_mutasi_model->get_max_faktur_dari_pusat_history()->row()->no_faktur;
        $faktur_akhir_history       = ($faktur_akhir_history=='') ? '0' : $faktur_akhir_history;
        $txterakhir     = ($faktur_akhir > $faktur_akhir_history) ? $faktur_akhir : $faktur_akhir_history;
        
        $itemupdateharga = $this->log->get_item_update_harga($this->session->userdata('tanggal'),$this->session->userdata('shift'))->num_rows();
        $tgltutupshift          = $this->log->get('SHIFT','SHIFT','tgl')->row()->tgl_edit;

        $handle         = printer_open($dataglobal['nama_printer']);
        printer_set_option($handle, PRINTER_MODE, "RAW");
        printer_start_doc($handle, "CetakBuktiStor");
        printer_start_page($handle);
  

        $cetak          = $this->app_model->maksimal(40,'Bukti Setoran','tengah');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->garis_empatpuluh();
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,'Toko: '.$namagudang,'kiri');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,'Tanggal: ' . $this->session->userdata('tanggal'),'kiri');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,'Shift: ' . $this->session->userdata('shift'),'kiri');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,'Tutup Shift: ' . $tgltutupshift,'kiri');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->garis_empatpuluh();
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(11,'Jumlah Bon:','kiri');
        $cetak          .= $this->app_model->maksimal(29,number_format($jmh_bon,0,',','.'),'kanan');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(17,'Jumlah Penjualan: ','kiri');
        $cetak          .= $this->app_model->maksimal(23,number_format($jmh_penjualan,0,',','.'),'kanan');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(12,'Jumlah Uang: ','kiri');
        $cetak          .= $this->app_model->maksimal(28,number_format($jmh_tunai,0,',','.'),'kanan');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(13,'Jumlah Debet: ','kiri');
        $cetak          .= $this->app_model->maksimal(27,number_format($jmh_debet,0,',','.'),'kanan');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(14,'Jumlah Kredit: ','kiri');
        $cetak          .= $this->app_model->maksimal(26,number_format($jmh_kredit,0,',','.'),'kanan');
//       $cetak          .= '<br/>';
        $jumlahOTX      = $this->barang_mutasi_model->get_history('',$this->session->userdata('tanggal'),'','','','','1');
//        $cetak          .= '<br/>';
//        $totalOTX       = 0;
//        foreach($jumlahOTX->result() as $rowOTX)
//        {
//            $totalOTX   += (int)$rowOTX->jmh;
//        }
        $cetak          .= $this->app_model->maksimal(14,'Jumlah OTX : ','kiri');
        $cetak          .= $this->app_model->maksimal(26,number_format($jumlahOTX->num_rows(),0,',','.'),'kanan');
//        $cetak          .= '<br/>';
        $jumlah_diskon  = $this->history_kasir_model->get_diskon($this->session->userdata('tanggal'));
        $jumlah_faktur_diskon  = ($jumlah_diskon->num_rows()>0) ? $jumlah_diskon->row()->jmhfaktur : 0;
        $nilai_diskon   = ($jumlah_diskon->num_rows()>0) ? $jumlah_diskon->row()->nilaidiskon : 0;
        $cetak          .= $this->app_model->maksimal(40,'Jumlah Faktur didiskon :' .$jumlah_faktur_diskon,'kiri');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,'Nilai Diskon : '.$nilai_diskon,'kiri');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->garis_empatpuluh();
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(20,'Update Harga Terakhir','kiri');
        $cetak          .= $this->app_model->maksimal(20,$update_harga_terakhir,'kanan');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(20,'Faktur Mutasi Terakhir','kiri');
        $cetak          .= $this->app_model->maksimal(20,$txterakhir,'kanan');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(20,'Faktur Jual Terakhir','kiri');
        $cetak          .= $this->app_model->maksimal(20,$ositerakhir,'kanan');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(20,'Jumlah Item ter-Update','kiri');
        $cetak          .= $this->app_model->maksimal(20,$itemupdateharga,'kanan');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->garis_empatpuluh();
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(20,'Kasir','tengah');
        $cetak          .= $this->app_model->maksimal(20,'Penerima','tengah');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(20,$this->session->userdata('user_nm'),'tengah');
        $cetak          .= $this->app_model->maksimal(20,'__________','tengah');
//        $cetak          .= '<br/>';
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
        $cetak          .= $this->app_model->maksimal(40,' ','kiri');
//        $cetak          .= '<br/>';
//        echo $cetak;
//        die();
        printer_write($handle,$cetak);
        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);
        //redirect('tutup_shift/kosongkan_transaksi');
        redirect('tutup_shift');
    }

    function kosongkan_transaksi()
    {
        /*
         |
         | 
         |
        */ 
        $this->db->trans_begin();
        //$this->db->delete('im_tpindah_barang_dtl_imei');
        //$this->db->delete('im_tpindah_barang_dtl');
        $this->db->delete('im_tpindah_barang'); 
        //$this->db->delete('ac_tjual_dtl_imeis');
        //$this->db->delete('ac_tjual_dtl');
        $this->db->delete('ac_tjual'); 
        //$this->db->truncate('im_tsaldo_barang'); 
        //$this->db->truncate('im_tsaldo_barang_dtl');
        //$this->db->truncate('im_tsaldo_barang_dtl_imei');
        if($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo '<script type="text/javascript">alert("Ada kesalahan Ketika menghapus Transaksi!");window.location="'.base_url().'index.php/tutup_shift";</script>';
        }
        else
        {
            $this->db->trans_commit();
            redirect('tutup_shift');
        }
    }
    function saldo_awal_baru()
    {
        $this->db->trans_begin();
        $this->load->model('barang_saldo_model');
        $data                           = $this->barang_saldo_model->saldo_hari_ini();
        for($i=0;$i<count($data);$i++)
        {
//            if($data[$i]['penyesuaian']!=0)
//            {
//                $saldo = $data[$i]['saldo_awal']+$data[$i]['penyesuaian'];
//                echo $saldo;
//                if($data[$i]['saldo_shift']=='1')
//                {
//                    $dataSaldoAwal['saldo_tgl'] = $data[$i]['saldo_tgl'];
//                    $dataSaldoAwal['saldo_shift'] = '2'; 
//                }
//                else
//                {
//                    $etgl                       = explode('-',$data[$i]['saldo_tgl']);
//                    $dataSaldoAwal['saldo_tgl'] = date('Y-m-d',mktime(0,0,0,$etgl[1],$etgl[2]+1,$etgl[0],0));
//                    $dataSaldoAwal['saldo_shift'] = '1';
//                }
//                echo $dataSaldoAwal['saldo_tgl'];
//                echo $dataSaldoAwal['saldo_shift'];
//                die();
//            }
            if($data[$i]['saldo_qty']!=0)
            {
                
                $dataSaldoAwal['saldo_barang']  = $data[$i]['saldo_barang'];
                $dataSaldoAwal['saldo_gudang']  = $data[$i]['saldo_gudang'];
                $dataSaldoAwal['saldo_qty']     = (int)$data[$i]['saldo_awal']+(int)$data[$i]['penyesuaian'];
//                if($data[$i]['saldo_shift']=='1')
//                {
//                    $dataSaldoAwal['saldo_tgl'] = $data[$i]['saldo_tgl'];
//                    $dataSaldoAwal['saldo_shift'] = '2'; 
//                }
//                else
//                {
//                    $etgl                       = explode('-',$data[$i]['saldo_tgl']);
//                    $dataSaldoAwal['saldo_tgl'] = date('Y-m-d',mktime(0,0,0,$etgl[1],$etgl[2]+1,$etgl[0],0));
//                    $dataSaldoAwal['saldo_shift'] = '1';
//                }
                $dataSaldoAwal['saldo_tgl']     = $data[$i]['saldo_tgl'];
                $dataSaldoAwal['saldo_shift']   = $data[$i]['saldo_shift'];
                $this->barang_saldo_model->update($dataSaldoAwal);
                echo $this->db->last_query();
                echo '<br/>';
                //$this->db->query('truncate table im_tsaldo_barang');
                //$this->db->query('truncate table im_tsaldo_barang_dtl');
                //$this->db->query('truncate table im_tsaldo_barang_dtl_imei');
            }
        }
        if($this->db->trans_status()===FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }
    
    function laporan_penjualan_berkala($tgldari='',$tglsampai='')
    {
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
        $data                   = $this->app_model->general();
        $data['belum_dikirim']  = $this->log->getLaporanBelumDikirim();
        $data['judulweb']       = ' | Laporan Penjualan Berkala ';
        $data['halaman']        = 'Tutup_shift/index_berkala';
        $this->load->view('layout/index',$data);
    }
    function kirim_penjualan_berkala($kd)
    {
        $args   = explode('.',$kd);        
        $this->load->model('kasir_model','kasir');
        $this->load->model('kasir_elektrik_model');
        
        $tanggal                    = $args[0];
        $shift                      = $args[1];
        $url                        = $this->app_model->system('service_url') . 'api/service_jual/jual/format/json';
        //echo $url;die();
        $query                      = $this->kasir->kirim_get($tanggal,$shift);
        $querys                     = $this->kasir_elektrik_model->kirim_get($tanggal,$shift);
        //echo $this->db->last_query();print_r($querys);die();
        $barang                     = array('barang'=>$query,'elektrik'=>$querys);
        $data                       = json_encode($barang);
        
        $this->curl->create($url);  
        $this->curl->post($data);  
        
        $result                     = json_decode($this->curl->execute());
        if(isset($result) && $result == 'Sukses')  
        {  
            $arr['log_col']= LAP_JUAL;
            $arr['log_val']= 'SUKSES';
            $arr['tgl']    = $tanggal;
            $arr['shift']  = $shift;
            $arr['tipe']   = LAP_JUAL;
            $this->log->simpanLogJual($arr);
            echo 'Proses Pengiriman Sukses!';  
        }  
        else  
        {  
            echo /*'Proses Pengiriman sudah dilakukan atau ada error ketika dikirim!'*/ $result;
        }
    } 
    function kirim_retur($kd)
    {
        
        $args   = explode('.',$kd);        
        $tanggal                    = $args[0];
        $shift                      = $args[1];
        
        $query                      = $this->tukar->getKirim($tanggal,$shift);
               
        //$barang                     = array('barang'=>$query,'elektrik'=>$querys);
        $data                       = json_encode($query);
        //print_r($data);die();     
        $url                        = $this->app_model->system('ka_service_url').'KirimRetur'; 
        $this->curl->create($url);  
        $this->curl->post($data); 
//        echo $url;die();
        $result                     = "";
        $result                     = json_decode($this->curl->execute());

        if(trim(strtoupper($result)) == 'SUKSES')  
        {  
            $db['log_col']= LAP_RETUR;
            $db['log_val']= 'SUKSES';
            $db['tgl']    = $tanggal;
            $db['shift']  = $shift;
            $db['tipe']   = LAP_RETUR;
            $this->log->simpanLogJual($db);
            echo 'Proses Pengiriman Sukses!';  
        }  
        else  
        {  
            echo 'Proses Pengiriman sudah dilakukan atau ada error ketika dikirim!' . ' > Error: ' .$result;
        }
    } 
}

?>