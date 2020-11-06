<?php

class Barang_imei_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function get($kdbarang,$imei,$status,$faktur='')
    {
        $hasil          = 0;
        if($kdbarang!='')
        {
            $this->db->where('imei_barang',$kdbarang);
        }
        if($imei!='')
        {
            $this->db->where('imei_no',$imei);
        }
        if($status!='')
        {
            $this->db->where('imei_status',$status);
        }
        if($faktur!='')
        {
            $this->db->where('imei_ref',$faktur);
        }
        $this->db->select('im_mbarang_dtl_imei.*');
        $this->db->join('im_mbarang','imei_barang=barang_kd','inner');
        $this->db->order_by('im_mbarang_dtl_imei.doe,im_mbarang_dtl_imei.doe_edit');
        
        $qry            = $this->db->get('im_mbarang_dtl_imei');
        return $qry;
    }
    function simpan($data)
    {
        return $this->db->insert('im_mbarang_dtl_imei',$data);
    }
    function update($barang,$imei,$data)
    {
        $this->db->where('imei_barang',$barang);
        $this->db->where('imei_no',$imei);
        return $this->db->update('im_mbarang_dtl_imei',$data);
    }
}

?>