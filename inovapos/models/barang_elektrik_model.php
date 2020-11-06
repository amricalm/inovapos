<?php

class Barang_elektrik_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }    
    function get($kd='',$cetak='')
    {
		if($kd!='')
		{
			$this->db->where('barang_kd',$kd);
		}
        $this->db->join('im_mgroup_barang','im_mgroup_barang.group_kd=im_mbarang.barang_group','left outer');
		if($cetak=='')
		{
			$this->db->where('group_elektrik','1');
		}
        return $this->db->get('im_mbarang')->row_array();
    }
}

?>