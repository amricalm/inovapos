<?php

class Tukar_model extends CI_Model
{
    private $tabel  = 'ac_ttukar';
    private $key    = 'no_faktur';
    
    function __construct()
    {
        parent::__construct();
    }
    
    function getAll($isArray=false)
    {
        $this->db->select($this->key.', tgl');
        $this->db->from($this->tabel);
        $query = $this->db->get();
        
        if ($isArray)
        {
            return $query->result_array();
        }
        else
        {
            return $query->result();
        }
    }
    function getAll2()
    {
        $this->db->select($this->key.', tgl');
        $this->db->from($this->tabel);
        $query = $this->db->get();
        return $query->result_array();
    }
    function getCombo()
    {
        $this->db->select($this->key.', tgl');
        $this->db->from($this->tabel);
        $this->db->order_by('tgl');
        $query = $this->db->get();
        
        return arrayToSelect($query->result_array(),$this->key,'tgl',true,'-- Pilih No Faktur --');
    }
    
    function get($kd)
    {
        $this->db->select($this->key.', tgl');
        $this->db->from($this->tabel);
        $this->db->where($this->key,$kd);
        $query = $this->db->get();
        return $query->result();
    }
    
    function getBelumSinkron()
    {
        $this->db->distinct();
        $this->db->select('tgl,shift');
        $this->db->from($this->tabel);
        $this->db->where("sinkronisasi",0);
        $query = $this->db->get();
        return $query;
    }

    function get2($kd)
    {
        $sql = " select hdr.no_faktur, tgl,ket, dtl.*
                    ,mbr.barang_nm
                from history_ac_tjual hdr
                inner join history_ac_tjual_dtl dtl
                    on hdr.no_faktur = dtl.no_faktur
                inner join im_mbarang mbr
                    on dtl.kd_barang = mbr.barang_kd                   
                where hdr.no_faktur = '$kd'
                ";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
    

    function simpan($update=0,$db=null)
    {
        if($update)
        {
            $this->db->where($this->key,$db[$this->key]);
            $this->db->update($this->tabel,$db);
        }
        else
        {
            $this->db->insert($this->tabel,$db);
        }
    }
    function hapus($kd)
    {
        $this->db->where($this->key, $kd);
        $this->db->delete($this->tabel);
    }
    
    function getKirim($tgl,$shift)
    {
        $array                          = array();
        
        $this->db->where('sys_col','kd_gudang');
        $kdgudang                       = $this->db->get('sys_var')->row_array();
        
        $this->db->where('date(tgl)',$tgl);
        $this->db->where('shift', $shift);
        $urutan1                = $this->db->get('ac_ttukar');
        
        $seq                    = 0;
        foreach($urutan1->result() as $rowurutan1)
        {

            $array[$seq]['no_faktur']   = $rowurutan1->no_faktur.$kdgudang['sys_val'];
            $array[$seq]['tgl']         = $rowurutan1->tgl;
            $array[$seq]['faktur_jual']= $rowurutan1->faktur_jual;
            $array[$seq]['ket']         = $rowurutan1->ket;
            $array[$seq]['shift']       = $rowurutan1->shift;
            $array[$seq]['uang_tunai']   = $rowurutan1->uang_tunai;

            $this->db->where('no_faktur',$rowurutan1->no_faktur);
            $urutan2                    = $this->db->get('ac_ttukar_dtl');
            if($urutan2->num_rows()>0)
            {
                $seq2                   = 0;
                foreach($urutan2->result() as $rowurutan2)
                {
                    $array[$seq]['pos_ttukar_dtls'][$seq2]['kd_dtl']     = $rowurutan2->kd_dtl;
                    $array[$seq]['pos_ttukar_dtls'][$seq2]['no_faktur']  = $rowurutan2->no_faktur.$this->session->userdata('outlet_kd');
                    $array[$seq]['pos_ttukar_dtls'][$seq2]['kd_barang']  = $rowurutan2->kd_barang;
                    $array[$seq]['pos_ttukar_dtls'][$seq2]['qty']        = $rowurutan2->qty;
                    $array[$seq]['pos_ttukar_dtls'][$seq2]['harga']      = $rowurutan2->harga;

                    $this->db->where('kd_dtl',$rowurutan2->kd_dtl);
                    $this->db->where('kd_barang',$rowurutan2->kd_barang);

                    $urutan3                                    = $this->db->get('ac_ttukar_dtl_imei');
                    if($urutan3->num_rows()>0)
                    {
						$seq3 = 0;
						foreach($urutan3->result() as $rowurutan3)
						{
                            $array[$seq]['pos_ttukar_dtls'][$seq2]['pos_ttukar_dtl_imeis'][$seq3]['kd_barang']  = $rowurutan3->kd_barang;
                            $array[$seq]['pos_ttukar_dtls'][$seq2]['pos_ttukar_dtl_imeis'][$seq3]['kd_dtl']     = $rowurutan3->kd_dtl;
                            $array[$seq]['pos_ttukar_dtls'][$seq2]['pos_ttukar_dtl_imeis'][$seq3]['urutan']     = $rowurutan3->urutan;
	                        $array[$seq]['pos_ttukar_dtls'][$seq2]['pos_ttukar_dtl_imeis'][$seq3]['imei']       = $rowurutan3->imei;
							$seq3++;
						}
                    }
                    else
                    {
                        $array[$seq]['pos_ttukar_dtls'][$seq2]['pos_ttukar_dtl_imeis']   = array();
                    }
                    $seq2++;
                }
            }
            
            $this->db->where('no_faktur',$rowurutan1->no_faktur);
            $arrMasuk                    = $this->db->get('ac_ttukar_masuk_dtl');
            if($arrMasuk->num_rows()>0)
            {
                $seq2                   = 0;
                foreach($arrMasuk->result() as $row_masuk)
                {
                    $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['kd_dtl']     = $row_masuk->kd_dtl;
                    $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['no_faktur']  = $row_masuk->no_faktur.$this->session->userdata('outlet_kd');
                    $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['kd_barang']  = $row_masuk->kd_barang;
                    $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['qty']        = $row_masuk->qty;
                    $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['harga']      = $row_masuk->harga;

                    $this->db->where('kd_dtl',$row_masuk->kd_dtl);
                    $this->db->where('kd_barang',$row_masuk->kd_barang);

                    $urutan3                                    = $this->db->get('ac_ttukar_masuk_dtl_imei');
                    if($urutan3->num_rows()>0)
                    {
						$seq3 = 0;
						foreach($urutan3->result() as $rowurutan3)
						{
                            $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['pos_ttukar_masuk_dtl_imeis'][$seq3]['kd_barang']  = $rowurutan3->kd_barang;
                            $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['pos_ttukar_masuk_dtl_imeis'][$seq3]['kd_dtl']     = $rowurutan3->kd_dtl;
                            $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['pos_ttukar_masuk_dtl_imeis'][$seq3]['urutan']     = $rowurutan3->urutan;
	                        $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['pos_ttukar_masuk_dtl_imeis'][$seq3]['imei']       = $rowurutan3->imei;
							$seq3++;
						}
                    }
                    else
                    {
                        $array[$seq]['pos_ttukar_masuk_dtls'][$seq2]['pos_ttukar_masuk_dtl_imeis']   = array();
                    }
                    $seq2++;
                }
            }
            
            $seq++;
        }
        return $array;        
    }

}

?>