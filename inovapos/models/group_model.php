<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function group_ambil($kd='')
    {
        if($kd!='')
        {
            $this->db->where('group_kd',$kd);
        }
        return $this->db->get('mgroup');
    }
    function group_simpan($data)
    {
        return $this->db->insert('mgroup',$data);
    }
    function group_edit($kd,$data)
    {
        $this->db->where('group_kd',$kd);
        return $this->db->update('mgroup',$data);
    }
    function group_delete($kd)
    {
        $this->db->where('group_kd',$kd);
        return $this->db->delete('mgroup');
    }
}
?>