<?php

class History_model extends CI_Model
{
    private $tabel  = 'history_ac_tjual';
    private $key    = 'no_faktur';
    function __construct()
    {
        parent::__construct();
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
    
    //------ inovaPOS 14.0 --------------
    function get($kd)
    {
        $this->db->select($this->key.', tgl');
        $this->db->from($this->tabel);
        $this->db->where($this->key,$kd);
        $query = $this->db->get();
        return $query->result();
    }
    
    function get2($kd)
    {
        $sql = " select hdr.no_faktur, tgl,ket, dtl.*
                    ,mbr.barang_nm
                from history_ac_tjual hdr
                inner join history_ac_tjual_dtl dtl
                    on hdr.no_faktur = dtl.no_faktur
                inner join im_mbarang mbr
                    on dtl.kd_barang = mbr.barang_kd
                where hdr.no_faktur = '$kd'
                ";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    function cekImei($imei)
    {
        $hasil = false;
        $sql = " select ime.imei
                from history_ac_tjual_dtl_imei ime
                where ime.imei = '$imei'
                ";
        $query = $this->db->query($sql);
        if ($query->num_rows()>0)
        {
            $hasil = true;
        }
        
        return $hasil;
    }
    
    function getBarangByImei($noStruk, $imei)
    {
        $sql = " select dtl.kd_barang,brg.barang_nm as nm_barang, ime.imei, dtl.harga
                from history_ac_tjual_dtl dtl
                inner join history_ac_tjual_dtl_imei ime
                    on dtl.no_faktur = ime.no_faktur
                    and dtl.kd_barang = ime.kd_barang
                inner join im_mbarang brg
                    on brg.barang_kd = dtl.kd_barang
                where  ime.imei = '$imei'
                    and ime.no_faktur = '$noStruk'
                ";       
       $query = $this->db->query($sql);
       return $query->result();
    }
    
}


?>