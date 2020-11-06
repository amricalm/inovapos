<?php

class Barang_saldo_penyesuaian_model extends CI_Model
{
    function get($no_faktur='',$kd_barang='',$grup_barang='',$teks='',$limit=0,$offset=0)
    {
        /*
        SELECT barang_kd,barang_nm, qty
        FROM im_mbarang brg
        LEFT OUTER JOIN im_tsaldo_barang_dtl dtl
        ON brg.barang_kd = dtl.kd_barang
        LEFT OUTER JOIN im_tsaldo_barang hdr
        ON dtl.no_faktur = hdr.no_faktur
        AND hdr.no_faktur = 'SO12042001'
        WHERE barang_group = '20'
        */
        
        $this->db->from('im_mbarang');
        $this->db->join('im_mgroup_barang','barang_group=group_kd','INNER');
        $this->db->join('im_tsaldo_barang_dtl','im_mbarang.barang_kd=im_tsaldo_barang_dtl.kd_barang','left outer');
        $this->db->join('im_tsaldo_barang',"im_tsaldo_barang_dtl.no_faktur=im_tsaldo_barang.no_faktur and im_tsaldo_barang.no_faktur = '$no_faktur'",'left outer');
        if($grup_barang!='' && $grup_barang!='0') {$this->db->where('barang_group',$grup_barang);}
        if($kd_barang!=''){$this->db->where('barang_kd',$kd_barang);}
        if($teks!=''){
            $this->db->or_like('barang_nm',$teks);
            $this->db->or_like('barang_kd',$teks);
            $this->db->or_like('group_nm',$teks);
        }
        if($limit!=0){$this->db->limit($limit,$offset);}
        $this->db->order_by('barang_kd');
        return $this->db->get();
    }
    function cek_imei_saldo($imei)
    {
        $hasil = 0;
        $this->db->select('saldo_imei');
        $this->db->where('saldo_imei', $imei);
        $qry = $this->db->get('im_msaldo_barang_imei');
        if($qry->num_rows()>0)
        {
            $hasil++;
        }
        else
        {
            $this->db->select('imei');
            $this->db->where('imei', $imei);
            $qry = $this->db->get('im_tsaldo_barang_dtl_imei');
            if($qry->num_rows() > 0)
            {
                $hasil++;
            }
        }
        return $hasil;
    }
    function cek_faktur($faktur,$status)
    {
        $this->db->where('no_faktur',$faktur);
        $this->db->where('status',$status);
        return $this->db->get('im_tsaldo_barang');
    }
    function cek_dtl($faktur,$barang)
    {
        $this->db->where('no_faktur',$faktur);
        $this->db->where('kd_barang',$barang);
        return $this->db->get('im_tsaldo_barang_dtl');
    }
    function cek_imei($faktur,$barang)
    {
        $this->db->where('no_faktur',$faktur);
        $this->db->where('kd_barang',$barang);
        return $this->db->get('im_tsaldo_barang_dtl_imei');
    }
    function simpan($data)
    {
        $etgl                       = explode('-',$data['tgl']);
        $error                      = '';
        $datahdr['no_faktur']       = 'SO'.$etgl[0].$etgl[1].$etgl[2].$data['shift'];
        $datahdr['tgl']             = $data['tgl'];
        $datahdr['kd_gudang']       = $data['kd_gudang'];
        $datahdr['status']          = 'positif';
        $datahdr['shift']           = $data['shift'];
        $datahdr['uid_edit']        = $data['user_kd'];
        $datahdr['doe_edit']        = $data['tgl'].' '.date('H:i:s');
        if($this->cek_faktur($datahdr['no_faktur'],$datahdr['status'])->num_rows() > 0)
        {
            $this->update_hdr($datahdr['no_faktur'],$datahdr);
            if($this->cek_dtl($datahdr['no_faktur'],$data['kd_barang'])->num_rows() > 0)
            {
                $datadtl['no_faktur']   = $datahdr['no_faktur'];
                $datadtl['kd_barang']   = $data['kd_barang'];
                $datadtl['qty']         = $data['qty'];
                if(!$this->update_dtl($datadtl,$datadtl))
                {
                    $error              .= 'error';
                }
            }
            else
            {
                $datadtl['no_faktur']   = $datahdr['no_faktur'];
                $datadtl['kd_barang']   = $data['kd_barang'];
                $datadtl['qty']         = $data['qty'];
                if(!$this->simpan_dtl($datadtl))
                {
                    $error              .= 'error';
                }
            }
        }
        else
        {
            $datahdr['uid']         = $data['user_kd'];
            $datahdr['doe']         = $data['tgl'].' '.date('H:i:s');
            $this->simpan_hdr($datahdr);
            $datadtl['no_faktur']   = $datahdr['no_faktur'];
            $datadtl['kd_barang']   = $data['kd_barang'];
            $datadtl['qty']         = $data['qty'];
            if(!$this->simpan_dtl($datadtl))
            {
                $error              .= 'error';
            }
        }
        
        if($error=='')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function simpan_imei($data,$imei)
    {
        $etgl                       = explode('-',$data['tgl']);
        $error                      = '';
        $datahdr['no_faktur']       = 'SO'.$etgl[0].$etgl[1].$etgl[2].$data['shift'];
        $datahdr['tgl']             = $data['tgl'];
        $datahdr['kd_gudang']       = $data['kd_gudang'];
        $datahdr['shift']           = $data['shift'];
        $datahdr['status']          = 'positif';
        $datahdr['uid_edit']        = $data['user_kd'];
        $datahdr['doe_edit']        = $data['tgl'].' '.date('H:i:s');
        if($this->cek_faktur($datahdr['no_faktur'],$datahdr['status'])->num_rows() > 0)
        {
            $this->update_hdr($datahdr['no_faktur'],$datahdr);
            if($this->cek_dtl($datahdr['no_faktur'],$data['kd_barang'])->num_rows() > 0)
            {
                $datadtl['no_faktur']   = $datahdr['no_faktur'];
                $datadtl['kd_barang']   = $data['kd_barang'];
                $qty                    = $this->cek_dtl($datahdr['no_faktur'],$data['kd_barang'])->row()->qty;
                $datadtl['qty']         = (int)$qty + 1;
                $this->update_dtl($datadtl,$datadtl);
                $dataimei['no_faktur']  = $datahdr['no_faktur'];
                $dataimei['kd_barang']  = $data['kd_barang'];
                $dataimei['imei']       = $data['imei'];
                if(!$this->simpan_dtl_imei($dataimei))
                {
                    $error              .= 'error';
                }
            }
            else
            {
                $datadtl['no_faktur']   = $datahdr['no_faktur'];
                $datadtl['kd_barang']   = $data['kd_barang'];
                $datadtl['qty']         = 1;
                $this->simpan_dtl($datadtl);
                $dataimei['no_faktur']  = $datahdr['no_faktur'];
                $dataimei['kd_barang']  = $data['kd_barang'];
                $dataimei['imei']       = $data['imei'];
                if(!$this->simpan_dtl_imei($dataimei))
                {
                    $error              .= 'error';
                }
            }
        }
        else
        {
            $datahdr['uid']         = $data['user_kd'];
            $datahdr['doe']         = $data['tgl'].' '.date('H:i:s');
            $this->simpan_hdr($datahdr);
            $datadtl['no_faktur']   = $datahdr['no_faktur'];
            $datadtl['kd_barang']   = $data['kd_barang'];
            $datadtl['qty']         = 1;
            $this->simpan_dtl($datadtl);
            $dataimei['no_faktur']  = $datahdr['no_faktur'];
            $dataimei['kd_barang']  = $data['kd_barang'];
            $dataimei['imei']       = $data['imei'];
            if(!$this->simpan_dtl_imei($dataimei))
            {
                $error              .= 'error';
            }
        }
        if($error=='')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function hapus_imei($data)
    {
        $etgl                       = explode('-',$data['tgl']);
        $error                      = '';
        $datahdr['no_faktur']       = 'SO'.$etgl[0].$etgl[1].$etgl[2].$data['shift'];
        $datahdr['tgl']             = $data['tgl'];
        $datahdr['kd_gudang']       = $data['kd_gudang'];
        $datahdr['shift']           = $data['shift'];
        $datahdr['status']          = 'positif';
        $datahdr['uid_edit']        = $data['user_kd'];
        $datahdr['doe_edit']        = $data['tgl'].' '.date('H:i:s');
        if($this->cek_faktur($datahdr['no_faktur'],$datahdr['status'])->num_rows() > 0)
        {
            $this->update_hdr($datahdr['no_faktur'],$datahdr);
            if($this->cek_dtl($datahdr['no_faktur'],$data['kd_barang'])->num_rows() > 0)
            {
                $datadtl['no_faktur']   = $datahdr['no_faktur'];
                $datadtl['kd_barang']   = $data['kd_barang'];
                $qty                    = $this->cek_dtl($datahdr['no_faktur'],$data['kd_barang'])->row()->qty;
                $datadtl['qty']         = (int)$qty - 1;
                $this->update_dtl($datadtl,$datadtl);
                $hehe['no_faktur']      = $datahdr['no_faktur'];
                $hehe['kd_barang']      = $data['kd_barang'];
                $dataimei['imei']       = $data['imei'];
                //$qty                    = $this->cek_imei($datahdr['no_faktur'],$data['kd_barang'])->num;
                if(!$this->hapus_dtl_imei($hehe,$dataimei))
                {
                    $error              .= 'error';
                }
            }
            else
            {
                $datadtl['no_faktur']   = $datahdr['no_faktur'];
                $datadtl['kd_barang']   = $data['kd_barang'];
                $datadtl['qty']         = -1;
                $this->simpan_dtl($datadtl);
                $hehe['no_faktur']      = $datahdr['no_faktur'];
                $hehe['kd_barang']      = $data['kd_barang'];
                $dataimei['imei']       = $data['imei'];
                if(!$this->hapus_dtl_imei($hehe,$dataimei))
                {
                    $error              .= 'error';
                }
            }
        }
        else
        {
            $datahdr['uid']         = $data['user_kd'];
            $datahdr['doe']         = $data['tgl'].' '.date('H:i:s');
            $this->simpan_hdr($datahdr);
            $datadtl['no_faktur']   = $datahdr['no_faktur'];
            $datadtl['kd_barang']   = $data['kd_barang'];
            $datadtl['qty']         = -1;
            $this->simpan_dtl($datadtl);
            $hehe['no_faktur']      = $datahdr['no_faktur'];
            $hehe['kd_barang']      = $data['kd_barang'];
            $dataimei['imei']       = $data['imei'];
            if(!$this->simpan_dtl_imei($hehe,$dataimei))
            {
                $error              .= 'error';
            }
        }
        if($error=='')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    function simpan_hdr($data)
    {
        return $this->db->insert('im_tsaldo_barang',$data);
    }
    function update_hdr($kd,$data)
    {
        $this->db->where('no_faktur',$kd);
        return $this->db->update('im_tsaldo_barang',$data);
    }
    function simpan_dtl($data)
    {
        return $this->db->insert('im_tsaldo_barang_dtl',$data);
    }
    function update_dtl($kd,$data)
    {
        $this->db->where('no_faktur',$kd['no_faktur']);
        $this->db->where('kd_barang',$kd['kd_barang']);
        return $this->db->update('im_tsaldo_barang_dtl',$data);
    }
    function simpan_dtl_imei($data)
    {
        return $this->db->insert('im_tsaldo_barang_dtl_imei',$data);
    }
    function update_dtl_imei($kd,$data)
    {
        $this->db->where('no_faktur',$kd['no_faktur']);
        $this->db->where('kd_barang',$kd['kd_barang']);
        return $this->db->update('im_tsaldo_barang_dtl_imei',$data);
    }
    function hapus_dtl_imei($kd,$data)
    {
        $this->db->where('no_faktur',$kd['no_faktur']);
        $this->db->where('kd_barang',$kd['kd_barang']);
        return $this->db->delete('im_tsaldo_barang_dtl_imei',$data);
    }
}