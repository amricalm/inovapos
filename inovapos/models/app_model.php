<?php

class App_model extends CI_Model
{
    var $global;
    function __construct()
    {
        parent::__construct();
        $this->load->model('log_proses_model','log');
    }
    function general()
    {
        $CI                                 =& get_instance();
        $separator                          = explode('/',$this->config->item('base_url'));
        $this->global['base_css']           = $this->config->item('base_url').'inovapos_asset/css';
        $this->global['base_img']           = $this->config->item('base_url').'inovapos_asset/img';
        $this->global['base_js']            = $this->config->item('base_url').'inovapos_asset/js';
        $this->global['base_upload']        = $_SERVER['DOCUMENT_ROOT'].'/'.$separator[count($separator)-2].'/inovapos_data/';
        $this->global['nama_printer']       = ($this->system('nama_printer')!='')?$this->system('nama_printer'):'EPSON TM-U220 Receipt';
        $this->global['tutup_shift']        = $this->log->cekTutupShift($CI->session->userdata('tanggal'),$CI->session->userdata('shift'));
        $this->global['tutup_stok']         = $this->log->cekTutupStok($CI->session->userdata('tanggal'),'');
        $this->global['versi']              = $this->config->item('versi');                                                /*$this->system('versi')*/
        $this->global['cd']                 = $this->system('customer_display');                    /* SETINGAN UNTUK CUSTOMER DISPLAY */
        $this->global['port_cd']            = 'COM4';                                               /* PORT untuk CUSTOMER DISPLAY */
        $this->global['gift']               = $this->system('gift');                                /* SETINGAN UNTUK GIFT */
        return $this->global;
    }
    function menu($no,$pilihbesar='',$pilihkecil='',$pilihikon='')
    {
        $menu                               = array();
        switch($no)
        {
            case 'besar' :
                $menu                       = array(
                                            1   => array('nama'=>'Dashboard','link'=>base_url().'index.php/home','ikon'=>'','extra'=>'','detail'=>array()),
                                            2   => array('nama'=>'Barang','link'=>base_url().'index.php/barang','ikon'=>'','extra'=>'','detail'=>array(
                                                1   => array('nama'=>'Barang Biasa','link'=>base_url().'index.php/barang','ikon'=>'','extra'=>''),
                                                2   => array('nama'=>'Barang Elektrik','link'=>base_url().'index.php/barang/daftar_elektrik','ikon'=>'','extra'=>'')
                                            )),
                                            3   => array('nama'=>'Transaksi','link'=>'#','ikon'=>'','extra'=>'id="kasir"','detail'=>array(
                                                1   => array('nama'=>'Kasir','link'=>base_url().'index.php/kasir/kasir_temp?iframe=true&amp;width=100%&amp;height=100%','ikon'=>'','extra'=>'id="kasir" rel="prettyPhoto[iframe]"'),
                                                2   => array('nama'=>'Kasir Elektrik','link'=>base_url().'index.php/kasir/kasir_elektrik?iframe=true&amp;width=100%&amp;height=100%','ikon'=>'','extra'=>'id="kasir_elektrik" rel="prettyPhoto[iframe]"'),
                                                3   => array('nama'=>'Tukar Barang','link'=>base_url().'index.php/tukar/input?iframe=true&amp;width=100%&amp;height=100%%','ikon'=>'','extra'=>'id="tukar_barang" rel="prettyPhoto[iframe]"')
                                            )),
                                            4   => array('nama'=>'Pelanggan','link'=>base_url().'index.php/pelanggan','ikon'=>'','extra'=>'id="pelanggan"','detail'=>array()),
                                            5   => array('nama'=>'Stok','link'=>'#','ikon'=>'','extra'=>'','detail'=>array(
                                                1   => array('nama'=>'Awal','link'=>base_url().'index.php/barang/stok_opname','ikon'=>'','extra'=>''),
                                                2   => array('nama'=>'Opname','link'=>base_url().'index.php/barang/stok_penyesuaian','ikon'=>'','extra'=>'')
                                            )),
                                            6   => array('nama'=>'Sinkronisasi','link'=>base_url().'index.php/sinkronisasi','ikon'=>'','extra'=>'','detail'=>array(
                                                1   => array('nama'=>'Update Harga','link'=>base_url().'index.php/sinkronisasi/halaman_update','ikon'=>'','extra'=>''),
                                                2   => array('nama'=>'Stok','link'=>base_url().'index.php/sinkronisasi/halaman_stok','ikon'=>'','extra'=>''),
                                                3   => array('nama'=>'Mutasi Barang','link'=>base_url().'index.php/sinkronisasi/halaman_mutasi','ikon'=>'','extra'=>''),
                                            )),
                                            7   => array('nama'=>'Pengguna','link'=>base_url().'index.php/user','ikon'=>'','extra'=>'id="pengguna"','detail'=>array()),
                                            8   => array('nama'=>'Tutup Shift','link'=>base_url().'index.php/tutup_shift','ikon'=>'','extra'=>'','detail'=>array())
                                            );
                break;
            case 'kecil' :
                $menu                       = array(
                                            1   => array('nama'=>'Barang','link'=>base_url().'index.php/barang','ikon'=>'','extra'=>''),
                                            2   => array('nama'=>'Grup Barang','link'=>base_url().'index.php/group_barang','ikon'=>'','extra'=>''),
                                            //3   => array('nama'=>'Outlet/Gudang','link'=>base_url().'index.php/outlet','ikon'=>'','extra'=>''),
                                            //4   => array('nama'=>'Grup Outlet/Gudang','link'=>base_url().'index.php/klp_outlet','ikon'=>'','extra'=>''),
                                            //3   => array('nama'=>'Sinkronisasi','link'=>base_url().'index.php/sinkronisasi','ikon'=>'','extra'=>''),
                                            3   => array('nama'=>'Teks Promosi','link'=>base_url().'index.php/promosi','ikon'=>'','extra'=>''),
                                            4   => array('nama'=>'Kartu Stock','link'=>base_url().'index.php/barang/kartu_stock','ikon'=>'','extra'=>''),
                                            5   => array('nama'=>'Laporan Penjualan','link'=>base_url().'index.php/laporan/penjualan','ikon'=>'','extra'=>''),
                                            6   => array('nama'=>'Penjualan Elektrik','link'=>base_url().'index.php/laporan/laporan_elektrik','ikon'=>'','extra'=>''),
                                            7   => array('nama'=>'Rekap Penjualan','link'=>base_url().'index.php/laporan/rekap_penjualan','ikon'=>'','extra'=>''),
                                            8   => array('nama'=>'Tukar Barang','link'=>base_url().'index.php/tukar/df','ikon'=>'','extra'=>'')
                                            );
                break;
            case 'ikon' :
                $menu                       = array(
                                            1   => array('nama'=>'Barang','link'=>base_url().'index.php/barang','ikon'=>'barang.png','extra'=>''),
                                            2   => array('nama'=>'Kasir','link'=>base_url().'index.php/kasir/kasir_temp?iframe=true&amp;width=100%&amp;height=100%','ikon'=>'keranjang.png','extra'=>'rel="prettyPhoto[iframe]"'),
                                            3   => array('nama'=>'Kasir Elektrik','link'=>base_url().'index.php/kasir/kasir_elektrik?iframe=true&amp;width=100%&amp;height=100%','ikon'=>'keranjang_elektrik.png','extra'=>'rel="prettyPhoto[iframe]"'),
                                            4   => array('nama'=>'Keamanan','link'=>'#','ikon'=>'kunci.png','extra'=>''),
                                            5   => array('nama'=>'Penjualan','link'=>base_url().'index.php/laporan/penjualan','ikon'=>'Crystal_Clear_files.gif','extra'=>''),
                                            6   => array('nama'=>'Sinkronisasi','link'=>base_url().'index.php/sinkronisasi','ikon'=>'sync.png','extra'=>''),
                                            7   => array('nama'=>'Absen','link'=>base_url().'index.php/karyawan/absen','ikon'=>'Crystal_Clear_calendar.gif','extra'=>'')
                                            );
                break;
        }
        
        return $menu;
    }
    function list_provinsi($pilih)
    {
        $list                               = '';
        $data                               = array(
                                                    '',
                                                    'Aceh',
                                                    'Sumatera Utara',
                                                    'Sumatera Barat',
                                                    'Riau',
                                                    'Jambi',
                                                    'Sumatera Selatan',
                                                    'Bengkulu',
                                                    'Lampung',
                                                    'Kepulauan Bangka Belitung',
                                                    'Kepulauan Riau',
                                                    'DKI Jakarta',
                                                    'Jawa Barat',
                                                    'DIY',
                                                    'Jawa Tengah',
                                                    'Jawa Timur',
                                                    'Banten',
                                                    'Bali',
                                                    'NTB',
                                                    'NTT',
                                                    'Kalimantan Barat',
                                                    'Kalimantan Tengah',
                                                    'Kalimantan Selatan',
                                                    'Kalimantan Timur',
                                                    'Sulawesi Utara',
                                                    'Sulawesi Tengah',
                                                    'Sulawesi Selatan',
                                                    'Sulawesi Tenggara',
                                                    'Gorontalo',
                                                    'Sulawesi Barat',
                                                    'Maluku',
                                                    'Maluku Utara',
                                                    'Papua Barat',
                                                    'Papua'
                                                );
        for($i=0;$i<count($data);$i++)
        {
            $selected                   = ($pilih==$data[$i]) ? 'selected="selected"' : '';
            $list                       .= '<option value="'.$data[$i].'" '.$selected.'>'.$data[$i].'</option>';
        }
        return $list;
    }
    function list_status_menikah($pilih)
    {
        $list                               = '';
        $data                               = array(
                                                    '',
                                                    'Menikah',
                                                    'Belum Menikah'
                                                );
        for($i=0;$i<count($data);$i++)
        {
            $selected                   = ($pilih==$data[$i]) ? 'selected="selected"' : '';
            $list                       .= '<option value="'.$data[$i].'" '.$selected.'>'.$data[$i].'</option>';
        }
        return $list;
    }
    function maksimal($jmhteks,$teks,$align)
    {
        $arrayteks          = str_split($teks);
        $hasilteks          = '';
        $jmhkarakter        = count($arrayteks);
        if($jmhkarakter > $jmhteks)
        {
            $jmhkarakter    = $jmhteks;
        }
        if($align=='kiri')
        {
            for($i=0;$i<$jmhkarakter;$i++)
            {
                $hasilteks      .= $arrayteks[$i];
            }
            for($j=$i;$j<$jmhteks;$j++)
            {
                $hasilteks      .= ' ';
            }
        }
        elseif($align=='kanan')
        {
            $sisa               = $jmhteks - $jmhkarakter;
            for($i=0;$i<$sisa;$i++)
            {
                $hasilteks      .= ' ';
            }
            $z                  = 0;
            for($j=$i;$j<$jmhteks;$j++)
            {
                $hasilteks      .= $arrayteks[$z];
                $z++;
            }
        }
        else
        {
            $sisa               = $jmhteks - $jmhkarakter;
            $sisaperdua         = floor($sisa/2);
            for($i=0;$i<$sisaperdua;$i++)
            {
                $hasilteks      .= ' ';
            }
            $z                  = 0;
            for($j=$i;$j<($jmhkarakter+$sisaperdua);$j++)
            {
                $hasilteks      .= $arrayteks[$z];
                $z++;
            }
            for($k=$j;$k<$jmhteks;$k++)
            {
                $hasilteks      .= ' ';
            }
        }
        return $hasilteks;
    }
    function garis_empatpuluh()
    {
        $garis              = '';
        for($i=1;$i<=40;$i++)
        {
            $garis          .= '-';
        }
        return $garis;
    }
    function max_lima_karakter($teks)
    {
        $banyakteks                     = strlen($teks);
        if($banyakteks==1)
        {
            $teks                       = '0000'.$teks;
        }
        elseif($banyakteks==2)
        {
            $teks                       = '000'.$teks;
        }
        elseif($banyakteks==3)
        {
            $teks                       = '00'.$teks;
        }
        elseif($banyakteks==4)
        {
            $teks                       = '0'.$teks;
        }
        else
        {
            $teks                       = $teks;
        }
        return $teks;
    }
    function system($where)
    {
        $this->db->where('sys_col',$where);
        $return         = $this->db->get('sys_var');
        return ($return->num_rows()>0) ? $return->row()->sys_val : '';
    }
    
}

?>