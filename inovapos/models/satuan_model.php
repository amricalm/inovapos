<?php

class Satuan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function get($kd,$limit,$offset)
    {
        $this->db->from('im_msatuan');
        
        return $this->db->get();
    }
    function simpan($data)
    {
        return $this->db->insert('im_msatuan',$data);
    }
    function update($key,$data)
    {
        $this->db->where('kd_satuan',$key);
        return $this->db->update('im_msatuan',$data);
    }
    function hapus($key)
    {
        $this->db->where('kd_satuan',$key);
        return $this->db->delete('im_msatuan');
    }
}

?>