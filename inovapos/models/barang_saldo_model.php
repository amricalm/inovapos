<?php

class Barang_saldo_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_saw($tgl=0, $shift='', $kd_gudang='')
    {
        $this->db->where('saldo_tgl',$tgl);
        $this->db->where('saldo_shift',$shift);
        $this->db->where('saldo_gudang',$kd_gudang);
        $this->db->order_by('saldo_barang');
        $qry= $this->db->get('im_msaldo_barang');
        
        $data = array();
        $i =0;
        foreach($qry->result() as $row)
        {
            $data[$i]['saldo_qty']      = $row->saldo_qty;
            $data[$i]['saldo_barang']   = $row->saldo_barang;
            $data[$i]['saldo_gudang']   = $row->saldo_gudang;
            $i++;
        }
        
        return $data;
    }
    
    function get_tgl_saw()
    {
        $this->db->where('sys_col','periode_saldo_awal_barang');
        $tgl = $this->db->get('sys_var')->row()->sys_val;
        return $tgl;
    }
    function saldo_awal($kdbarang,$tgl,$tipe)
    {
        $CI                         =& get_instance();  
        $tglsaldoawal               = $this->get_tgl_saw();
        $etgls                      = explode('-',$tgl);
        $tgl_sebelumnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]-1,$etgls[0],0));
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0));
        /*
         * Lihat Qty Saldo
         */
        $this->db->select('saldo_qty');
        $this->db->where('saldo_barang',$kdbarang);
        $this->db->where('saldo_gudang',$CI->session->userdata('outlet_kd'));
        if($tipe=='saldoawal')
        {
            $this->db->where("saldo_tgl >= '".$tglsaldoawal."'",'',false);
            $this->db->where("saldo_tgl < '".$tgl."'",'',false);
        }
        elseif($tipe=='sekarang')
        {
            $this->db->where("saldo_tgl >= '".$tgl."'",'',false);
            $this->db->where("saldo_tgl < '".$tgl_sesudahnya."'",'',false);
        }
        else
        {
            $this->db->where("saldo_tgl >= '".$tglsaldoawal."'",'',false);
            $this->db->where("saldo_tgl < '".$tgl_sesudahnya."'",'',false);
        }
        return $this->db->get('im_msaldo_barang');        
    }
    function penjualan($kdbarang,$tgl,$tipe)
    {
        $CI                         =& get_instance();  
        $tglsaldoawal               = $this->get_tgl_saw();
        $etgls                      = explode('-',$tgl);
        $tgl_sebelumnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]-1,$etgls[0],0));
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0));
        /*
         * Lihat Penjualan
         */
        $this->db->select_sum('qty');
        $this->db->join('ac_tjual','ac_tjual_dtl.no_faktur=ac_tjual.no_faktur','inner');
        if($tipe=='saldoawal')
        {
            $this->db->where("tgl >= '".$tglsaldoawal."'",'',false);
            $this->db->where("tgl < '".$tgl."'",'',false);
        }
        elseif($tipe=='sekarang')
        {
            $this->db->where("tgl >= '".$tgl."'",'',false);
            $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        }
        else
        {
            $this->db->where("tgl >= '".$tglsaldoawal."'",'',false);
            $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        }
        
        //$this->db->where("tgl >= '".$tgl."'",'',false);
        //$this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        return $this->db->get('ac_tjual_dtl');
    }
    function mutasi_keluar($kdbarang,$tgl,$tipe)
    {
        $CI                         =& get_instance();  
        $tglsaldoawal               = $this->get_tgl_saw();
        $etgls                      = explode('-',$tgl);
        $tgl_sebelumnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]-1,$etgls[0],0));
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0));     
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        if($tipe=='saldoawal')
        {
            $this->db->where("tgl >= '".$tglsaldoawal."'",'',false);
            $this->db->where("tgl < '".$tgl."'",'',false);
        }
        elseif($tipe=='sekarang')
        {
            $this->db->where("tgl >= '".$tgl."'",'',false);
            $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        }
        else
        {
            $this->db->where("tgl >= '".$tglsaldoawal."'",'',false);
            $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        }
        //$this->db->where("tgl >= '".$tgl."'",'',false);
        //$this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_asal',$CI->session->userdata('outlet_kd'));
        return $this->db->get('im_tpindah_barang_dtl');
    }
    function mutasi_masuk($kdbarang,$tgl,$tipe)
    {
        $CI                         =& get_instance();  
        $tglsaldoawal               = $this->get_tgl_saw();
        $etgls                      = explode('-',$tgl);
        $tgl_sebelumnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]-1,$etgls[0],0));
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0)); 
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        if($tipe=='saldoawal')
        {
            $this->db->where("tgl >= '".$tglsaldoawal."'",'',false);
            $this->db->where("tgl < '".$tgl."'",'',false);
        }
        elseif($tipe=='sekarang')
        {
            $this->db->where("tgl >= '".$tgl."'",'',false);
            $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        }
        else
        {
            $this->db->where("tgl >= '".$tglsaldoawal."'",'',false);
            $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        }
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_tujuan',$CI->session->userdata('outlet_kd'));
        return $this->db->get('im_tpindah_barang_dtl');
    }
    function saldo_penyesuaian($kdbarang,$tgl,$tipe,$tipesaldo)
    {
        $CI                         =& get_instance();  
        $tglsaldoawal               = $this->get_tgl_saw();
        $etgls                      = explode('-',$tgl);
        $tgl_sebelumnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]-1,$etgls[0],0));
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0));  
        
        /*
            Lihat Saldo Penyesuaian
         */
        
        $this->db->select_sum('qty');
        $this->db->join('im_tsaldo_barang','im_tsaldo_barang.no_faktur=im_tsaldo_barang_dtl.no_faktur','inner');
        if($tipe=='saldoawal')
        {
            $this->db->where("tgl >= '".$tglsaldoawal."'",'',false);
            $this->db->where("tgl < '".$tgl."'",'',false);
        }
        elseif($tipe=='sekarang')
        {
            $this->db->where("date(tgl) = '".$tgl."'",'',false);
            //$this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        }
        $this->db->where('kd_barang',$kdbarang);
//        if($tipesaldo=='masuk')
//        {
//            $this->db->where('qty > 0','',false);
//        }
//        elseif($tipesaldo=='keluar')
//        {
//            $this->db->where('qty < 0','',false);
//        }
        return $this->db->get('im_tsaldo_barang_dtl');
    }
    function kartu_stok($kdbarang,$tgl_sekarang)
    {
        $CI                         =& get_instance();
        $hasil                      = array();
        $tgl                        = $tgl_sekarang;
        
        $saldoawal                  = $this->saldo_awal($kdbarang,$tgl,'');
        $saldoawalsekarang          = $this->saldo_awal($kdbarang,$tgl,'sekarang');
        
        $penjualan                  = $this->penjualan($kdbarang,$tgl,'saldoawal');
        $penjualansekarang          = $this->penjualan($kdbarang,$tgl,'sekarang');
        
        $mutasikeluar               = $this->mutasi_keluar($kdbarang,$tgl,'saldoawal');
        $mutasikeluarsekarang       = $this->mutasi_keluar($kdbarang,$tgl,'sekarang');
        
        $mutasimasuk                = $this->mutasi_masuk($kdbarang,$tgl,'saldoawal');
        $mutasimasuksekarang        = $this->mutasi_masuk($kdbarang,$tgl,'sekarang');
        
        $penyesuaianmasuk           = $this->saldo_penyesuaian($kdbarang,$tgl,'saldoawal','masuk');
        $penyesuaianmasuksekarang   = $this->saldo_penyesuaian($kdbarang,$tgl,'sekarang','masuk');
        
        $penyesuaiankeluar          = $this->saldo_penyesuaian($kdbarang,$tgl,'saldoawal','keluar');
        $penyesuaiankeluarsekarang  = $this->saldo_penyesuaian($kdbarang,$tgl,'sekarang','keluar');
        
        $hasil['nilaisaldoawal']    = ($saldoawal->num_rows() > 0) ? $saldoawal->row()->saldo_qty : 0;
        $hasil['nilaipenjualan']    = ($penjualan->num_rows() > 0) ? $penjualan->row()->qty : 0;
        $hasil['nilaimutasikeluar'] = ($mutasikeluar->num_rows() > 0) ? $mutasikeluar->row()->qty : 0;
        $hasil['nilaimutasimasuk']  = ($mutasimasuk->num_rows() > 0) ? $mutasimasuk->row()->qty : 0;
        $hasil['nilaipenyesuaianmasuk']  = ($penyesuaianmasuk->num_rows() > 0) ? $penyesuaianmasuk->row()->qty : 0;
        $hasil['nilaipenyesuaiankeluar']  = ($penyesuaiankeluar->num_rows() > 0) ? $penyesuaiankeluar->row()->qty : 0;
        
        $hasil['nilaisaldoawalsekarang'] = ($saldoawalsekarang->num_rows() > 0) ? $saldoawalsekarang->row()->saldo_qty : 0;
        $hasil['nilaipenjualansekarang'] = ($penjualansekarang->num_rows() > 0) ? $penjualansekarang->row()->qty : 0;
        $hasil['nilaimutasikeluarsekarang'] = ($mutasikeluarsekarang->num_rows() > 0) ? $mutasikeluarsekarang->row()->qty : 0;
        $hasil['nilaimutasimasuksekarang']  = ($mutasimasuksekarang->num_rows() > 0) ? $mutasimasuksekarang->row()->qty : 0;
        $hasil['nilaipenyesuaianmasuksekarang']  = ($penyesuaianmasuksekarang->num_rows() > 0) ? $penyesuaianmasuksekarang->row()->qty : 0;
        $hasil['nilaipenyesuaiankeluarsekarang']  = ($penyesuaiankeluarsekarang->num_rows() > 0) ? $penyesuaiankeluarsekarang->row()->qty : 0;
        
        $hasil['saldomasuk']        = ($hasil['nilaisaldoawal'] + $hasil['nilaimutasimasuk'] + $hasil['nilaipenyesuaianmasuk']);
        $hasil['saldokeluar']       = ($hasil['nilaipenjualan'] + $hasil['nilaimutasikeluar'] + $hasil['nilaipenyesuaiankeluar']);
        $hasil['saldo']             = $hasil['saldomasuk'] - $hasil['saldokeluar'];
        $hasil['masuk']             = $hasil['nilaisaldoawalsekarang'] + $hasil['nilaimutasimasuksekarang']+$hasil['nilaipenyesuaianmasuksekarang'];
        $hasil['keluar']            = $hasil['nilaimutasikeluarsekarang'] + $hasil['nilaipenjualansekarang'];
        
        return $hasil;
    }
    
    function get_imei_saldo($kdbarang,$kdgudang,$tgl,$imei='')
    {
        $CI                         =& get_instance();  
        $tglsaldoawal               = $this->get_tgl_saw();
        $etgls                      = explode('-',$tgl);
        $tgl_sebelumnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]-1,$etgls[0],0));
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0)); 
        $sqlbarang1                 = '';
        $sqlbarang2                 = '';
        if($kdbarang!='')
        {
            $sqlbarang1             = " AND dtl.kd_barang = '$kdbarang' ";
            $sqlbarang2             = " AND hdr.saldo_barang = '$kdbarang' ";
        }
        $sql                        = "SELECT kd_barang,barang_kd,barang_nm,barang_harga_jual as harga_jual,barang_group,imei,imei as saldo_imei
                                FROM
                                (
                                	SELECT dtl.kd_barang,imei FROM im_tpindah_barang_dtl dtl
                                	INNER JOIN im_tpindah_barang hdr
                                	ON dtl.no_faktur = hdr.no_faktur
                                	left outer JOIN im_tpindah_barang_dtl_imei dtlimei
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtlimei.no_faktur = hdr.no_faktur
                                	WHERE kd_gudang_tujuan = '$kdgudang'
                                	AND hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                	$sqlbarang1
                                UNION
                                	SELECT hdr.saldo_barang AS kd_barang, saldo_imei AS imei FROM im_msaldo_barang hdr
                                	left outer JOIN im_msaldo_barang_imei dtlimei
                                	ON dtlimei.saldo_barang = hdr.saldo_barang
                                	WHERE hdr.saldo_tgl >= '$tglsaldoawal'
                                	AND hdr.saldo_tgl < '$tgl_sesudahnya'
                                	$sqlbarang2
                                UNION
                                	SELECT dtl.kd_barang, imei FROM im_tsaldo_barang_dtl dtl
                                	INNER JOIN im_tsaldo_barang hdr
                                	ON dtl.no_faktur = hdr.no_faktur
                                	INNER JOIN im_tsaldo_barang_dtl_imei dtlimei
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtlimei.no_faktur = hdr.no_faktur
                                	AND hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                	$sqlbarang1
                                	AND dtl.qty > 0
                                ) tbl
                                INNER JOIN im_mbarang brg
                                ON kd_barang = barang_kd
                                WHERE imei NOT IN
                                (
                                	SELECT imei FROM ac_tjual_dtl_imei dtlimei
                                	INNER JOIN ac_tjual hdr
                                	ON dtlimei.no_faktur = hdr.no_faktur
                                	INNER JOIN ac_tjual_dtl dtl
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtl.no_faktur = hdr.no_faktur
                                	WHERE hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                	$sqlbarang1
                                UNION
                                	SELECT imei FROM im_tpindah_barang_dtl_imei dtlimei
                                	INNER JOIN im_tpindah_barang hdr
                                	ON dtlimei.no_faktur = hdr.no_faktur
                                	INNER JOIN im_tpindah_barang_dtl dtl
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtl.no_faktur = hdr.no_faktur
                                	WHERE kd_gudang_asal = '$kdgudang'
                                	AND hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                	$sqlbarang1
                                UNION
                                	SELECT imei FROM im_tsaldo_barang_dtl_imei dtlimei
                                	INNER JOIN im_tsaldo_barang hdr
                                	ON dtlimei.no_faktur = hdr.no_faktur
                                	INNER JOIN im_tsaldo_barang_dtl dtl
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtl.no_faktur = hdr.no_faktur
                                	AND hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                	$sqlbarang1
                                	AND dtl.qty < 0
                                ) ";
        if($imei!='')
        {
            $sql                .= " AND imei = '$imei' ";
        }
        $sql                    .= " order by kd_barang ";
        return $this->db->query($sql);
    }
    
    function get_imei($kdgudang,$tgl,$imei='')
    {
        $CI                         =& get_instance();  
        $tglsaldoawal               = $this->get_tgl_saw();
        $etgls                      = explode('-',$tgl);
        $tgl_sebelumnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]-1,$etgls[0],0));
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0)); 
        $sql                        = "SELECT kd_barang,barang_kd,barang_nm,barang_harga_jual as harga_jual,barang_group,imei,imei as saldo_imei
                                FROM
                                (
                                	SELECT dtlimei.kd_barang,imei FROM im_tpindah_barang_dtl_imei dtlimei
                                	INNER JOIN im_tpindah_barang hdr
                                	ON dtlimei.no_faktur = hdr.no_faktur
                                	INNER JOIN im_tpindah_barang_dtl dtl
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtl.no_faktur = hdr.no_faktur
                                	WHERE kd_gudang_tujuan = '$kdgudang'
                                	AND hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                UNION
                                	SELECT dtlimei.saldo_barang AS kd_barang, saldo_imei AS imei FROM im_msaldo_barang_imei dtlimei
                                	INNER JOIN im_msaldo_barang hdr
                                	ON dtlimei.saldo_barang = hdr.saldo_barang
                                	WHERE hdr.saldo_tgl >= '$tglsaldoawal'
                                	AND hdr.saldo_tgl < '$tgl_sesudahnya'
                                UNION
                                	SELECT dtlimei.kd_barang, imei FROM im_tsaldo_barang_dtl_imei dtlimei
                                	INNER JOIN im_tsaldo_barang hdr
                                	ON dtlimei.no_faktur = hdr.no_faktur
                                	INNER JOIN im_tsaldo_barang_dtl dtl
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtl.no_faktur = hdr.no_faktur
                                	AND hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                	AND dtl.qty > 0
                                ) tbl
                                INNER JOIN im_mbarang brg
                                ON kd_barang = barang_kd
                                WHERE imei NOT IN
                                (
                                	SELECT imei FROM ac_tjual_dtl_imei dtlimei
                                	INNER JOIN ac_tjual hdr
                                	ON dtlimei.no_faktur = hdr.no_faktur
                                	INNER JOIN ac_tjual_dtl dtl
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtl.no_faktur = hdr.no_faktur
                                	WHERE hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                UNION
                                	SELECT imei FROM im_tpindah_barang_dtl_imei dtlimei
                                	INNER JOIN im_tpindah_barang hdr
                                	ON dtlimei.no_faktur = hdr.no_faktur
                                	INNER JOIN im_tpindah_barang_dtl dtl
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtl.no_faktur = hdr.no_faktur
                                	WHERE kd_gudang_asal = '$kdgudang'
                                	AND hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                UNION
                                	SELECT imei FROM im_tsaldo_barang_dtl_imei dtlimei
                                	INNER JOIN im_tsaldo_barang hdr
                                	ON dtlimei.no_faktur = hdr.no_faktur
                                	INNER JOIN im_tsaldo_barang_dtl dtl
                                	ON dtlimei.kd_barang = dtl.kd_barang
                                	AND dtl.no_faktur = hdr.no_faktur
                                	AND hdr.tgl >= '$tglsaldoawal'
                                	AND hdr.tgl < '$tgl_sesudahnya'
                                	AND dtl.qty < 0
                                )";
        if($imei!='')
        {
            $sql                .= " AND imei = '$imei' ";
        }
        return $this->db->query($sql);
    }
    function hapus_imei($data)
    {
        $this->db->where('saldo_tgl',$data['saldo_tgl']);
        $this->db->where('saldo_barang',$data['saldo_barang']);
        $this->db->where('saldo_gudang',$data['saldo_gudang']);
        $this->db->where('saldo_imei',$data['saldo_imei']);
        return $this->db->delete('im_msaldo_barang_imei');
    }
    
    function get2($tgl='', $shift='',$kdgroup='',$cari, $limit='',$offset='')
    {
        $CI             = & get_instance();
        $kdgudang       = $CI->session->userdata('outlet_kd');
        $this->db->select('barang_kd,barang_nm,group_nm,saldo_qty,saldo_gudang,barang_group');
        $this->db->from('im_mbarang');
        $join           = "barang_kd=saldo_barang and saldo_gudang = '$kdgudang' ";
        $join           .= ($tgl!='') ? " and saldo_tgl = '$tgl'" : '';
        $join           .= ($shift!='') ? " and saldo_shift = '$shift'" : '';
        $this->db->join('im_msaldo_barang',$join,'left outer');
        $this->db->join('im_mgroup_barang','barang_group=group_kd','inner');
        if($kdgroup!='0' && $kdgroup!='') {$this->db->where('barang_group',$kdgroup);}
        if($cari!='') 
        {
            $this->db->or_like('barang_nm',$cari);
            $this->db->or_like('barang_kd',$cari);
        }
        if($limit!='')
        {
            $this->db->limit($limit,$offset);
        }
        $this->db->order_by('barang_kd');
        return $this->db->get();
    }
    
    function get_stok_by_imei($kd_barang,$tgl,$shift)
    {
        $hasil=0;
        $this->db->where('saldo_barang', $kd_barang);
        $this->db->where('saldo_tgl', $tgl);
        //$this->db->where('saldo_shift', $shift); Tidak Ada Kolom Shift di Database
        $this->db->from('im_msaldo_barang_imei');
        
        $hasil  = $this->db->count_all_results();
        return $hasil;
    }

    function cek($kdbarang='',$kdgudang='',$tgl='',$shift='')
    {
        if($kdbarang!=''){$this->db->where('saldo_barang',$kdbarang);}
        if($kdgudang!=''){$this->db->where('saldo_gudang',$kdgudang);}
        if($tgl!=''){
            $this->db->where('saldo_tgl',$tgl);
        }
        if($shift!=''){
            $this->db->where('saldo_shift',$shift);
        }
        return $this->db->get('im_msaldo_barang');
    }
    
    function simpan($data)
    {
        return $this->db->insert('im_msaldo_barang',$data);
    }
    function simpan_imei($data)
    {
        return $this->db->insert('im_msaldo_barang_imei',$data);
    }
    function update($data)
    {
        //$this->db->where('saldo_kd',$data['saldo_kd']);
        $this->db->where('saldo_barang',$data['saldo_barang']);
        $this->db->where('saldo_gudang',$data['saldo_gudang']);
        $this->db->where('saldo_tgl',$data['saldo_tgl']);
        $this->db->where('saldo_shift',$data['saldo_shift']);
        return $this->db->update('im_msaldo_barang',array('saldo_qty'=>$data['saldo_qty']));
    }
    function update_imei($data)
    {
        $this->db->where('saldo_barang',$data['saldo_barang']);
        $this->db->where('saldo_gudang',$data['saldo_gudang']);
        $this->db->where('saldo_tgl',$data['saldo_tgl']);
        return $this->db->update('im_msaldo_barang_imei',array('saldo_imei'=>$data['saldo_imei']));
    }
    
    function cek_imei_saldo($imei)
    {
        $hasil = 0;
        $this->db->select('saldo_imei');
        $this->db->where('saldo_imei', $imei);
        $qry = $this->db->get('im_msaldo_barang_imei');
        if($qry->num_rows()>0)
        {
            $hasil = 1;
        }

        return $hasil;

    }
    function get_saldox($kdbarang,$tgl_sekarang)
    {
        $CI                         =& get_instance();
        $hasil                      = array();
        $tgl                        = $tgl_sekarang;
        
        $saldoawal                  = $this->saldo_awal($kdbarang,$tgl,'fix');
        $penjualan                  = $this->penjualan($kdbarang,$tgl,'fix');        
        $mutasikeluar               = $this->mutasi_keluar($kdbarang,$tgl,'fix');        
        $mutasimasuk                = $this->mutasi_masuk($kdbarang,$tgl,'fix');        
        $penyesuaianmasuk           = $this->saldo_penyesuaian($kdbarang,$tgl,'fix','masuk');        
        $penyesuaiankeluar          = $this->saldo_penyesuaian($kdbarang,$tgl,'fix','keluar');
        
        $hasil['nilaisaldoawal']    = ($saldoawal->num_rows() > 0) ? $saldoawal->row()->saldo_qty : 0;
        $hasil['nilaipenjualan']    = ($penjualan->num_rows() > 0) ? $penjualan->row()->qty : 0;
        $hasil['nilaimutasikeluar'] = ($mutasikeluar->num_rows() > 0) ? $mutasikeluar->row()->qty : 0;
        $hasil['nilaimutasimasuk']  = ($mutasimasuk->num_rows() > 0) ? $mutasimasuk->row()->qty : 0;
        $hasil['nilaipenyesuaianmasuk']  = ($penyesuaianmasuk->num_rows() > 0) ? $penyesuaianmasuk->row()->qty : 0;
        $hasil['nilaipenyesuaiankeluar']  = ($penyesuaiankeluar->num_rows() > 0) ? $penyesuaiankeluar->row()->qty : 0;
        
        $hasil['masuk']             = ($hasil['nilaisaldoawal']+$hasil['nilaimutasimasuk']);
        $hasil['keluar']            = ($hasil['nilaipenjualan']+$hasil['nilaimutasikeluar']);
        
        $hasil['saldo']             = $hasil['masuk']-$hasil['keluar'];
        
        return $hasil['saldo'];
    }
    function perbaikan_saldo_awal()
    {
        $CI     =& get_instance();
        $data                           = array();
        $shiftsekarang                  = $CI->session->userdata('shift');
        $tglsekarang                    = $CI->session->userdata('tanggal');
        $etglsekarang                   = explode('-',$tglsekarang);
        if($shiftsekarang=='1')
        {
            $shiftsebelumnya            = '2';
            $tglsebelumnya              = date('Y-m-d',mktime(0,0,0,$etglsekarang[1],$etglsekarang[2]-1,$etglsekarang[0],0));
        }
        else
        {
            $shiftsebelumnya            = '1';
            $tglsebelumnya              = $tglsekarang;
        }
        
        /*
         |
         | Looping Barang 
         |
         */
        $barang                         = $CI->db->get('im_mbarang');
        $i                              = 0;
        foreach($barang->result() as $rowbarang)
        {
            $kodebarang                 = $rowbarang->barang_kd;
            $kodegudang                 = $CI->session->userdata('outlet_kd');
            $data[$i]['saldo_barang']   = $kodebarang;
            $data[$i]['saldo_gudang']   = $kodegudang;
            $saldo_awal                 = " SELECT IFNULL(SUM(saldo_qty),0) AS qty
                                        FROM im_msaldo_barang 
                                        WHERE saldo_barang = '$kodebarang'";
            $qtysaldoawal               = ($this->db->query($saldo_awal)->num_rows()>0) ? $this->db->query($saldo_awal)->row()->qty : 0;
            $penjualan                  = " SELECT IFNULL(SUM(qty),0) AS qty
                                        FROM ac_tjual_dtl
                                        WHERE kd_barang = '$kodebarang'";
            $qtypenjualan               = ($this->db->query($penjualan)->num_rows()>0) ? $this->db->query($penjualan)->row()->qty : 0;
            $mutasimasuk                = " SELECT IFNULL(SUM(qty),0) AS qty
                                        FROM im_tpindah_barang_dtl dtl
                                        INNER JOIN im_tpindah_barang hdr
                                        ON dtl.no_faktur = hdr.no_faktur
                                        WHERE kd_gudang_tujuan = '$kodegudang'
                                        AND kd_barang = '$kodebarang'";
            $qtymutasimasuk             = ($this->db->query($mutasimasuk)->num_rows()>0) ? $this->db->query($mutasimasuk)->row()->qty : 0;
            $mutasikeluar               = " SELECT IFNULL(SUM(qty),0) AS qty
                                        FROM im_tpindah_barang_dtl dtl
                                        INNER JOIN im_tpindah_barang hdr
                                        ON dtl.no_faktur = hdr.no_faktur
                                        WHERE kd_gudang_asal = '$kodegudang'
                                        AND kd_barang = '$kodebarang'";
            $qtymutasikeluar            = ($this->db->query($mutasikeluar)->num_rows()>0) ? $this->db->query($mutasikeluar)->row()->qty : 0;
            $penyesuaian                = " SELECT IFNULL(SUM(qty),0) AS qty
                                        FROM im_tsaldo_barang_dtl
                                        WHERE kd_barang = '$kodebarang'";
            $qtypenyesuaian             = ($this->db->query($penyesuaian)->num_rows()>0) ? $this->db->query($penyesuaian)->row()->qty : 0;
            $qtysaldo                   = ((int)$qtysaldoawal+(int)$qtymutasimasuk+(int)$qtypenyesuaian) - ((int)$qtymutasikeluar+(int)$qtypenjualan);
            $data[$i]['saldo_qty']      = $qtysaldo;
            $data[$i]['saldo_shift']    = $shiftsekarang;
            $data[$i]['saldo_tgl']      = $tglsekarang;
            $i++;
        }
        return $data;
    }
    function saldo($kdbarang='',$tgl,$shift)
    {
        $CI     =& get_instance();
        $data                           = array();
        
        if($kdbarang!='')
        {
            $CI->db->where('barang_kd',$kdbarang);
        }
        $barang                         = $CI->db->get('im_mbarang');
        $i                              = 0;
        foreach($barang->result() as $rowbarang)
        {
            $kodebarang                 = $rowbarang->barang_kd;
            $kodegudang                 = $CI->session->userdata('outlet_kd');
            $data[$i]['saldo_barang']   = $kodebarang;
            $data[$i]['saldo_gudang']   = $kodegudang;
            $saldo_awal                 = " SELECT IFNULL(SUM(saldo_qty),0) AS qty
                                        FROM im_msaldo_barang 
                                        WHERE saldo_barang = '$kodebarang'
                                        AND saldo_tgl = '$tgl'
                                        AND saldo_shift = '$shift'";
            $qtysaldoawal               = ($this->db->query($saldo_awal)->num_rows()>0) ? $this->db->query($saldo_awal)->row()->qty : 0;
            $data[$i]['saldo_qty']      = $qtysaldoawal;
            $data[$i]['saldo_shift']    = $shift;
            $data[$i]['saldo_tgl']      = $tgl;
            $i++;
        }
        return $data;
    }
    function saldo_hari_ini($kdbarang='')
    {
        $CI                             =& get_instance();
        $data                           = array();
        $shiftsekarang                  = $CI->session->userdata('shift');
        $tglsekarang                    = $CI->session->userdata('tanggal');
        $etglsekarang                   = explode('-',$tglsekarang);
        if($shiftsekarang=='1')
        {
            $shiftsebelumnya            = '2';
            $tglsebelumnya              = date('Y-m-d',mktime(0,0,0,$etglsekarang[1],$etglsekarang[2]-1,$etglsekarang[0]));
        }
        else
        {
            $shiftsebelumnya            = '1';
            $tglsebelumnya              = $tglsekarang;
        }
        
        if($kdbarang!='')
        {
            $CI->db->where('barang_kd',$kdbarang);
        }
        $CI->db->join('im_mgroup_barang','group_kd=barang_group','left outer');
        $barang                         = $CI->db->get('im_mbarang');
        
        $i                              = 0;
        foreach($barang->result() as $rowbarang)
        {
            $kodebarang                 = $rowbarang->barang_kd;
            $kodegudang                 = $CI->session->userdata('outlet_kd');
            $data[$i]['saldo_barang']   = $kodebarang;
            $data[$i]['saldo_gudang']   = $kodegudang;
            $data[$i]['saldo_elektrik'] = $rowbarang->group_elektrik;
            if($data[$i]['saldo_elektrik']=='1')
            {
                $saldo_awal                 = " SELECT IFNULL(SUM(saldo_qty),0) AS qty
                                            FROM im_msaldo_barang 
                                            WHERE saldo_barang = '$kodebarang'
                                            AND saldo_tgl = '$tglsekarang'
                                            AND saldo_shift = '$shiftsekarang'";
                $qtysaldoawal               = ($this->db->query($saldo_awal)->num_rows()>0) ? $this->db->query($saldo_awal)->row()->qty : 0;
                
                $penjualan                  = " SELECT IFNULL(SUM(qty*harga_pokok),0) AS qty
                                            FROM elektrik_ac_tjual
                                            WHERE date(tgl) = '$tglsekarang'
                                            AND shift = '$shiftsekarang'
                                            ";
                $qpenjualan                 = $this->db->query($penjualan);
                $qtypenjualan               = ($qpenjualan->num_rows()>0) ? ($qpenjualan->row()->qty) : 0;
                
                $mutasimasuk                = " SELECT IFNULL(SUM(qty),0) AS qty
                                            FROM im_tpindah_barang_dtl dtl
                                            INNER JOIN im_tpindah_barang hdr
                                            ON dtl.no_faktur = hdr.no_faktur
                                            WHERE kd_gudang_tujuan = '$kodegudang'
                                            AND kd_barang = '$kodebarang'
                                            /*AND date(tgl) = '$tglsekarang'*/";
                $qtymutasimasuk             = ($this->db->query($mutasimasuk)->num_rows()>0) ? $this->db->query($mutasimasuk)->row()->qty : 0;
                
                $penyesuaian                = " SELECT IFNULL(SUM(qty),0) AS qty
                                            FROM im_tsaldo_barang_dtl dtl
                                            INNER JOIN  im_tsaldo_barang hdr
                                            ON dtl.no_faktur = hdr.no_faktur
                                            WHERE kd_barang = '$kodebarang'
                                            AND date(tgl) = '$tglsekarang'
                                            AND shift = '$shiftsekarang'";
                $qtypenyesuaian             = ($this->db->query($penyesuaian)->num_rows()>0) ? $this->db->query($penyesuaian)->row()->qty : 0;
                
                $qtysaldo                   = (int)$qtysaldoawal + ((int)$qtymutasimasuk+(int)$qtypenyesuaian) - ((int)$qtypenjualan);
                
                $data[$i]['saldo_awal']     = $qtysaldoawal;
                $data[$i]['saldo_qty']      = $qtysaldo;
                $data[$i]['mutasi_masuk']   = $qtymutasimasuk;
                $data[$i]['mutasi_keluar']  = 0;
                $data[$i]['penjualan']      = $qtypenjualan;
                $data[$i]['penyesuaian']    = $qtypenyesuaian;
                $data[$i]['saldo_shift']    = $shiftsekarang;
                $data[$i]['saldo_tgl']      = $tglsekarang;
            }
            else
            {
                $saldo_awal                 = " SELECT IFNULL(SUM(saldo_qty),0) AS qty
                                            FROM im_msaldo_barang 
                                            WHERE saldo_barang = '$kodebarang'
                                            AND saldo_tgl = '$tglsekarang'
                                            AND saldo_shift = '$shiftsekarang'";
                $qtysaldoawal               = ($this->db->query($saldo_awal)->num_rows()>0) ? $this->db->query($saldo_awal)->row()->qty : 0;
                $penjualan                  = " SELECT IFNULL(SUM(qty),0) AS qty
                                            FROM ac_tjual_dtl dtl
                                            INNER JOIN ac_tjual hdr
                                            ON dtl.no_faktur = hdr.no_faktur
                                            WHERE kd_barang = '$kodebarang'
                                            AND date(tgl) = '$tglsekarang'
                                            AND shift = '$shiftsekarang'";
                $qtypenjualan               = ($this->db->query($penjualan)->num_rows()>0) ? $this->db->query($penjualan)->row()->qty : 0;
                $mutasimasuk                = " SELECT IFNULL(SUM(qty),0) AS qty
                                            FROM im_tpindah_barang_dtl dtl
                                            INNER JOIN im_tpindah_barang hdr
                                            ON dtl.no_faktur = hdr.no_faktur
                                            WHERE kd_gudang_tujuan = '$kodegudang'
                                            AND kd_barang = '$kodebarang'
                                            /*AND date(tgl) = '$tglsekarang'*/";
                $qtymutasimasuk             = ($this->db->query($mutasimasuk)->num_rows()>0) ? $this->db->query($mutasimasuk)->row()->qty : 0;
                $mutasikeluar               = " SELECT IFNULL(SUM(qty),0) AS qty
                                            FROM im_tpindah_barang_dtl dtl
                                            INNER JOIN im_tpindah_barang hdr
                                            ON dtl.no_faktur = hdr.no_faktur
                                            WHERE kd_gudang_asal = '$kodegudang'
                                            AND kd_barang = '$kodebarang'
                                            /*AND date(tgl) = '$tglsekarang'*/";
                $qtymutasikeluar            = ($this->db->query($mutasikeluar)->num_rows()>0) ? $this->db->query($mutasikeluar)->row()->qty : 0;
                $penyesuaian                = " SELECT IFNULL(SUM(qty),0) AS qty
                                            FROM im_tsaldo_barang_dtl dtl
                                            INNER JOIN  im_tsaldo_barang hdr
                                            ON dtl.no_faktur = hdr.no_faktur
                                            WHERE kd_barang = '$kodebarang'
                                            AND date(tgl) = '$tglsekarang'
                                            AND shift = '$shiftsekarang'";                                         
                $qtypenyesuaian             = ($this->db->query($penyesuaian)->num_rows()>0) ? $this->db->query($penyesuaian)->row()->qty : 0;
                
                //---- Adn Pos14------
                $sql_tukar_masuk            = " SELECT IFNULL(SUM(qty),0) AS qty
                                            FROM ac_ttukar_masuk_dtl dtl
                                            INNER JOIN ac_ttukar hdr
                                            ON dtl.no_faktur = hdr.no_faktur
                                            WHERE kd_barang = '$kodebarang'
                                            AND date(tgl) = '$tglsekarang'
                                            AND shift = '$shiftsekarang'";
                $qty_tukar_masuk            = ($this->db->query($sql_tukar_masuk)->num_rows()>0) ?$this->db->query($sql_tukar_masuk)->row()->qty:0;
                
                $sql_tukar_keluar           = " SELECT IFNULL(SUM(qty),0) AS qty
                                            FROM ac_ttukar_dtl dtl
                                            INNER JOIN ac_ttukar hdr
                                            ON dtl.no_faktur = hdr.no_faktur
                                            WHERE kd_barang = '$kodebarang'
                                            AND date(tgl) = '$tglsekarang'
                                            AND shift = '$shiftsekarang'";
                                            
                $qty_tukar_keluar           = ($this->db->query($sql_tukar_keluar)->num_rows()>0) ? $this->db->query($sql_tukar_keluar)->row()->qty:0;
                
                $qtysaldo                   = ((int)$qtysaldoawal+(int)$qtymutasimasuk+(int)$qtypenyesuaian) - ((int)$qtymutasikeluar+(int)$qtypenjualan) + (int) $qty_tukar_masuk - (int)$qty_tukar_keluar;
                
                $data[$i]['saldo_qty']      = $qtysaldo;
                $data[$i]['saldo_awal']     = $qtysaldoawal;
                $data[$i]['mutasi_masuk']   = $qtymutasimasuk;
                $data[$i]['mutasi_keluar']  = $qtymutasikeluar;
                $data[$i]['penjualan']      = $qtypenjualan;
                $data[$i]['penyesuaian']    = $qtypenyesuaian;
                $data[$i]['saldo_shift']    = $shiftsekarang;
                $data[$i]['saldo_tgl']      = $tglsekarang;
                
                $data[$i]['tukar_masuk']    = $qty_tukar_masuk;
                $data[$i]['tukar_keluar']   = $qty_tukar_keluar;      
               //------------------------------------------------------
                
            }
            $i++;
        }
        return $data;
    }
    function saldo_hari($kdbarang='',$tgl='',$shift='')
    {
        $CI     =& get_instance();
        $data                           = array();
        $shiftsekarang                  = $CI->session->userdata('shift');
        $tglsekarang                    = $CI->session->userdata('tanggal');
        if($tgl!='')
        {
            $tglsekarang                = $tgl;
            $shiftsekarang              = $shift;
        }
        
        if($kdbarang!='')
        {
            $CI->db->where('barang_kd',$kdbarang);
        }
        $barang                         = $CI->db->get('im_mbarang');
        $i                              = 0;
        foreach($barang->result() as $rowbarang)
        {
            $kodebarang                 = $rowbarang->barang_kd;
            $kodegudang                 = $CI->session->userdata('outlet_kd');
            $data[$i]['saldo_barang']   = $kodebarang;
            $data[$i]['saldo_gudang']   = $kodegudang;
            $saldo_awal                 = " SELECT IFNULL(SUM(saldo_qty),0) AS qty
                                        FROM im_msaldo_barang 
                                        WHERE saldo_barang = '$kodebarang'
                                        AND saldo_tgl = '$tglsekarang'
                                        AND saldo_shift = '$shiftsekarang'";
            $qtysaldoawal               = ($this->db->query($saldo_awal)->num_rows()>0) ? $this->db->query($saldo_awal)->row()->qty : 0;
            $penjualan                  = " SELECT IFNULL(SUM(qty),0) AS qty
                                        FROM history_ac_tjual_dtl dtl
                                        INNER JOIN history_ac_tjual hdr
                                        ON dtl.no_faktur = hdr.no_faktur
                                        WHERE kd_barang = '$kodebarang'
                                        AND date(tgl) = '$tglsekarang'
                                        AND shift = '$shiftsekarang'";
            $qtypenjualan               = ($this->db->query($penjualan)->num_rows()>0) ? $this->db->query($penjualan)->row()->qty : 0;
            $mutasimasuk                = " SELECT IFNULL(SUM(qty),0) AS qty
                                        FROM history_im_tpindah_barang_dtl dtl
                                        INNER JOIN history_im_tpindah_barang hdr
                                        ON dtl.no_faktur = hdr.no_faktur
                                        WHERE kd_gudang_tujuan = '$kodegudang'
                                        AND kd_barang = '$kodebarang'
                                        /*AND date(tgl) = '$tglsekarang'*/";
            $qtymutasimasuk             = ($this->db->query($mutasimasuk)->num_rows()>0) ? $this->db->query($mutasimasuk)->row()->qty : 0;
            $mutasikeluar               = " SELECT IFNULL(SUM(qty),0) AS qty
                                        FROM history_im_tpindah_barang_dtl dtl
                                        INNER JOIN history_im_tpindah_barang hdr
                                        ON dtl.no_faktur = hdr.no_faktur
                                        WHERE kd_gudang_asal = '$kodegudang'
                                        AND kd_barang = '$kodebarang'
                                        /*AND date(tgl) = '$tglsekarang'*/";
            $qtymutasikeluar            = ($this->db->query($mutasikeluar)->num_rows()>0) ? $this->db->query($mutasikeluar)->row()->qty : 0;
            $penyesuaian                = " SELECT IFNULL(SUM(qty),0) AS qty
                                        FROM history_im_tsaldo_barang_dtl dtl
                                        INNER JOIN  history_im_tsaldo_barang hdr
                                        ON dtl.no_faktur = hdr.no_faktur
                                        WHERE kd_barang = '$kodebarang'
                                        AND date(tgl) = '$tglsekarang'
                                        AND shift = '$shiftsekarang'";
            $qtypenyesuaian             = ($this->db->query($penyesuaian)->num_rows()>0) ? $this->db->query($penyesuaian)->row()->qty : 0;
            $qtysaldo                   = ((int)$qtysaldoawal+(int)$qtymutasimasuk+(int)$qtypenyesuaian) - ((int)$qtymutasikeluar+(int)$qtypenjualan);
            $data[$i]['saldo_qty']      = $qtysaldo;
            $data[$i]['saldo_awal']     = $qtysaldoawal;
            $data[$i]['mutasi_masuk']   = $qtymutasimasuk;
            $data[$i]['mutasi_keluar']  = $qtymutasikeluar;
            $data[$i]['penjualan']      = $qtypenjualan;
            $data[$i]['penyesuaian']    = $qtypenyesuaian;
            $data[$i]['saldo_shift']    = $shiftsekarang;
            $data[$i]['saldo_tgl']      = $tglsekarang;
            $i++;
        }
        return $data;
    }
    function saldo_awal_baru()
    {
        $this->db->trans_begin();
        $CI                             =& get_instance();
        $data                           = $this->saldo_hari_ini();
        for($i=0;$i<count($data);$i++)
        {
            if($data[$i]['saldo_qty']!=0)
            {
                $dataSaldoAwal['saldo_barang']  = $data[$i]['saldo_barang'];
                $dataSaldoAwal['saldo_gudang']  = $data[$i]['saldo_gudang'];
                $dataSaldoAwal['saldo_qty']     = $data[$i]['saldo_qty'];
                if($data[$i]['saldo_shift']=='1')
                {
                    $dataSaldoAwal['saldo_tgl'] = $data[$i]['saldo_tgl'];
                    $dataSaldoAwal['saldo_shift'] = '2'; 
                }
                else
                {
                    $etgl                       = explode('-',$data[$i]['saldo_tgl']);
                    $dataSaldoAwal['saldo_tgl'] = date('Y-m-d',mktime(0,0,0,$etgl[1],$etgl[2]+1,$etgl[0],0));
                    $dataSaldoAwal['saldo_shift'] = '1';
                }
                
//                if(!$this->db->insert('im_msaldo_barang',$dataSaldoAwal))
//                {
//                    
//                }
                $this->simpan($dataSaldoAwal);
                //echo $CI->db->last_query();
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
    function get_saldo_elektrik()
    {
        $CI                 =& get_instance();
        $CI->load->model('barang_elektrik_model');
        $elektrik           = $CI->barang_elektrik_model->get();
        //echo $this->db->last_query();
        /*
            $this->db->join('im_mgroup_barang','im_mgroup_barang.group_kd=im_mbarang.barang_group','left outer');
            $this->db->where('group_elektrik','1');
            $elektrik           = $this->db->get('im_mbarang')->row_array();
        */
        //if (count($elektrik)>0)
        //{
         //   $saldo              = $this->saldo_elektrik_hari_ini($elektrik['barang_kd']);
       // }
        //print_r($saldo);
       // return $saldo[0];
    }
    function saldo_elektrik_hari_ini($kdbarang='')
    {
        $CI     =& get_instance();
        $data                           = array();
        $shiftsekarang                  = $CI->session->userdata('shift');
        $tglsekarang                    = $CI->session->userdata('tanggal');
        $etglsekarang                   = explode('-',$tglsekarang);
        if($shiftsekarang=='1')
        {
            $shiftsebelumnya            = '2';
            $tglsebelumnya              = date('Y-m-d',mktime(0,0,0,$etglsekarang[1],$etglsekarang[2]-1,$etglsekarang[0],0));
        }
        else
        {
            $shiftsebelumnya            = '1';
            $tglsebelumnya              = $tglsekarang;
        }
        
        if($kdbarang!='')
        {
            $CI->db->where('barang_kd',$kdbarang);
        }
        $barang                         = $CI->db->get('im_mbarang');
        $i                              = 0;
        foreach($barang->result() as $rowbarang)
        {
            $kodebarang                 = $rowbarang->barang_kd;
            $kodegudang                 = $CI->session->userdata('outlet_kd');
            $data[$i]['saldo_barang']   = $kodebarang;
            $data[$i]['saldo_gudang']   = $kodegudang;
            $saldo_awal                 = " SELECT IFNULL(SUM(saldo_qty),0) AS qty
                                        FROM im_msaldo_barang 
                                        WHERE saldo_barang = '$kodebarang'
                                        AND saldo_tgl = '$tglsekarang'
                                        AND saldo_shift = '$shiftsekarang'";
            $qtysaldoawal               = ($this->db->query($saldo_awal)->num_rows()>0) ? $this->db->query($saldo_awal)->row()->qty : 0;
            $penjualan                  = " SELECT IFNULL(SUM(qty*harga_pokok),0) as qty
                                        FROM elektrik_ac_tjual
                                        WHERE date(tgl) = '$tglsekarang'
                                        AND shift = '$shiftsekarang'";
            $qpenjualan                 = $this->db->query($penjualan);
            $qtypenjualan               = ($qpenjualan->num_rows()>0) ? ($qpenjualan->row()->qty) : 0;
            $mutasimasuk                = " SELECT IFNULL(SUM(qty),0) AS qty
                                        FROM im_tpindah_barang_dtl dtl
                                        INNER JOIN im_tpindah_barang hdr
                                        ON dtl.no_faktur = hdr.no_faktur
                                        WHERE kd_gudang_tujuan = '$kodegudang'
                                        AND kd_barang = '$kodebarang'
                                        /*AND date(tgl) = '$tglsekarang'*/";
            $qtymutasimasuk             = ($this->db->query($mutasimasuk)->num_rows()>0) ? $this->db->query($mutasimasuk)->row()->qty : 0;
            $penyesuaian                = " SELECT IFNULL(SUM(qty),0) AS qty
                                            FROM im_tsaldo_barang_dtl dtl
                                            INNER JOIN  im_tsaldo_barang hdr
                                            ON dtl.no_faktur = hdr.no_faktur
                                            WHERE kd_barang = '$kodebarang'
                                            AND date(tgl) = '$tglsekarang'
                                            AND shift = '$shiftsekarang'";
            $qtypenyesuaian             = ($this->db->query($penyesuaian)->num_rows()>0) ? $this->db->query($penyesuaian)->row()->qty : 0;
            $qtysaldo                   = (int)$qtysaldoawal + ((int)$qtymutasimasuk+(int)$qtypenyesuaian) - ((int)$qtypenjualan);
            $data[$i]['saldo_awal']     = $qtysaldoawal;
            $data[$i]['saldo_qty']      = $qtysaldo;
            $data[$i]['mutasi_masuk']   = $qtymutasimasuk;
            $data[$i]['penyesuaian']    = $qtypenyesuaian;
            $data[$i]['penjualan']      = $qtypenjualan;
            $data[$i]['saldo_shift']    = $shiftsekarang;
            $data[$i]['saldo_tgl']      = $tglsekarang;
            $i++;
        }
        return $data;
    }
}

?>