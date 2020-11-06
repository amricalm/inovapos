<?php

class Laporan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

//    function penjualan($shift=0, $limit='',$offset)
//    {
//        $sql = "SELECT dtl.no_faktur,brg.barang_nm, dtl.qty, dtl.harga, (dtl.qty*dtl.harga) jmh
//                FROM ac_tjual hdr
//                INNER JOIN ac_tjual_dtl dtl
//                    ON hdr.no_faktur = dtl.no_faktur
//                INNER JOIN im_mbarang brg
//                    ON dtl.kd_barang = brg.barang_kd
//                WHERE tgl = '" . date('Y-m-d') . "' 
//                    AND shift=$shift";
//
//        if($limit!='') 
//        {
//            $sql .= " limit $limit offset $offset ";
//        }
//
//        return $this->db->query($sql);
//    }

    function penjualan($tgl=0,$shift=0,$grup='',$cari='',$limit='',$offset='')
    {
        $etgl               = explode('-',$tgl);
        
        $tglsesudahnya      = 
        $sql = "SELECT dtl.no_faktur,brg.barang_kd,brg.barang_nm 
                FROM ac_tjual_dtl dtl
                INNER JOIN ac_tjual hdr
                ON dtl.no_faktur = hdr.no_faktur
                INNER JOIN im_mbarang brg
                ON dtl.kd_barang = brg.barang_kd
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
}

?>