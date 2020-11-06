<?php

class Kasir_elektrik_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function simpan($data)
    {
        return $this->db->insert('elektrik_ac_tjual',$data);
    }
    function edit($kd,$data)
    {
        $this->db->where($kd,'',false);
        return $this->db->update('elektrik_ac_tjual',$data);
    }
    function hapus($kd)
    {
        $this->db->where($kd,'',false);
        return $this->db->delete('elektrik_ac_tjual');
    }
    function get($tgldari,$tglsampai,$shift,$cari='',$offset='',$limit='',$kd='')
    {
        if($kd!='')
        {
            $this->db->where('no_faktur',$kd);
        }
		if($tgldari!='' && $tglsampai!='' && $tgldari!='0' && $tglsampai!='')
		{        
			$this->db->where("date(tgl) between '$tgldari' and '$tglsampai' ",'',false);
		}
//        if($shift!='')
//        {
//            $shifts     = explode('#',$shift);
//            echo count($shifts);die();
//            $this->db->where('shift',$shift);
//        }
        if($cari!='')
        {
            $this->db->or_like('barang_nm',$cari);
            $this->db->or_like('kd_barang',$cari);
        }
        if($limit!='')
        {
            $this->db->limit($limit,$offset);
        }
        $this->db->join('im_mbarang','kd_barang=barang_kd','inner');
        return $this->db->get('elektrik_ac_tjual');
    }
    function kirim_get($tgl,$shift)
    {
        $this->db->where('date(tgl)',$tgl);
        $this->db->where('shift',$shift);
        $this->db->select('concat(no_faktur,kd_gudang) as no_faktur,kd_barang,no_urut,tgl,shift,qty,harga,harga_pokok,no_hp,status,uid,doe,uid_edit,doe_edit',false);
        $get = $this->db->get('elektrik_ac_tjual');
        return $get->result_array();        
    }
}

?>