<?php

class Kasir_model extends CI_Model
{
    function get_kdakhir($tgl)
    {
        $sql        = 'SELECT max(right(no_faktur,3)) as no_faktur FROM ac_tjual';
        $sql        .= ' WHERE substring(no_faktur,4,6) = '."'$tgl'";
        if($this->db->query($sql)->num_rows()==0)
        {
            return '001';
        }
        else
        {
            $no_faktur      = ((int)$this->db->query($sql)->row()->no_faktur + 1);
            if(strlen($no_faktur)==1)
            {
                $no_faktur  = '00'.$no_faktur;
            }
            elseif(strlen($no_faktur)==2)
            {
                $no_faktur  = '0'.$no_faktur;
            }
            else
            {
                $no_faktur  = $no_faktur;
            }
            return $no_faktur;
        }
    }
    function get_kdakhirhistory($tgl)
    {
        $sql        = 'SELECT max(right(no_faktur,3)) as no_faktur FROM history_ac_tjual';
        $sql        .= ' WHERE substring(no_faktur,4,6) = '."'$tgl'";
        if($this->db->query($sql)->num_rows()==0)
        {
            return '001';
        }
        else
        {
            $no_faktur      = ((int)$this->db->query($sql)->row()->no_faktur + 1);
            if(strlen($no_faktur)==1)
            {
                $no_faktur  = '00'.$no_faktur;
            }
            elseif(strlen($no_faktur)==2)
            {
                $no_faktur  = '0'.$no_faktur;
            }
            else
            {
                $no_faktur  = $no_faktur;
            }
            return $no_faktur;
        }
    }
    function simpan($data)
    {
        return $this->db->insert('ac_tjual',$data);
    }
    function simpan_dtl($data)
    {
        return $this->db->insert('ac_tjual_dtl',$data);   
    }
    function simpan_dtl_imei($data)
    {
        return $this->db->insert('ac_tjual_dtl_imei',$data);
    }
    function get($tgl,$tgl1,$shift)
    {
        $this->db->select('sum(total) as total');
        $this->db->where('tgl >= '."'".$tgl."'",'');
        $this->db->where('tgl <= '."'".$tgl1."'",'');
        $this->db->where('shift',$shift);
        return $this->db->get('ac_tjual');
    }
    function get_max_history()
    {
        $sql            = ' SELECT max(no_faktur) as no_faktur from history_ac_tjual ';
        return $this->db->query($sql);
    }
    function get_max()
    {
        $sql            = ' SELECT max(no_faktur) as no_faktur from ac_tjual ';
        return $this->db->query($sql);
    }
    function ambil($kdfaktur,$tgl='',$tgl1='',$shift='')
    {
        if($kdfaktur!='') $this->db->where('no_faktur',$kdfaktur);
        if($tgl!='') $this->db->where('date(tgl) = '."'".$tgl."'",'',false);
        //if($tgl1!='') $this->db->where('tgl < '."'".$tgl1."'",'');
        if($shift!='') $this->db->where('shift',$shift);
        return $this->db->get('ac_tjual');
    }
    function jmh_faktur_diskon()
    {
        return $this->db->get('ac_tjual');
    }
    function ambil_dtl($kdfaktur,$kdbarang='')
    {
        if($kdbarang!=''){$this->db->where('kd_barang',$kdbarang);}
        if($kdfaktur!=''){$this->db->where('no_faktur',$kdfaktur);}
        return $this->db->get('ac_tjual_dtl');
    }
    function ambil_dtl_imei($kdfaktur,$kdbarang,$urutan)
    {
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('no_faktur',$kdfaktur);
        $this->db->where('urutan',$urutan);
        return $this->db->get('ac_tjual_dtl_imei');
    }
    function get_jmh($tgl,$shift)
    {
        $this->db->select('sum(jmh) as total');
        $this->db->where('date(tgl)',"'$tgl'",false);
        $this->db->where('shift',$shift);
        $nilai =  $this->db->get('ac_tjual')->row()->total;
        
        $this->db->select('sum(uang_tunai) as total');
        $this->db->where('date(tgl)',"'$tgl'",false);
        $this->db->where('shift',$shift);
        $nilai = $nilai +  $this->db->get('ac_ttukar')->row()->total;
        
        return $nilai;
    
    }
    function get_diskon($tgl,$shift)
    {
        $this->db->select('sum(diskon_p) as total');
        $this->db->where('date(tgl)',"'$tgl'",false);
        $this->db->where('shift',$shift);
        return $this->db->get('ac_tjual');
    }
    function kirim_get($tgl,$shift)
    {
        $array                          = array();
        
        $this->db->where('sys_col','kd_gudang');
        $kdgudang                       = $this->db->get('sys_var')->row_array();
        //return $kdgudang;die();
        
        $this->db->where('date(tgl)',$tgl);
        $this->db->where('shift', $shift);
        $urutan1                = $this->db->get('history_ac_tjual');
        //return $this->db->last_query();die();
        $seq                    = 0;
        foreach($urutan1->result() as $rowurutan1)
        {
            //$array[$seq]['no_faktur']   = $rowurutan1->no_faktur.$this->session->userdata('outlet_kd');
            $array[$seq]['no_faktur']   = $rowurutan1->no_faktur.$kdgudang['sys_val'];
            $array[$seq]['tgl']         = $rowurutan1->tgl;
            $array[$seq]['kd_pelanggan']= $rowurutan1->kd_pelanggan;
            $array[$seq]['nik']         = $rowurutan1->nik;
            $array[$seq]['ket']         = $rowurutan1->ket;
            $array[$seq]['kd_term']     = $rowurutan1->kd_term;
            $array[$seq]['nomor_dk']    = $rowurutan1->nomor_dk;
            $array[$seq]['jmh_dk']      = $rowurutan1->jmh_dk;
            $array[$seq]['diskon_p']    = $rowurutan1->diskon_p;
            $array[$seq]['diskon_nominal']  = $rowurutan1->diskon_nominal;
            $array[$seq]['pajak']       = $rowurutan1->pajak;
            $array[$seq]['jmh']         = $rowurutan1->jmh;
            $array[$seq]['biaya_kirim'] = $rowurutan1->biaya_kirim;
            $array[$seq]['total']       = $rowurutan1->total;
            $array[$seq]['kembali']     = $rowurutan1->kembali;
            $array[$seq]['lunas']       = $rowurutan1->lunas;
            $array[$seq]['kd_outlet']   = $rowurutan1->kd_outlet;
            $array[$seq]['shift']       = $rowurutan1->shift;
            $array[$seq]['jmh_debet']   = $rowurutan1->jmh_debet;
            $array[$seq]['jmh_kredit']  = $rowurutan1->jmh_kredit;
            $array[$seq]['jmh_tunai']   = $rowurutan1->jmh_tunai;
            $array[$seq]['jmh_biaya_kartu'] = $rowurutan1->jmh_biaya_kartu;

            $this->db->where('no_faktur',$rowurutan1->no_faktur);
            $this->db->order_by('urutan');
            $urutan2                    = $this->db->get('history_ac_tjual_dtl');
            
            if($urutan2->num_rows()>0)
            {
                $seq2                   = 0;
                foreach($urutan2->result() as $rowurutan2)
                {
                    $array[$seq]['detail'][$seq2]['no_faktur']  = $rowurutan2->no_faktur.$this->session->userdata('outlet_kd');
                    $array[$seq]['detail'][$seq2]['kd_barang']  = $rowurutan2->kd_barang;
                    $array[$seq]['detail'][$seq2]['urutan']     = $rowurutan2->urutan;
                    $array[$seq]['detail'][$seq2]['qty']        = $rowurutan2->qty;
                    $array[$seq]['detail'][$seq2]['satuan']     = $rowurutan2->satuan;
                    $array[$seq]['detail'][$seq2]['harga']      = $rowurutan2->harga;
                    $array[$seq]['detail'][$seq2]['diskon_p']   = $rowurutan2->diskon_p;
                    $array[$seq]['detail'][$seq2]['pajak_p']    = $rowurutan2->pajak_p;
                    $array[$seq]['detail'][$seq2]['jmh']        = $rowurutan2->jmh;
                    
                    $this->db->where('no_faktur',$rowurutan2->no_faktur);
                    $this->db->where('kd_barang',$rowurutan2->kd_barang);
                    $this->db->where('urutan', $rowurutan2->urutan);
                    $urutan3                                    = $this->db->get('history_ac_tjual_dtl_imei');
                    if($urutan3->num_rows()>0)
                    {
						$seq3 = 0;
						foreach($urutan3->result() as $rowurutan3)
						{
                                                        $array[$seq]['detail'][$seq2]['imei'][$seq3]['kd_barang'] = $rowurutan2->kd_barang;
                                                        $array[$seq]['detail'][$seq2]['imei'][$seq3]['no_faktur'] = $rowurutan2->no_faktur;
                                                        $array[$seq]['detail'][$seq2]['imei'][$seq3]['urutan'] = $rowurutan2->urutan;
							$array[$seq]['detail'][$seq2]['imei'][$seq3]['imei']   = $rowurutan3->imei;
							$seq3++;
						}
                    }
                    else
                    {
                        $array[$seq]['detail'][$seq2]['imei']   = array();
                    }
                    $seq2++;
                }
            }
            $seq++;
        }
        return $array;        
    }

