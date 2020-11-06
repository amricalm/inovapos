<?php

class Home extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('barang_mutasi_model');
        $this->load->model('log_proses_model');
        $this->load->model('barang_saldo_model');
    }
    function index()
    {
        $this->load->model('kasir_model');
        if($this->session->userdata('user_nm')=='')
        {
            redirect('');
        }
//        $laporan                    = $this->log_proses_model->getlaporan($this->session->userdata('tanggal'),$this->session->userdata('shift'));
//        if($laporan->num_rows()==0)
//        {
//            echo '<script type="text/javascript">alert("Anda belum melakukan Kirim Laporan dari tanggal : '.$this->adntgl->tgl_panjang($this->session->userdata('tanggal')).',shift : '.$this->session->userdata('shift').'");</script>';
//        }
        $data                       = $this->app_model->general();
        $data['halaman']            = 'home';
        $data['judulweb']           = ' | Home';
        $this->load->view('layout/index',$data);              
    }
    function test($teks,$jmhteks)
    {
        $arrayteks          = str_split($teks);
        $hasilteks          = '';
        $jmhkarakter        = count($arrayteks);
        if($jmhkarakter > $jmhteks)
        {
            $jmhkarakter    = $jmhteks;
        }
        
        $sisa               = $jmhteks - $jmhkarakter;
        $sisaperdua         = floor($sisa/2);
        for($i=0;$i<$sisaperdua;$i++)
        {
            $hasilteks      .= '.';
        }
        //die($hasilteks);
        $z                  = 0;
        for($j=$i;$j<($jmhkarakter+$sisaperdua);$j++)
        {
            $hasilteks      .= $arrayteks[$z];
            $z++;
        }
        for($k=$j;$k<$jmhteks;$k++)
        {
            $hasilteks      .= '.';
        }
        echo $hasilteks;
    }
    function liat_stok_real()
    {
        $this->load->model('barang_saldo_model');
        $data                       = $this->app_model->general();
        $namafile                   = "saldo_real.txt";
        $ourFileName                = $data['base_upload'].$namafile;
        $ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        $stok                       = $this->barang_saldo_model->perbaikan_saldo_awal();
        for($i=0;$i<count($stok);$i++)
        {
            if($stok[$i]['saldo_qty']!=0)
            {
                fwrite($ourFileHandle,$stok[$i]['saldo_tgl'].';'.$stok[$i]['saldo_barang'].';'.$stok[$i]['saldo_qty'].';'.$stok[$i]['saldo_gudang'].';'.$stok[$i]['saldo_shift'].';'."\r\n");
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
    }
    function cek_transaction()
    {
        $this->db->trans_begin();
        $user['user_kd'] = 'gins';
        $user['user_nm'] = 'gins';
        $user['user_password'] = '';
        $user['user_group'] = 1;
        $user['user_outlet'] = '1003';
        $this->db->insert('muser',$user);
        /*
        |
        | Saldo Barang
        |
        */
        $data['saldo_tgl'] = '2012-07-07';
        $data['saldo_barang'] = 'xxx';
        $data['saldo_qty'] = '222';
        $data['saldo_shift'] = '2';
        $data['saldo_gudang'] = '1003';
        if(!$this->db->insert('im_msaldo_barang',$data))
        {
            show_error('Ada Kesalahan pada Databasenya!');
        }
        if($this->db->trans_status() === false)
        {
            $this->db->trans_rollback();
            echo ' Gagal !';
        }
        else
        {
            $this->db->trans_commit();
            echo ' Sukses !';
        }
    }
    function test_lagi()
    {
        $this->load->model('barang_mutasi_model');
        $jumlahOTX      = $this->barang_mutasi_model->get_history('','2012-12-22',$this->session->userdata('outlet_kd'));
        //echo $this->db->last_query();die();
        echo $jumlahOTX->num_rows();
    }
}

?>