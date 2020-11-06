<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Objek_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function objek_ambil($kd='',$limit='',$offset='')
    {
        if($kd!='')
        {
            $this->db->where('objek_kd',$kd);
        }
        return $this->db->get('mobjek',$limit,$offset);
    }
    function objek_simpan($data)
    {
        return $this->db->insert('mobjek',$data);
    }
    function objek_edit($kd,$data)
    {
        $this->db->where('objek_kd',$kd);
        return $this->db->update('mobjek',$data);
    }
    function objek_delete($kd)
    {
        $this->db->where('objek_kd',$kd);
        return $this->db->delete('mobjek');
    }
}
?>