    function kirim_get_berkala($tgldari,$tglsampai)
    {
        $array                          = array();
        
        $this->db->where('sys_col','kd_gudang');
        $kdgudang                       = $this->db->get('sys_var')->row_array();
        //return $kdgudang;die();
        
        $this->db->where("date(tgl) between '".$tgl."' ",'',false);
        $urutan1                = $this->db->get('history_ac_tjual');
        return $this->db->last_query();die();
        
        $seq                    = 0;
        foreach($urutan1->result() as $rowurutan1)
        {
            //$array[$seq]['no_faktur']   = $rowurutan1->no_faktur.$this->session->userdata('outlet_kd');
            $array[$seq]['no_faktur']   = $rowurutan1->no_faktur.$kdgudang['sys_val'];
            $array[$seq]['tgl']         = $rowurutan1->tgl;
            $array[$seq]['kd_pelanggan']= $rowurutan1->kd_pelanggan;
            $array[$seq]['nik']         = $rowurutan1->nik;
            $array[$seq]['ket']         = $rowurutan1->ket;
            $array[$seq]['kd_term']     = $rowurutan1->kd_term;
            $array[$seq]['nomor_dk']    = $rowurutan1->nomor_dk;
            $array[$seq]['jmh_dk']      = $rowurutan1->jmh_dk;
            $array[$seq]['diskon_p']    = $rowurutan1->diskon_p;
            $array[$seq]['diskon_nominal']  = $rowurutan1->diskon_nominal;
            $array[$seq]['pajak']       = $rowurutan1->pajak;
            $array[$seq]['jmh']         = $rowurutan1->jmh;
            $array[$seq]['biaya_kirim'] = $rowurutan1->biaya_kirim;
            $array[$seq]['total']       = $rowurutan1->total;
            $array[$seq]['kembali']     = $rowurutan1->kembali;
            $array[$seq]['lunas']       = $rowurutan1->lunas;
            $array[$seq]['kd_outlet']   = $rowurutan1->kd_outlet;
            $array[$seq]['shift']       = $rowurutan1->shift;
            $array[$seq]['jmh_debet']   = $rowurutan1->jmh_debet;
            $array[$seq]['jmh_kredit']  = $rowurutan1->jmh_kredit;
            $array[$seq]['jmh_tunai']   = $rowurutan1->jmh_tunai;
            $array[$seq]['jmh_biaya_kartu'] = $rowurutan1->jmh_biaya_kartu;

            $this->db->where('no_faktur',$rowurutan1->no_faktur);
            $this->db->order_by('urutan');
            $urutan2                    = $this->db->get('history_ac_tjual_dtl');
            
            if($urutan2->num_rows()>0)
            {
                $seq2                   = 0;
                foreach($urutan2->result() as $rowurutan2)
                {
                    $array[$seq]['detail'][$seq2]['no_faktur']  = $rowurutan2->no_faktur.$this->session->userdata('outlet_kd');
                    $array[$seq]['detail'][$seq2]['kd_barang']  = $rowurutan2->kd_barang;
                    $array[$seq]['detail'][$seq2]['urutan']     = $rowurutan2->urutan;
                    $array[$seq]['detail'][$seq2]['qty']        = $rowurutan2->qty;
                    $array[$seq]['detail'][$seq2]['satuan']     = $rowurutan2->satuan;
                    $array[$seq]['detail'][$seq2]['harga']      = $rowurutan2->harga;
                    $array[$seq]['detail'][$seq2]['diskon_p']   = $rowurutan2->diskon_p;
                    $array[$seq]['detail'][$seq2]['pajak_p']    = $rowurutan2->pajak_p;
                    $array[$seq]['detail'][$seq2]['jmh']        = $rowurutan2->jmh;
                    
                    $this->db->where('no_faktur',$rowurutan2->no_faktur);
                    $this->db->where('kd_barang',$rowurutan2->kd_barang);
                    $this->db->where('urutan', $rowurutan2->urutan);
                    $urutan3                                    = $this->db->get('history_ac_tjual_dtl_imei');
                    if($urutan3->num_rows()>0)
                    {
						$seq3 = 0;
						foreach($urutan3->result() as $rowurutan3)
						{
							$array[$seq]['detail'][$seq2]['imei'][$seq3]   = $rowurutan3->imei;
							$seq3++;
						}
                    }
                    else
                    {
                        $array[$seq]['detail'][$seq2]['imei']   = array();
                    }
                    $seq2++;
                }
            }
            $seq++;
        }
        return $array;        
    }
    function getByNoStruk($kd)
    {
        $sql = " select hdr.no_faktur, tgl,ket, dtl.*
                    ,mbr.barang_nm
                from ac_tjual hdr
                inner join ac_tjual_dtl dtl
                    on hdr.no_faktur = dtl.no_faktur
                inner join im_mbarang mbr
                    on dtl.kd_barang = mbr.barang_kd
                where hdr.no_faktur = '$kd'
                ";
        $query = $this->db->query($sql);
        return $query->result();
    }
}

?>