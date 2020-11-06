<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Otoritas_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function otoritas_ambil($kdgroup='',$kdobjek='',$limit='',$offset='')
    {
        if($kdgroup!='')
        {
            $this->db->where('otoritas_group',$kdgroup);
        }
        if($kdobjek!='')
        {
            $this->db->where('otoritas_objek',$kdobjek);
        }
        return $this->db->get('motoritas',$limit,$offset);
    }
    function otoritas_simpan($data)
    {
        return $this->db->insert('motoritas',$data);
    }
    function otoritas_delete($kdgroup='',$kdobjek='')
    {
        if($kdgroup!='')
        {
            $this->db->where('otoritas_group',$kdobjek);
        }
        if($kdobjek!='')
        {
            $this->db->where('otoritas_objek',$kdobjek);
        }
        return $this->db->delete('motoritas');
    }
}
?>