<?php

class History_mutasi_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function get($kd)
    {
        $this->db->where('no_faktur',$kd);
        return $this->db->get('history_im_tpindah_barang');
    }
    function get_dtl($kd,$kdb)
    {
        $this->db->where('no_faktur',$kd);
        $this->db->where('kd_barang',$kdb);
        return $this->db->get('history_im_tpindah_barang_dtl');
    }
    function get_dtl_imei()
    {
        $this->db->where('no_faktur',$kd);
        $this->db->where('kd_barang',$kdb);
        return $this->db->get('history_im_tpindah_barang_dtl');
    }
    function simpan_pindah($data)
    {
        return $this->db->insert('history_im_tpindah_barang',$data);
    }
    function simpan_pindah_dtl($data)
    {
        return $this->db->insert('history_im_tpindah_barang_dtl',$data);
    }
    function simpan_pindah_dtl_imei($data)
    {
        return $this->db->insert('history_im_tpindah_barang_dtl_imei',$data);
    }
}


?>