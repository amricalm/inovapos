<?php

class Barang_mutasi_model extends CI_Model
{
    function get($kd,$tgl='',$gudangasal='',$gudangtujuan='',$sinkronisasi='',$tglsebelumnya='')
    {
        if($tgl!='')
        {
            $pisahtgl       = explode('-',$tgl);
            $tglsesudahnya  = date('Y-m-d',mktime(0,0,0,$pisahtgl[1],$pisahtgl[2]+1,$pisahtgl[0]));
            if($kd!=''){$this->db->where('no_faktur',$kd);}
            if($tgl!='' && $tglsebelumnya=='')
            {
                $this->db->where("date(tgl) >= '$tgl' ",'',false);
                //$this->db->where("date(tgl) < '$tglsesudahnya' ",'',false);
            }
            else
            {
                $this->db->where("tgl >= '$tglsebelumnya' ",'',false);
                $this->db->where("tgl < '$tgl' ",'',false);
            }
        }
        if($kd!='')
        {
            $this->db->where('no_faktur',$kd);  
        }
        if($gudangasal!='')
        {
            $this->db->where('kd_gudang_asal',$gudangasal);
        }
        if($gudangtujuan!='')
        {
            $this->db->where('kd_gudang_tujuan',$gudangtujuan);
        }
        if($sinkronisasi!='')
        {
            $sql            = " no_faktur NOT IN (";
            $sql            .= " SELECT no_faktur FROM im_tpindah_barang ";
            $sql            .= " WHERE no_faktur!=''";
            if($tgl!='')
            {
                $sql        .= " AND date(tgl) >= '$tgl' AND date(tgl) < '$tglsesudahnya' ";
            }
            $sql            .= " AND sinkronisasi = 'OK' ) ";
            $this->db->where($sql,'',false);
        }
        $this->db->order_by('no_faktur');
        return $this->db->get('im_tpindah_barang');
    }
    function get_dtl($kd,$kdbarang='')
    {
        $this->db->where('no_faktur',$kd);
        if($kdbarang!='')
        {
            $this->db->where('kd_barang',$kdbarang);
        }
        $this->db->join('im_mbarang','im_tpindah_barang_dtl.kd_barang=im_mbarang.barang_kd','inner');
        $this->db->order_by('urutan');
        return $this->db->get('im_tpindah_barang_dtl');
    }
    function get_dtl_aja($kd,$kdbarang='')
    {
        $this->db->where('no_faktur',$kd);
        if($kdbarang!='')
        {
            $this->db->where('kd_barang',$kdbarang);
        }
        //$this->db->join('im_mbarang','im_tpindah_barang_dtl.kd_barang=im_mbarang.barang_kd','inner');
        $this->db->order_by('urutan');
        return $this->db->get('im_tpindah_barang_dtl');
    }
    function get_dtl_imei($kd,$kdbarang)
    {
        if($kd!=''){$this->db->where('no_faktur',$kd);}
        if($kdbarang!=''){$this->db->where('kd_barang',$kdbarang);}
        $this->db->order_by('no_faktur');
        return $this->db->get('im_tpindah_barang_dtl_imei');
    }
    function get_max_faktur_dari_pusat()
    {
        $sql            = ' SELECT max(no_faktur) as no_faktur FROM im_tpindah_barang ';
        $sql            .= ' WHERE length(no_faktur) = 11 ';
        return $this->db->query($sql);
    }
    function get_max_faktur_dari_pusat_history()
    {
        $sql            = ' SELECT max(no_faktur) as no_faktur FROM history_im_tpindah_barang ';
        $sql            .= ' WHERE length(no_faktur) = 11 ';
        return $this->db->query($sql);
    }
    function get_max_faktur($tgl)
    {
        $sql            = 'SELECT MAX(RIGHT(no_faktur,5)) as ttt from im_tpindah_barang';
        $sql            .= " where SUBSTRING(no_faktur,-9,4) = '$tgl' ";
        return $this->db->query($sql);
    }
    function get_max()
    {
        $sql            = ' SELECT max(no_faktur) as no_faktur from im_tpindah_barang ';
        return $this->db->query($sql);
    }
    function get_max_history()
    {
        $sql            = ' SELECT max(no_faktur) as no_faktur from history_im_tpindah_barang ';
        return $this->db->query($sql);
    }
    function get_max_faktur_history($tgl)
    {
        $sql            = 'SELECT MAX(RIGHT(no_faktur,5)) as ttt from history_im_tpindah_barang';
        $sql            .= " where SUBSTRING(no_faktur,-9,4) = '$tgl' ";
        return $this->db->query($sql);
    }
    function simpan($data)
    {
        return $this->db->insert('im_tpindah_barang',$data);
    }
    function simpan_dtl($data)
    {
        return $this->db->insert('im_tpindah_barang_dtl',$data);
    }
    function simpan_dtl_imei($data)
    {
        return $this->db->insert('im_tpindah_barang_dtl_imei',$data);
    }
    function update($kd,$data)
    {
        $this->db->where('no_faktur',$kd);
        return $this->db->update('im_tpindah_barang',$data);
    }
    function kirim_mutasi($tgl)
    {
        $CI                     =& get_instance();
        $array                  = array();
        //$this->db->where('date(tgl)',$tgl);
        //$this->db->where('kd_gudang_tujuan','0001');
        //$urutan1                = $this->db->get('im_tpindah_barang');
        $urutan1                = $this->get('',$tgl,$CI->session->userdata('outlet_kd'),'','sinkronisasi');
        
        $seq                    = 0;
        foreach($urutan1->result() as $rowurutan1)
        {
            $array[$seq]['no_faktur']   = $rowurutan1->no_faktur;
            $array[$seq]['tgl']         = $rowurutan1->tgl;
            $array[$seq]['ket']         = $rowurutan1->ket;
            $array[$seq]['kd_gudang_asal'] = $rowurutan1->kd_gudang_asal;
            $array[$seq]['kd_gudang_tujuan'] = $rowurutan1->kd_gudang_tujuan;
            $array[$seq]['ref']         = $rowurutan1->ref;
            $array[$seq]['st_dokumen']  = $rowurutan1->st_dokumen;
            $array[$seq]['status']      = $rowurutan1->status;
            
            $this->db->where('no_faktur',$rowurutan1->no_faktur);
            $this->db->order_by('urutan');
            $urutan2                    = $this->db->get('im_tpindah_barang_dtl');
            if($urutan2->num_rows()>0)
            {
                $seq2                   = 0;
                foreach($urutan2->result() as $rowurutan2)
                {
                    $array[$seq]['detail'][$seq2]['no_faktur']  = $rowurutan2->no_faktur;
                    $array[$seq]['detail'][$seq2]['kd_barang']  = $rowurutan2->kd_barang;
                    $array[$seq]['detail'][$seq2]['urutan']     = $rowurutan2->urutan;
                    $array[$seq]['detail'][$seq2]['qty']        = $rowurutan2->qty;
                    $array[$seq]['detail'][$seq2]['satuan']     = $rowurutan2->satuan;
                    $array[$seq]['detail'][$seq2]['dtl_imei']       = array();
                    
                    $this->db->where('no_faktur',$rowurutan2->no_faktur);
                    $this->db->where('kd_barang',$rowurutan2->kd_barang);
                    $urutan3                                    = $this->db->get('im_tpindah_barang_dtl_imei');
                    if($urutan3->num_rows()>0)
                    {
                        $seq3   = 0;
                        foreach($urutan3->result() as $row_imei)
                        {
                            $array[$seq]['detail'][$seq2]['dtl_imei'][$seq3]['imei']   = $row_imei->imei;//$urutan3->row()->imei;
                            $seq3++;
                        }
                    }
                    else
                    {
                        //$array[$seq]['detail'][$seq2]['dtl_imei']   = '';
                    }
                    $seq2++;
                }
            }
            $seq++;
        }
        return $array;
    }
    function get_history_notsync($tgl)
    {
        /*SELECT DATE(tgl) AS tgl, COUNT(no_faktur) AS jmh FROM history_im_tpindah_barang
        WHERE tgl <= '2012-11-08'
        AND sinkronisasi <> 'OK'
        AND kd_gudang_asal <> '0001'
        GROUP BY DATE(tgl)
        ORDER BY tgl desc*/
        $ptgl = explode('-',$tgl);
        if($tgl!='')
        {
            $tgl = date("Y-m-d", mktime(0, 0, 0, $ptgl[1],$ptgl[2]+1,$ptgl[0]));
            $this->db->where("tgl = '".$tgl."'",'',false);
        }
        $this->db->where("sinkronisasi is null",'',false);
        $this->db->where("kd_gudang_asal",$this->session->userdata('outlet_kd'));
        $this->db->group_by('date(tgl)');
        $this->db->order_by('tgl desc');
        $this->db->select("date(tgl) as tgl,count(no_faktur) as jmh");
        return $this->db->get('history_im_tpindah_barang');
    }
    function get_history_hdr($tgl='',$nofaktur='')
    {
        //$ptgl = explode('-',$tgl);
        //$tgl = date("Y-m-d", mktime(0, 0, 0, $ptgl[1],$ptgl[2]+1,$ptgl[0]));
        if($tgl!='')
        {
            $this->db->where("date(tgl) = '".$tgl."'",'',false);
        }
        if($nofaktur!='')
        {
            $this->db->where('no_faktur',$nofaktur);
        }
        $this->db->where("sinkronisasi is null",'',false);
        $this->db->where("kd_gudang_asal = '".$this->session->userdata('outlet_kd')."'",'',false);
        //$this->db->group_by('date(tgl)');
        $this->db->order_by('tgl desc');
        $this->db->select("*");
        return $this->db->get('history_im_tpindah_barang');
    }
    //function 
    function kirim_mutasi_history($tgl)
    {        
        $CI                     =& get_instance();
        $array                  = array();
        //$this->db->where('date(tgl)',$tgl);
        //$this->db->where('kd_gudang_tujuan','0001');
        //$urutan1                = $this->db->get('im_tpindah_barang');
        $urutan1                = $this->get_history('',$tgl,$CI->session->userdata('outlet_kd'),'','sinkronisasi');
        $seq                    = 0;
        //die($this->db->last_query());
        foreach($urutan1->result() as $rowurutan1)
        {
            $array[$seq]['no_faktur']   = $rowurutan1->no_faktur;
            $array[$seq]['tgl']         = $rowurutan1->tgl;
            $array[$seq]['ket']         = $rowurutan1->ket;
            $array[$seq]['kd_gudang_asal'] = $rowurutan1->kd_gudang_asal;
            $array[$seq]['kd_gudang_tujuan'] = $rowurutan1->kd_gudang_tujuan;
            $array[$seq]['ref']         = $rowurutan1->ref;
            $array[$seq]['st_dokumen']  = $rowurutan1->st_dokumen;
            $array[$seq]['status']      = $rowurutan1->status;
            
            $this->db->where('no_faktur',$rowurutan1->no_faktur);
            $this->db->order_by('urutan');
            $urutan2                    = $this->db->get('history_im_tpindah_barang_dtl');
            if($urutan2->num_rows()>0)
            {
                $seq2                   = 0;
                foreach($urutan2->result() as $rowurutan2)
                {
                    $array[$seq]['detail'][$seq2]['no_faktur']  = $rowurutan2->no_faktur;
                    $array[$seq]['detail'][$seq2]['kd_barang']  = $rowurutan2->kd_barang;
                    $array[$seq]['detail'][$seq2]['urutan']     = $rowurutan2->urutan;
                    $array[$seq]['detail'][$seq2]['qty']        = $rowurutan2->qty;
                    $array[$seq]['detail'][$seq2]['satuan']     = $rowurutan2->satuan;
                    $array[$seq]['detail'][$seq2]['dtl_imei']       = array();
                    
                    $this->db->where('no_faktur',$rowurutan2->no_faktur);
                    $this->db->where('kd_barang',$rowurutan2->kd_barang);
                    $urutan3                                    = $this->db->get('history_im_tpindah_barang_dtl_imei');
                    if($urutan3->num_rows()>0)
                    {
                        $seq3   = 0;
                        foreach($urutan3->result() as $row_imei)
                        {
                            $array[$seq]['detail'][$seq2]['dtl_imei'][$seq3]['imei']   = $row_imei->imei;//$urutan3->row()->imei;
                            $seq3++;
                        }
                    }
                    else
                    {
                        //$array[$seq]['detail'][$seq2]['dtl_imei']   = '';
                    }
                    $seq2++;
                }
            }
            $seq++;
        }
        return $array;
    }
    function get_history($kd,$tgl='',$gudangasal='',$gudangtujuan='',$sinkronisasi='',$tglsebelumnya='',$bukandaripusat='')
    {
        if($tgl!='')
        {
//            $pisahtgl       = explode('-',$tgl);
//            $tglsesudahnya  = date('Y-m-d',mktime(0,0,0,$pisahtgl[1],$pisahtgl[2]+1,$pisahtgl[0]));
//            if($kd!=''){$this->db->where('no_faktur',$kd);}
//            if($tgl!='' && $tglsebelumnya=='')
//            {
//                $this->db->where("date(tgl) >= '$tgl' ",'',false);
//                //$this->db->where("date(tgl) < '$tglsesudahnya' ",'',false);
//            }
//            else
//            {
//                $this->db->where("tgl >= '$tglsebelumnya' ",'',false);
//                $this->db->where("tgl < '$tgl' ",'',false);
//            }
            $this->db->where('date(tgl)',$tgl);
        }
        if($kd!='')
        {
            $this->db->where('no_faktur',$kd);  
        }
        if($gudangasal!='')
        {
            $this->db->where('kd_gudang_asal',$gudangasal);
        }
        if($gudangtujuan!='')
        {
            $this->db->where('kd_gudang_tujuan',$gudangtujuan);
        }      
        if($bukandaripusat!='')
        {
            $this->db->where("kd_gudang_asal <> '0001'",'',false);
        }
        if($sinkronisasi!='')
        {
            $sql            = " no_faktur NOT IN (";
            $sql            .= " SELECT no_faktur FROM history_im_tpindah_barang ";
            $sql            .= " WHERE no_faktur!=''";
            if($tgl!='')
            {

                //$sql        .= " AND date(tgl) = '$tgl'";
            }       
            if($bukandaripusat!='')
            {
                $sql        .= " and kd_gudang_asal <> '0001' ";
            }
            $sql            .= " AND sinkronisasi = 'OK' ) ";
            $this->db->where($sql,'',false);
        }
        $this->db->select('*,date(tgl) as tgl');
        $this->db->order_by('no_faktur');
        return $this->db->get('history_im_tpindah_barang');
    }
    function get_history_dtl($nofaktur='')
    {
        if($nofaktur!='')
        {
            $this->db->where('no_faktur',$nofaktur);
        }
        $this->db->select('*,barang_nm');
        $this->db->join('im_mbarang','history_im_tpindah_barang_dtl.kd_barang=im_mbarang.barang_kd','left outer');
        return $this->db->get('history_im_tpindah_barang_dtl');
    }
    function get_history_dtl_imei($nofaktur='',$kdbarang='')
    {
        if($nofaktur!='')
        {
            $this->db->where('no_faktur',$nofaktur);
        }
        if($kdbarang!='')
        {
            $this->db->where('kd_barang',$kdbarang);
        }
        return $this->db->get('history_im_tpindah_barang_dtl_imei');
    }
    function update_history($kd,$data)
    {
        $this->db->where('no_faktur',$kd);
        return $this->db->update('history_im_tpindah_barang',$data);
    }
}

?>