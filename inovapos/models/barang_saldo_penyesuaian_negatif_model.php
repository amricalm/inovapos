<?php

class Barang_saldo_penyesuaian_negatif_model extends CI_Model
{
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
            $this->insert_hdr($datahdr);
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
        $datahdr['status']          = 'negatif';
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
            $this->insert_hdr($datahdr);
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
        $datahdr['status']          = 'negatif';
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
    function simpan_hdr($data)
    {
        return $this->db->insert('im_tsaldo_barang_negatif',$data);
    }
    function update_hdr($kd,$data)
    {
        $this->db->where('no_faktur',$kd);
        return $this->db->update('im_tsaldo_barang_negatif',$data);
    }
    function simpan_dtl($data)
    {
        return $this->db->insert('im_tsaldo_barang_dtl_negatif',$data);
    }
    function update_dtl($kd,$data)
    {
        $this->db->where('no_faktur',$kd['no_faktur']);
        $this->db->where('kd_barang',$kd['kd_barang']);
        return $this->db->update('im_tsaldo_barang_dtl_negatif',$data);
    }
    function simpan_dtl_imei($data)
    {
        return $this->db->insert('im_tsaldo_barang_dtl_imei_negatif',$data);
    }
    function update_dtl_imei($kd,$data)
    {
        $this->db->where('no_faktur',$kd['no_faktur']);
        $this->db->where('kd_barang',$kd['kd_barang']);
        return $this->db->update('im_tsaldo_barang_dtl_imei_negatif',$data);
    }
}