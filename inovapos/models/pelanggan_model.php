<?php

class Pelanggan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function get($kd='',$limit,$offset,$cari='')
    {
        $this->db->from('im_mpelanggan');
        if($kd!='')
        {
            $this->db->where('pelanggan_member',$kd);
        }
        if($limit!='')
        {
            $this->db->limit($limit,$offset);
        }
        if($cari!='')
        {
            $this->db->or_like('pelanggan_nm_lengkap',$cari);
            $this->db->or_like('pelanggan_alamat',$cari);
            $this->db->or_like('pelanggan_kecamatan',$cari);
            $this->db->or_like('pelanggan_kelurahan',$cari);
            $this->db->or_like('pelanggan_kota',$cari);
        }
        return $this->db->get();
    }
    function simpan($data)
    {
        return $this->db->insert('im_mpelanggan',$data);
    }
    function update($key,$data)
    {
        $this->db->where('pelanggan_kd',$key);
        return $this->db->update('im_mpelanggan',$data);
    }
    function hapus($key)
    {
        if($key!='')
        {
            $this->db->where('pelanggan_kd',$key);
        }
        else
        {
            $this->db->where("pelanggan_kd<>''",'',false);
        }
        return $this->db->delete('im_mpelanggan');
    }
}

?>