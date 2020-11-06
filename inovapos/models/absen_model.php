<?php

class Absen_model extends CI_Model
{
    private $_tabel  = 'gj_absen';
    private $_key    = 'kd';
    private $_nm     = 'nik';
    
    function __construct()
    {
        parent::__construct();
    }
    
    function get($tgl, $shift)
    {
        $kd_gudang = $this->app_model->system('kd_gudang');
        
        $sql = "select *, " . $kd_gudang . " as kd_gudang
                from gj_absen
                where tgl = '" . $tgl . "'
                    and shift = " . $shift ;
                        
        return $this->db->query($sql);
    }
    
    function simpan($data)
    {
        return $this->db->insert($this->_tabel,$data);
    }
    
    function update_jam_keluar($tgl, $shift, $nik, $jam_keluar)
    {
        $sql = "update gj_absen
                set jam_keluar = '" . $jam_keluar . "'
                where tgl = '" . $tgl . "'
                    and shift = " . $shift ."
                    and nik = '" . $nik ."'";
        
        return $this->db->query($sql);
    }
    
    function is_ada_jam_masuk($tgl, $shift, $nik)
    {
        $hasil = false;
        
        $sql = "select nik
                from gj_absen
                where tgl = '" . $tgl . "'
                    and shift = " . $shift ."
                    and nik = '" . $nik ."'";
        
        $qry =  $this->db->query($sql);
        if($qry->num_rows()>0)
        {
            $hasil = true;
        }
        return $hasil;
    }
    
    function get_absen($tgl, $shift, $nik)
    {       
        $sql = "select *
                from gj_absen
                where tgl = '" . $tgl . "'
                    and shift = " . $shift ."
                    and nik = '" . $nik ."'";
        
        return $this->db->query($sql);
    }
    
}

?>