<?php

class History_kasir_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function penjualan($tgl=0,$shift=0,$grup='',$cari='',$limit='',$offset='')
    {
        $etgl               = explode('-',$tgl);
        
        $tglsesudahnya      = 
        $sql = "SELECT dtl.no_faktur,brg.barang_kd,brg.barang_nm ,group_elektrik
                FROM ac_tjual_dtl dtl
                INNER JOIN ac_tjual hdr
                ON dtl.no_faktur = hdr.no_faktur
                INNER JOIN im_mbarang brg
                ON dtl.kd_barang = brg.barang_kd
                LEFT OUTER JOIN im_mgroup_barang
                ON group_kd=barang_group
                WHERE date(tgl) = '$tgl'
                AND shift = $shift ";
        $sql    .= ($grup!='') ? " AND barang_group = '$grup'" : '';
        $sql    .= ($cari!='') ? " AND (brg.barang_nm LIKE '%$cari%' " : '';
        $sql    .= ($cari!='') ? " OR brg.barang_kd LIKE '%$cari%' " : '';
        $sql    .= ($cari!='') ? " OR dtl.no_faktur LIKE '%$cari%') " : '';
        $sql    .= " GROUP BY barang_kd ";                
        $sql    .= " ORDER BY barang_kd "; 
        if($limit!='') 
        {
            $sql .= " limit $limit offset $offset ";
        }

        return $this->db->query($sql);
    }
    function penjualan_dtl($barang=0,$tgl=0,$shift=0)
    {
        $sql = "SELECT brg.barang_kd,brg.barang_nm,dtl.no_faktur,dtl.qty, dtl.harga, (dtl.qty*dtl.harga) jmh 
                FROM ac_tjual_dtl dtl
                INNER JOIN ac_tjual hdr
                ON dtl.no_faktur = hdr.no_faktur
                INNER JOIN im_mbarang brg
                ON dtl.kd_barang = brg.barang_kd
                WHERE date(tgl) = '$tgl'
                AND shift = $shift 
                AND brg.barang_kd = '$barang' ";
        $sql    .= " ORDER BY barang_kd "; 

        return $this->db->query($sql);
    }
    function penjualan_per_faktur($tgl=0,$shift=0,$grup='',$cari='',$limit='',$offset='')
    {
        $etgl               = explode('-',$tgl);
        
        $tglsesudahnya      = 
        $sql = "SELECT no_faktur,total
                FROM ac_tjual
                WHERE date(tgl) = '$tgl'
                AND shift = $shift ";       
        $sql    .= " ORDER BY no_faktur DESC "; 
        if($limit!='') 
        {
            $sql .= " limit $limit offset $offset ";
        }

        return $this->db->query($sql);
    }
    function penjualan_dtl_per_faktur($nofaktur=0,$tgl=0,$shift=0)
    {
        $sql = "SELECT brg.barang_kd,brg.barang_nm,dtl.no_faktur,dtl.qty, dtl.harga, (dtl.qty*dtl.harga) jmh 
                FROM ac_tjual_dtl dtl 
                INNER JOIN ac_tjual hdr ON dtl.no_faktur = hdr.no_faktur 
                INNER JOIN im_mbarang brg ON dtl.kd_barang = brg.barang_kd 
                WHERE date(tgl) = '$tgl'
                AND shift = $shift 
                AND dtl.no_faktur = '$nofaktur' ";
        $sql    .= " ORDER BY urutan "; 

        return $this->db->query($sql);
    }
    function rekap_penjualan($tgldari='',$tglsampai='',$cari='',$grup='',$limit='',$offset='')
    {
/**
  *         $sql = "SELECT brg.barang_kd,brg.barang_nm,dtl.no_faktur,dtl.qty, dtl.harga, (dtl.qty*dtl.harga) jmh 
  *                 FROM ac_tjual_dtl dtl 
  *                 INNER JOIN ac_tjual hdr ON dtl.no_faktur = hdr.no_faktur 
  *                 INNER JOIN im_mbarang brg ON dtl.kd_barang = brg.barang_kd 
  *                 WHERE date(tgl) = '$tgl'
  *                 AND shift = $shift 
  *                 AND dtl.no_faktur = '$nofaktur' ";
  *         $sql    .= " ORDER BY urutan ";
  */ 
        $stgldari                           = '';
        $stglsampai                         = '';
        if($tgldari!='' && $tglsampai!='')
        {
            if($tgldari==$tglsampai)
            {
                $etglsekarang               = explode('-',$tgldari);
                $tglsampai                  = date('Y-m-d',mktime(0,0,0,$etglsekarang[1],$etglsekarang[2]+1,$etglsekarang[0]));
                $stgldari                   = " AND DATE(tgl) >= '$tgldari' ";
                $stglsampai                 = " AND DATE(tgl) < '$tglsampai' ";
            }
            else
            {
                $stgldari                   = " AND DATE(tgl) >= '$tgldari' ";
                $stglsampai                 = " AND DATE(tgl) <= '$tglsampai' ";
            }
        }
        else
        {
            $stgldari                       = " AND DATE(tgl) >= '' ";
            $stglsampai                     = " AND DATE(tgl) <= '' ";
        }
        $sql = " SELECT barang_kd,barang_nm,IFNULL(SUM(qty),0) jumlah,harga,tgl
                FROM im_mbarang
                LEFT OUTER JOIN history_ac_tjual_dtl dtl
                ON barang_kd = dtl.kd_barang
                LEFT OUTER JOIN history_ac_tjual hdr
                ON hdr.no_faktur = dtl.no_faktur
                $stgldari
                $stglsampai
                WHERE shift != '' ";
        $sql    .= ($grup!='') ? " AND barang_group = '$grup'" : '';
        $sql    .= ($cari!='') ? " AND (barang_nm LIKE '%$cari%' " : '';
        $sql    .= ($cari!='') ? " OR barang_kd LIKE '%$cari%') " : '';
        $sql    .= " GROUP BY barang_kd,barang_nm,harga,tgl ORDER BY tgl desc ";
        if($limit!='') 
        {
            $sql .= " limit $limit offset $offset ";
        }
        return $this->db->query($sql);
    }
    function get()
    {
        
    }
    function get_dtl()
    {
        
    }
    function get_dtl_imei()
    {
        
    }
    function simpan_kasir($data)
    {
        return $this->db->insert('history_ac_tjual',$data);
    }
    function simpan_kasir_dtl($data)
    {
        return $this->db->insert('history_ac_tjual_dtl',$data);
    }
    function simpan_kasir_dtl_imei($data)
    {
        return $this->db->insert('history_ac_tjual_dtl_imei',$data);
    }
    function get_diskon($tgl='')
    {
        if($tgl!='')
        {
            $this->db->where('date(tgl)',$tgl);
        }
        $this->db->where('hdr.diskon_p>0','',false);
        $this->db->where('dtl.diskon_p>0','',false);
        $this->db->select('count(hdr.no_faktur) as jmhfaktur, SUM(hdr.diskon_p) AS nilaidiskon');
        $this->db->join('history_ac_tjual_dtl dtl','hdr.no_faktur=dtl.no_faktur','inner');
        return $this->db->get('history_ac_tjual hdr');
    }
}


?>