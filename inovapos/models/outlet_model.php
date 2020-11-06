<?php

class Outlet_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function outlet_ambil($kd='')
    {
        $this->db->from('im_moutlet');
        if($kd!='')
        {
            $this->db->where('outlet_kd',$kd);
        }
        $this->db->order_by('outlet_kd');

        return $this->db->get();
    }
    function simpan($data)
    {
        return $this->db->insert('im_moutlet',$data);
    }
    function update($key,$data)
    {
        $this->db->where('outlet_kd',$key);
        return $this->db->update('im_moutlet',$data);
    }
    function hapus($key='')
    {
        if($key!='')
        {
            $this->db->where('outlet_kd',$key);
        }
        return $this->db->delete('im_moutlet');
    }
}

?>