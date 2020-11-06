<?php

class Akun_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function ambil_akun($kd='',$klasifikasi='',$gol='',$kategori='',$bank='',$cari='')
    {
        if($kd!='')
        {
            $this->db->where('kd_perkiraan',$kd);
        }
        if($klasifikasi!='')
        {
            $this->db->where('klasifikasi',$klasifikasi);
        }
        if($gol!='')
        {
            if($cari=='')
            {
                $this->db->where('kode_gol',$gol);
            }
            else
            {
                $this->db->or_where('kode_gol',$gol);
                $this->db->or_like('kd_perkiraan',$cari);
                $this->db->or_like('nama_perkiraan',$cari);
            }
        }
        if($kategori!='')
        {
            $this->db->where('kategori',$kategori);
        }
        if($bank!='')
        {
            $this->db->where('bank',$bank);
        }
        
        return $this->db->get('ac_ms_perk');
    }
}

?>