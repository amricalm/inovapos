<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function user_ambil($kd='',$pwd='')
    {
        if($kd!='')
        {
            $this->db->where('user_kd',$kd);
        }
        if($pwd!='')
        {
            $this->db->where('user_password',$pwd);
        }
        return $this->db->get('muser');
    } 
    function get($kd='',$limit,$offset,$cari='',$where='')
    {
        if($kd!='')
        {
            $this->db->where('user_kd',$kd);
        }
        if($limit!='') 
        {
            $this->db->limit($limit,$offset);
        }
        if($cari!='') 
        {
            $this->db->or_like('user_kd',$cari);
            $this->db->or_like('user_nm',$cari);
        }
        if($where!='')
        {
            $this->db->where($where,'',false);
        }
        $this->db->join('mgroup','muser.user_group=mgroup.group_kd','inner');
        return $this->db->get('muser');
        
    }
    function user_simpan($data)
    {
        return $this->db->insert('muser',$data);
    }
    function user_update($kd,$data)
    {
        $this->db->where('user_kd',$kd);
        return $this->db->update('muser',$data);
    }
    function user_hapus($kd)
    {
        $this->db->where('user_kd',$kd);
        return $this->db->delete('muser');
    }
}
?>