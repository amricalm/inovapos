<?php

class Karyawan_model extends CI_Model
{
    private $_tabel  = 'mkaryawan';
    private $_key    = 'nik';
    private $_nm     = 'nm_lengkap';
    
    function __construct()
    {
        parent::__construct();
    }
    function get($kd='',$limit,$offset,$cari='')
    {
        $this->db->from('im_mkaryawan');
        if($kd!='')
        {
            $this->db->where('karyawan_kd',$kd);
        }
        if($cari!='')
        {
            $this->db->or_like('karyawan_nm_lengkap',$cari);
            $this->db->or_like('karyawan_alamat',$cari);
            $this->db->or_like('karyawan_kecamatan',$cari);
            $this->db->or_like('karyawan_kelurahan',$cari);
            $this->db->or_like('karyawan_kota',$cari);
            $this->db->or_like('karyawan_hp',$cari);
            $this->db->or_like('karyawan_email',$cari);
        }
        return $this->db->get();
    }
    
    function is_ada($key)
    {
        $hasil = false;
        
        $this->db->from($this->_tabel);
        $this->db->where($this->_key,$key);

        $qry = $this->db->get();
        if($qry->num_rows()>0)
        {
           $hasil = true;
        }
        return $hasil;
    }
    
    function is_valid($key, $pwd)
    {
        $hasil = false;
        
        $this->db->from($this->_tabel);
        $this->db->where($this->_key,$key);
        $this->db->where('pwd_absen',$pwd);

        $qry = $this->db->get();
        if($qry->num_rows()>0)
        {
           $hasil = true;
        }
        return $hasil;
    }
    
    
    
    function simpan($data)
    {
        return $this->db->insert($this->_tabel,$data);
    }
    function update($key,$data)
    {
        $this->db->where('karyawan_kd',$key);
        return $this->db->update('im_mkaryawan',$data);
    }
    function hapus($key)
    {
        if ($key!='')
        {
            $this->db->where($this->_key,$key);
            return $this->db->delete($this->_tabel);
        }
        else 
        {
            return $this->db->empty_table($this->_tabel); 
        }
        
    }
}

?>