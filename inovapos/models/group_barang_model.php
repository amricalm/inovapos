<?php

class Group_barang_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function get($kd='',$limit,$offset,$cari='')
    {
        $this->db->from('im_mgroup_barang');
        if($limit!='')
        {
            $this->db->limit($limit,$offset);
        }
        if($kd!='')
        {
            $this->db->where('group_kd',$kd);
        }
        if($cari!='')
        {
            $this->db->like('group_nm');
        }
        $this->db->order_by('group_nm');
        return $this->db->get();
    }
    function simpan($data)
    {
        return $this->db->insert('im_mgroup_barang',$data);
    }
    function update($key,$data)
    {
        $this->db->where('group_kd',$key);
        return $this->db->update('im_mgroup_barang',$data);
    }
    function hapus($key)
    {
        $this->db->where('group_kd',$key);
        return $this->db->delete('im_mgroup_barang');
    }
}

?>