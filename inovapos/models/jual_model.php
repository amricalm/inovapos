<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jual_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function get()
    {
        return $this->db->get('ac_tjual',50,2);
    }
    function simpan($data)
    {
        
        //return $this->db->insert('pos_tjual',$data);
    }
    function edit($kd,$data)
    {

    }
    function hapus($kd)
    {

    }
    function jmh_bon_real($tgl,$shift)
    {
        $hasil = 0;
        $this->db->select('count(no_faktur) jmh ');
        $this->db->where("date(tgl) = '".$tgl."'",'',false);
        $this->db->where('shift', $shift);
        $qry = $this->db->get('ac_tjual');
        if($qry->num_rows()>0)
        {
            $hasil = $qry->row()->jmh;
        }

        return $hasil;

    }
    function jmh_bon($tgl,$shift)
    {
        $hasil = 0;
        $this->db->select('count(no_faktur) jmh ');
        $this->db->where("date(tgl) = '".$tgl."'",'',false);
        $this->db->where('shift', $shift);
        $qry = $this->db->get('history_ac_tjual');
        if($qry->num_rows()>0)
        {
            $hasil = $qry->row()->jmh;
        }

        return $hasil;

    }
    function jmh_penjualan($tgl,$shift)
    {
        $hasil = 0;
        $this->db->select_sum('jmh');
        $this->db->where("date(tgl) = '".$tgl."'",'',false);
        $this->db->where('shift', $shift);
        $qry = $this->db->get('history_ac_tjual');
        if($qry->num_rows()>0)
        {
            $hasil = $qry->row()->jmh;
        }
        return $hasil;
    }

    function jmh_tunai($tgl,$shift)
    {
        $hasil = 0;
        $this->db->select_sum('jmh_tunai');
        $this->db->where("date(tgl) = '".$tgl."'",'',false);
        $this->db->where('shift', $shift);
        $qry = $this->db->get('history_ac_tjual');
        if($qry->num_rows()>0)
        {
            $hasil = $qry->row()->jmh_tunai;
        }
        return $hasil;
    }
    function jmh_debet($tgl,$shift)
    {
        $hasil = 0;
        $this->db->select_sum('jmh_debet');
        $this->db->where("date(tgl) = '".$tgl."'",'',false);
        $this->db->where('shift', $shift);
        $qry = $this->db->get('history_ac_tjual');
        if($qry->num_rows()>0)
        {
            $hasil = $qry->row()->jmh_debet;
        }
        return $hasil;
    }
    function jmh_kredit($tgl,$shift)
    {
        $hasil = 0;
        $this->db->select_sum('jmh_kredit');
        $this->db->where("date(tgl) = '".$tgl."'",'',false);
        $this->db->where('shift', $shift);
        $qry = $this->db->get('history_ac_tjual');
        if($qry->num_rows()>0)
        {
            $hasil = $qry->row()->jmh_kredit;
        }
        return $hasil;
    }
    function osi_terakhir($tgl,$shift)
    {
        $hasil          = 0;
        $this->db->select_max('no_faktur');
        $this->db->where('date(tgl)',"'$tgl'",false);
        $this->db->where('shift',$shift);
        //$qry            = $this->db->get('ac_tjual');
        $qry            = $this->db->get('history_ac_tjual');
        if($qry->num_rows()>0)
        {
            $hasil      = $qry->row()->no_faktur;
        }
        return $hasil;
    }
}
?>