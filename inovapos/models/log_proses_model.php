<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_proses_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function get($tipe='',$log_col='',$order='')
    {
        if($tipe!=''){$this->db->where('tipe',$tipe);}
        if($log_col!=''){ $this->db->where('log_col',$log_col);}
        if($order!=''){$this->db->order_by($order,'desc');}
        return $this->db->get('log_proses');
    }
    function gettutupshift()
    {
        $this->db->where('log_col','SHIFT');
        //$this->db->select('log_col,log_val,tipe,shift');
        //$this->db->select_max('shift','shift');
        $this->db->select_max('date(tgl)','tgl');
        return $this->db->get('log_proses');
    }
    function getmaxshifttanggal($tgl)
    {
        $this->db->where('log_col','SHIFT');
        $this->db->where("date(tgl) = '$tgl'",'',false);
        $this->db->select('log_col,log_val,shift,tipe,date(tgl)',false);
        $this->db->order_by('shift','desc');
        return $this->db->get('log_proses');
    }
    function get_item_update_harga($tgl,$shift)
    {
        $this->db->where('date(tgl)',"'$tgl'",false);
        $this->db->where('shift',$shift);
        $this->db->where('tipe','UPDATE HARGA MANUAL');
        return $this->db->get('log_proses');
    }
    function cekTutupShift($tgl,$shift)
    {
        $hasil = 0;

        $this->db->where('tipe', TUTUP_SHIFT);
        $this->db->where('tgl', $tgl);
        $this->db->where('shift', $shift);
        $this->db->where('log_val','CLOSE');

        $this->db->select('kd_log');
        $query = $this->db->get('log_proses');

        if($query->num_rows()>0)
        {
            $hasil = 1;
        }
        $hasil;
        return $hasil;
    }
    function cekTutupStok($tgl,$shift)
    {
        $hasil = 0;

        $this->db->where('tipe', TUTUP_STOK);
        $this->db->where('date(tgl)',"'$tgl'",false);
        if($shift!=''){$this->db->where('shift', $shift);}

        $this->db->select('kd_log');
        $query = $this->db->get('log_proses');

        if($query->num_rows()>0)
        {
            $hasil = 1;
        }
        $hasil;
        return $hasil;
    }
    function close($data)
    {
        return $this->db->insert('log_proses', $data);
    }
    function update($where,$data)
    {
        $this->db->where($where);
        return $this->db->update('log_proses', $data);
    }

    function getLaporanBelumDikirim()
    {
//        $sql = "SELECT DATE(tutup.tgl) as tgl, tutup.shift
//                FROM log_proses tutup
//                WHERE tutup.tipe = '".TUTUP_SHIFT."'";
//        $sql .= " AND CONCAT(tgl,'-',shift)
//                NOT IN
//                (
//                SELECT  CONCAT(date(lap.tgl),'-',lap.shift)
//                FROM log_proses lap
//                WHERE lap.tipe = '". LAP_JUAL ."'
//                )";
        $sql    = "SELECT date(tgl) as tgl,shift
            FROM log_proses
            WHERE tipe = '".TUTUP_SHIFT."'
            AND log_val = 'CLOSE'
            AND CONCAT(DATE(tgl),'#',shift) NOT IN 
            (
            	SELECT CONCAT(DATE(tgl),'#',shift) AS tgl FROM log_proses
            	WHERE tipe = '".LAP_JUAL."'
            	AND log_val = 'SUKSES'
            ) ";
        return $this->db->query($sql);
    }
    function getlaporan($tgl,$shift)
    {
        $this->db->from('log_proses');
        $this->db->where('date(tgl)',$tgl);
        $this->db->where('shift',$shift);
        $this->db->where('tipe','LAPORAN PENJUALAN');
        $this->db->select('date(tgl) as tgl,shift',false);
        return $this->db->get();
    }
    function simpanLogJual($data)
    {
        return $this->db->insert('log_proses',$data);
    }
    
    function getReturBelumDikirim()
    {
        $sql    = "SELECT date(tgl) as tgl,shift
            FROM log_proses
            WHERE tipe = '".TUTUP_SHIFT."'
            AND log_val = 'CLOSE'
            AND CONCAT(DATE(tgl),'#',shift) NOT IN 
            (
            	SELECT CONCAT(DATE(tgl),'#',shift) AS tgl FROM log_proses
            	WHERE tipe = '".LAP_RETUR."'
            	AND log_val = 'SUKSES'
            ) ";
            echo $sql;die();
        return $this->db->query($sql);
    }
}
?>