<?php

class Barang_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function get($kd,$limit,$offset,$cari,$grup='')
    {
        //$this->db->select('kd_barang,nm_barang,barcode,im_mbarang.kd_group,nm_group,im_mbarang.kd_satuan,nm_satuan,harga_jual,hpp');
        $this->db->from('im_mbarang');
        $this->db->join('im_mgroup_barang','barang_group=group_kd','inner');
        $this->db->join('im_msatuan','barang_satuan=satuan_kd','left outer');
        //$this->db->join('im_msaldo_barang','saldo_barang=barang_kd','left outer');
        if($limit!='') 
        {
            $this->db->limit($limit,$offset);
        }
        if($cari!='') 
        {
            $this->db->or_like('barang_nm',$cari);
            $this->db->or_like('barang_kd',$cari);
        }
        if($kd!='')
        {
            $this->db->where('barang_kd',$kd);
        }
        if($grup!='')
        {
            $this->db->where('barang_group',$grup);
        }
        //$this->db->where("barang_group!=''",'',false);
        $this->db->order_by('barang_kd');
        return $this->db->get();
    }
    function get_elektrik($kd,$limit,$offset,$cari)
    {
        //$this->db->select('kd_barang,nm_barang,barcode,im_mbarang.kd_group,nm_group,im_mbarang.kd_satuan,nm_satuan,harga_jual,hpp');
        $this->db->from('im_mbarang');
        $this->db->join('im_mgroup_barang','barang_group=group_kd','left outer');
        $this->db->join('im_msatuan','barang_satuan=satuan_kd','left outer');
        //$this->db->join('im_msaldo_barang','saldo_barang=barang_kd','left outer');
        if($limit!='') 
        {
            $this->db->limit($limit,$offset);
        }
        if($cari!='') 
        {
            $this->db->where("(barang_nm LIKE '%$cari%' OR `barang_kd` LIKE '%$cari%' )","",false);
            //$this->db->or_like('barang_nm',$cari);
            //$this->db->or_like('barang_kd',$cari);
        }
        if($kd!='')
        {
            $this->db->where('barang_kd',$kd);
        }
        $this->db->where('im_mbarang.barang_group','');
        $this->db->order_by('barang_kd');
        return $this->db->get();
    }
    function get_print($kd,$limit,$offset,$cari,$grup='')
    {
        //$this->db->select('kd_barang,nm_barang,barcode,im_mbarang.kd_group,nm_group,im_mbarang.kd_satuan,nm_satuan,harga_jual,hpp');
        $this->db->from('im_mbarang');
        $this->db->join('im_mgroup_barang','barang_group=group_kd','left outer');
        $this->db->join('im_msatuan','barang_satuan=satuan_kd','left outer');
        //$this->db->join('im_msaldo_barang','saldo_barang=barang_kd','left outer');
        if($limit!='') 
        {
            $this->db->limit($limit,$offset);
        }
        if($cari!='') 
        {
            $this->db->or_like('barang_nm',$cari);
            $this->db->or_like('barang_kd',$cari);
        }
        if($kd!='')
        {
            $this->db->where('barang_kd',$kd);
        }
        if($grup!='')
        {
            $this->db->where('barang_group',$grup);
        }
        $this->db->order_by('barang_kd');
        return $this->db->get();
    }
    function simpan_id($data)
    {
        $this->db->insert('im_mbarang',$data);
        return $this->db->insert_id();
    }
    function simpan($data)
    {
        return $this->db->insert('im_mbarang',$data);
    }
    function update($key,$data)
    {
        $this->db->where('barang_kd',$key);
        return $this->db->update('im_mbarang',$data);
    }
    function hapus($key)
    {
//        $this->db->where('barang_kd',$key);
//        return $this->db->delete('im_mbarang');
        $this->db->trans_begin();
        $sql        = " DELETE FROM im_mbarang WHERE barang_kd = '$key'";
        $this->db->query($sql);
        if($this->db->trans_status()===false)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }

    function cek_grup_hp($kd_barang)
    {
        $hasil = 0;

        $this->db->select('group_hp');
        $this->db->from('im_mbarang');
        $this->db->join('im_mgroup_barang', 'barang_group=group_kd','inner');
        $this->db->where('barang_kd', $kd_barang);
        $qry = $this->db->get();
        //echo $this->db->last_query(); die;
        if($qry->num_rows()>0)
        {
            if ($qry->row()->group_hp==-1 || $qry->row()->group_hp==1)
            {
                $hasil = 1;
            }
        }

        return $hasil;

    }

}

?>