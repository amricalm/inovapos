<?php

class Barang_saldo_model2 extends CI_Model
{

    function get_imei($kd,$kdbarang='')
    {
        $CI                     =&get_instance();
        $sql                    = " SELECT tbl.barang_kd,barang_nm,barang_harga_jual as harga_jual,barang_group,imei FROM ";
        $sql                    .= " ( ";
        $sql                    .= " SELECT saldo_barang as barang_kd, saldo_imei as imei FROM im_msaldo_barang_imei ";
        $sql                    .= ($kdbarang!='') ? " WHERE saldo_barang = '$kdbarang'" : "";
        $sql                    .= " UNION ";        
        $sql                    .= " SELECT kd_barang,imei FROM im_tpindah_barang_dtl_imei dtlimei ";
        $sql                    .= " INNER JOIN im_tpindah_barang hdr ON dtlimei.no_faktur=hdr.no_faktur ";        
        $sql                    .= ($kdbarang!='') ? " WHERE kd_barang = '$kdbarang' " : '';      
        $sql                    .= " AND kd_gudang_tujuan = '".$CI->session->userdata('outlet_kd')."' "  ;
        $sql                    .= " ) tbl ";
        $sql                    .= " INNER JOIN im_mbarang ON tbl.barang_kd=im_mbarang.barang_kd ";
        if($kd!='')
        {
            $sql                .= ($kd!='') ? " WHERE imei = '$kd'" : '';
        }
        $sql                    .= ($kd!='') ? " AND " : ' WHERE ';
        $sql                    .= " imei NOT IN ";
        $sql                    .= " ( ";
        $sql                    .= " SELECT imei FROM ac_tjual_dtl_imei ";
        $sql                    .= ($kdbarang!='') ?" WHERE kd_barang = '$kdbarang' " : '';
        //$sql                    .= ($kd!='') ? " AND imei = '$kd'" : '';
        $sql                    .= " UNION ";
        $sql                    .= " SELECT imei FROM im_tpindah_barang_dtl_imei dtlimei ";
        $sql                    .= " INNER JOIN im_tpindah_barang hdr ON dtlimei.no_faktur=hdr.no_faktur ";
        $sql                    .= ($kdbarang!='') ? " WHERE kd_barang = '$kdbarang' " : '';
        $sql                    .= " AND kd_gudang_asal = '".$CI->session->userdata('outlet_kd')."'";
        //$sql                    .= ($kd!='') ? " AND imei = '$kd'" : '';             
        $sql                    .= " ) ";
        $query                  = $this->db->query($sql);                                        
        return $query;
    }

    function getBarangByImei($kd)
    {
        $CI                     =&get_instance();
        $kdgudang           = $CI->session->userdata('outlet_kd');


        $sql    = "SELECT sn.kd_barang as barang_kd, barang_nm,barang_harga_jual as harga_jual,imei 
                FROM im_tpindah_barang_dtl_imei sn 
                INNER JOIN im_mbarang brg 
                    ON sn.kd_barang = brg.barang_kd
                WHERE sn.imei = '$kd' 
                    AND imei NOT IN
                (
                    SELECT imei 
                    FROM ac_tjual_dtl_imei
                    WHERE imei='$kd'
                    UNION
                    SELECT imei
                    FROM im_tpindah_barang  hdr
                    INNER JOIN im_tpindah_barang_dtl dtl
                        ON hdr.no_faktur  = dtl.no_faktur
                    INNER  JOIN  im_tpindah_barang_dtl_imei sn
                        ON dtl.no_faktur = sn.no_faktur
                        AND dtl.kd_barang = sn.kd_barang
                    WHERE sn.imei ='$kd'
                        AND kd_gudang_asal='0026'
                )";
        return $this->db->query($sql);
    }

    function get($tgl='',$kdgroup='',$limit='',$offset='')
    {
        $CI                         = & get_instance();
        $kdgudang                   = $CI->session->userdata('outlet_kd');
        $this->db->select('barang_kd,barang_nm,group_nm,saldo_qty,saldo_gudang,barang_group');
        $this->db->from('im_mbarang');
        $join                       = "barang_kd=saldo_barang and saldo_gudang = '$kdgudang' ";
        $join                       .= ($tgl!='') ? " and saldo_tgl = '$tgl'" : '';
        $this->db->join('im_msaldo_barang',$join,'left outer');
        $this->db->join('im_mgroup_barang','barang_group=group_kd','inner');
        if($kdgroup!='0' && $kdgroup!='') {$this->db->where('barang_group',$kdgroup);}
        if($limit!='')
        {
            $this->db->limit($limit,$offset);
        }
        $this->db->order_by('barang_kd');
        return $this->db->get();
    }
    function get2($tgl='',$kdgroup='',$cari, $limit='',$offset='')
    {
        $CI                         = & get_instance();
        $kdgudang                   = $CI->session->userdata('outlet_kd');
        $this->db->select('barang_kd,barang_nm,group_nm,saldo_qty,saldo_gudang,barang_group');
        $this->db->from('im_mbarang');
        $join                       = "barang_kd=saldo_barang and saldo_gudang = '$kdgudang' ";
        $join                       .= ($tgl!='') ? " and saldo_tgl = '$tgl'" : '';
        $this->db->join('im_msaldo_barang',$join,'left outer');
        $this->db->join('im_mgroup_barang','barang_group=group_kd','inner');
        if($kdgroup!='0' && $kdgroup!='') {$this->db->where('barang_group',$kdgroup);}
        if($cari!='') 
        {
            $this->db->or_like('barang_nm',$cari);
            $this->db->or_like('barang_kd',$cari);
        }
        if($limit!='')
        {
            $this->db->limit($limit,$offset);
        }
        $this->db->order_by('barang_kd');
        return $this->db->get();
    }

    function get_saw($tgl='')
    {
        
    	$this->db->select('max(saldo_tgl) as tgl',false);
    	$tgl = $this->db->get('im_msaldo_barang')->row()->tgl;
        
        $this->db->select('im_msaldo_barang.saldo_tgl,barang_kd,barang_nm,saldo_qty,saldo_imei');
        $this->db->from('im_mbarang');
        $join       = " barang_kd=saldo_barang ";
        $join       .= " and saldo_tgl = '$tgl'" ;
        $this->db->join('im_msaldo_barang',$join,'inner');
        $this->db->join('im_msaldo_barang_imei','im_msaldo_barang.saldo_barang = im_msaldo_barang_imei.saldo_barang and im_msaldo_barang.saldo_tgl = im_msaldo_barang_imei.saldo_tgl','left');
        $this->db->order_by('barang_kd');
        return $this->db->get();
    }
    function get_saldo_awal($kdbarang)
    {
        $tgl                        = $this->get_tgl_saw($kdbarang);
        $saldo                      = 0;
        
        $CI                         =& get_instance();
        $this->db->select('saldo_qty');
        $this->db->where('saldo_barang',$kdbarang);
        $this->db->where('saldo_gudang',$CI->session->userdata('outlet_kd'));
        $this->db->where("saldo_tgl = '".$tgl."'",'',false);
        $saldoawal = $this->db->get('im_msaldo_barang');
        $nilaisaldoawal = ($saldoawal->num_rows() > 0) ? $saldoawal->row()->saldo_qty : 0;
        return $nilaisaldoawal;
    }
    function get_saldox($kdbarang)
    {
        $CI                         = & get_instance();
        $tgls                       = $CI->session->userdata('tanggal');
        $tgl                        = $this->get_tgl_saw($kdbarang);
        $etgls                      = explode('-',$tgls);
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0));
        
        
        $saldo                      = 0;
        /*
         * Lihat Qty Saldo
         */
        $this->db->select('saldo_qty');
        $this->db->where('saldo_barang',$kdbarang);
        $this->db->where('saldo_gudang',$CI->session->userdata('outlet_kd'));
        $this->db->where("saldo_tgl = '".$tgl."'",'',false);
        $saldoawal = $this->db->get('im_msaldo_barang');
        $nilaisaldoawal = ($saldoawal->num_rows() > 0) ? $saldoawal->row()->saldo_qty : 0;
        //$nilaisaldoawal = $this->get_saldoz($kdbarang,$CI->session->userdata('tanggal'),$CI->session->userdata('shift'));
        
        /*
         * Lihat Penjualan
         */
        $this->db->select_sum('qty');
        $this->db->join('ac_tjual','ac_tjual_dtl.no_faktur=ac_tjual.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $penjualan = $this->db->get('ac_tjual_dtl');
        $nilaipenjualan = ($penjualan->num_rows() > 0) ? $penjualan->row()->qty : 0;
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_asal',$CI->session->userdata('outlet_kd'));
        $mutasikeluar = $this->db->get('im_tpindah_barang_dtl');
        $nilaimutasikeluar = ($mutasikeluar->num_rows() > 0) ? $mutasikeluar->row()->qty : 0;
        
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_tujuan',$CI->session->userdata('outlet_kd'));
        $mutasimasuk = $this->db->get('im_tpindah_barang_dtl');
        $nilaimutasimasuk = ($mutasimasuk->num_rows() > 0) ? $mutasimasuk->row()->qty : 0;
        
        /*
         * Lihat Stok yang bernilai Negatif
         */
//        $this->db->select_sum('qty');
//        $this->db->join('im_tsaldo_barang','im_tsaldo_barang.no_faktur=im_tsaldo_barang_dtl.no_faktur','inner');
//        $this->db->where("tgl = '".$tgl."'",'',false);
//        $this->db->where('qty < 0','',false);
//        $stoknegatif = $this->db->get('im_tsaldo_barang_dtl');
//        $nilaistoknegatif = ($stoknegatif->num_rows() > 0) ? $stoknegatif->row()->qty : 0;
        /*
         * Lihat Stok yang bernilai Positif
         */
//        $this->db->select_sum('qty');
//        $this->db->join('im_tsaldo_barang','im_tsaldo_barang.no_faktur=im_tsaldo_barang_dtl.no_faktur','inner');
//        $this->db->where("tgl = '".$tgl."'",'',false);
//        $this->db->where('qty > 0','',false);
//        $stokpositif = $this->db->get('im_tsaldo_barang_dtl');
//        $nilaistokpositif = ($stokpositif->num_rows() > 0) ? $stokpositif->row()->qty : 0;
        
        //$saldo                      = ((int)$nilaisaldoawal + (int)$nilaimutasimasuk + (int)$nilaistokpositif) - ((int)$nilaimutasikeluar + (int)$nilaipenjualan + (int)$nilaistoknegatif);
        $saldo                      = ((int)$nilaisaldoawal + (int)$nilaimutasimasuk) - ((int)$nilaimutasikeluar + (int)$nilaipenjualan);
        return $saldo;
    }
    function get_saldoz($kdbarang,$tgls,$shift)
    {
        $tgl                        = $this->get_tgl_saw($kdbarang);
        $saldo                      = 0;
        $etgls                      = explode('-',$tgls);
        $tgl_sebelumnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]-1,$etgls[0],0));
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0));
        /*
         * Lihat Qty Saldo
         */
        $CI                         = & get_instance();
        $this->db->select('saldo_qty');
        $this->db->where('saldo_barang',$kdbarang);
        $this->db->where('saldo_gudang',$CI->session->userdata('outlet_kd'));
        $this->db->where("saldo_tgl = '".$tgl."'",'',false);
        $saldoawal = $this->db->get('im_msaldo_barang');
        $nilaisaldoawal = ($saldoawal->num_rows() > 0) ? $saldoawal->row()->saldo_qty : 0;
        /*
         * Lihat Penjualan
         */
        $this->db->select_sum('qty');
        $this->db->join('ac_tjual','ac_tjual_dtl.no_faktur=ac_tjual.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $penjualan = $this->db->get('ac_tjual_dtl');
        $nilaipenjualan = ($penjualan->num_rows() > 0) ? $penjualan->row()->qty : 0;
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_asal',$CI->session->userdata('outlet_kd'));
        $mutasikeluar = $this->db->get('im_tpindah_barang_dtl');
        $nilaimutasikeluar = ($mutasikeluar->num_rows() > 0) ? $mutasikeluar->row()->qty : 0;
        
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_tujuan',$CI->session->userdata('outlet_kd'));
        $mutasimasuk = $this->db->get('im_tpindah_barang_dtl');
        $nilaimutasimasuk = ($mutasimasuk->num_rows() > 0) ? $mutasimasuk->row()->qty : 0;
        /*
         * Lihat Stok yang bernilai Negatif
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tsaldo_barang','im_tsaldo_barang.no_faktur=im_tsaldo_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('qty < 0','',false);
        $stoknegatif = $this->db->get('im_tsaldo_barang_dtl');
        $nilaistoknegatif = ($stoknegatif->num_rows() > 0) ? $stoknegatif->row()->qty : 0;
        /*
         * Lihat Stok yang bernilai Positif
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tsaldo_barang','im_tsaldo_barang.no_faktur=im_tsaldo_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('qty > 0','',false);
        $stokpositif = $this->db->get('im_tsaldo_barang_dtl');
        $nilaistokpositif = ($stokpositif->num_rows() > 0) ? $stokpositif->row()->qty : 0;
        
        $saldo                      = ((int)$nilaisaldoawal + (int)$nilaimutasimasuk + (int)$nilaistokpositif) - ((int)$nilaimutasikeluar + (int)$nilaipenjualan + (int)$nilaistoknegatif);
        return $saldo;
    }
    function get_tgl_saw($kdbarang='')
    {
//    	$this->db->select('date(max(saldo_tgl)) as tgl',false);
//        if($kdbarang!='') {$this->db->where('saldo_barang',$kdbarang);}
//    	$tgl = $this->db->get('im_msaldo_barang')->row()->tgl;
        
        $this->db->where('sys_col','periode_saldo_awal_barang');
        $tgl = $this->db->get('sys_var')->row()->sys_val;
        return $tgl;
    }
    function simpan($data)
    {
        return $this->db->insert('im_msaldo_barang',$data);
    }
    function simpan_imei($data)
    {
        return $this->db->insert('im_msaldo_barang_imei',$data);
    }
    function hapus_imei($data)
    {
        $this->db->where('saldo_tgl',$data['saldo_tgl']);
        $this->db->where('saldo_barang',$data['saldo_barang']);
        $this->db->where('saldo_gudang',$data['saldo_gudang']);
        $this->db->where('saldo_imei',$data['saldo_imei']);
        return $this->db->delete('im_msaldo_barang_imei');
    }
    function update($data)
    {
        $this->db->where('saldo_kd',$data['saldo_kd']);
        $this->db->where('saldo_barang',$data['saldo_barang']);
        $this->db->where('saldo_gudang',$data['saldo_gudang']);
        $this->db->where('saldo_tgl',$data['saldo_tgl']);
        return $this->db->update('im_msaldo_barang',array('saldo_qty'=>$data['saldo_qty']));
    }
    function update_imei($data)
    {
        $this->db->where('saldo_barang',$data['saldo_barang']);
        $this->db->where('saldo_gudang',$data['saldo_gudang']);
        $this->db->where('saldo_tgl',$data['saldo_tgl']);
        return $this->db->update('im_msaldo_barang_imei',array('saldo_imei'=>$data['saldo_imei']));
    }
    function cek($kdbarang='',$kdgudang='',$tgl='')
    {
        if($kdbarang!=''){$this->db->where('saldo_barang',$kdbarang);}
        if($kdgudang!=''){$this->db->where('saldo_gudang',$kdgudang);}
        if($tgl!=''){$this->db->where('saldo_tgl',$tgl);}
        return $this->db->get('im_msaldo_barang');
    }
    function cek_imei($kdbarang='',$kdgudang='',$tgl='')
    {
        if($kdbarang!=''){$this->db->where('saldo_barang',$kdbarang);}
        if($kdgudang!=''){$this->db->where('saldo_gudang',$kdgudang);}
        if($tgl!=''){$this->db->where('saldo_tgl',$tgl);}
        return $this->db->get('im_msaldo_barang_imei');
    }
    function get_imei_saldo($kdbarang='',$kdgudang='',$tgl='')
    {
        if($kdbarang!=''){$this->db->where('saldo_barang',$kdbarang);}
        if($kdgudang!=''){$this->db->where('saldo_gudang',$kdgudang);}
        if($tgl!=''){$this->db->where('saldo_tgl',$tgl);}
        $where                      = 'saldo_imei NOT IN 
                        (
                        SELECT imei FROM im_tpindah_barang_dtl_imei
                        UNION 
                        SELECT imei FROM ac_tjual_dtl_imei
                        )';
        $this->db->where($where,'',false);
        return $this->db->get('im_msaldo_barang_imei');
    }

    function get_saldo2($kd_barang,$tgl)
    {
        $tgl_saw = $this->sys_var_model->get(PERIODE_SAW_BARANG);
        //echo $kd_barang;
        $sql = " SELECT ms.barang_kd,barang_nm,qsaw,qin,qout
                    FROM 
                        (   
                            SELECT ms.barang_kd, barang_nm,  
                                    IFNULL(masuk.jmh, 0) qin,IFNULL(jual.jmh, 0) +  IFNULL(keluar.jmh, 0) qout, 
                                    IFNULL(saw.saldo_qty, 0)  + IFNULL(saw_masuk.jmh, 0) - IFNULL(saw_jual.jmh, 0) - IFNULL(saw_keluar.jmh, 0) AS qsaw
                            FROM  im_mbarang ms 
                            LEFT OUTER JOIN im_msaldo_barang saw 
                                ON ms.barang_kd = saw.saldo_barang 
                                AND saw.saldo_tgl ='$tgl_saw'

                            -- PENJUALAN
                            LEFT OUTER JOIN
                                (
                                    SELECT IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM   ac_tjual hdr 
                                    INNER JOIN ac_tjual_dtl dtl
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE   (tgl BETWEEN '$tgl_saw'  AND '" . tgl_minus_hari($tgl,1)  ."')
                                    GROUP BY kd_barang 
                                ) saw_jual 
                                    ON ms.barang_kd = saw_jual.kd_barang

                            -- PINDAH BARANG KELUAR
                            LEFT OUTER JOIN
                                (
                                    SELECT  IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM    im_tpindah_barang hdr 
                                    INNER JOIN im_tpindah_barang_dtl dtl 
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE  (tgl BETWEEN '$tgl_saw'  AND '" . tgl_minus_hari($tgl,1)  ."')
                                        AND kd_gudang_asal ='". KD_GUDANG ."'
                                ) saw_keluar 
                                    ON ms.barang_kd = saw_keluar.kd_barang

                            -- PINDAH BARANG MASUK
                            LEFT OUTER JOIN
                                (
                                    SELECT  IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM    im_tpindah_barang hdr 
                                    INNER JOIN im_tpindah_barang_dtl dtl 
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE  (tgl BETWEEN '$tgl_saw'  AND '" . tgl_minus_hari($tgl,1)  ."')
                                        AND kd_gudang_tujuan ='". KD_GUDANG ."'
                                ) saw_masuk 
                                 ON ms.barang_kd = saw_masuk.kd_barang


                            -- MUTASI MASUK - KELUAR TANGGAL AKTIF --------------------------------------------
                            -- PENJUALAN
                            
                            LEFT OUTER JOIN
                                (
                                    SELECT IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM   ac_tjual hdr 
                                    INNER JOIN ac_tjual_dtl dtl
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE   (tgl = '$tgl')
                                    GROUP BY kd_barang 
                                ) jual 
                                ON ms.barang_kd = jual.kd_barang 
                                

                            -- PINDAH BARANG KELUAR
                            LEFT OUTER JOIN
                                (
                                    SELECT IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM    im_tpindah_barang hdr 
                                    INNER JOIN im_tpindah_barang_dtl dtl
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE  (tgl = '$tgl')
                                        AND kd_gudang_asal ='". KD_GUDANG ."'
                                ) keluar 
                                ON ms.barang_kd = keluar.kd_barang
                            
                            -- PINDAH BARANG MASUK
                            LEFT OUTER JOIN
                                (
                                    SELECT     IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM    im_tpindah_barang hdr 
                                    INNER JOIN im_tpindah_barang_dtl dtl 
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE  (tgl = '$tgl')
                                        AND kd_gudang_tujuan ='". KD_GUDANG ."'
                                )masuk 
                                ON ms.barang_kd= masuk.kd_barang

                            WHERE barang_kd = '$kd_barang'

                        ) ms ";
        return $this->db->query($sql);
    }
    function get_saldo($kd_barang)
    {
        $tgl = date('Y-m-d');
        $tgl_saw = $this->sys_var_model->get(PERIODE_SAW_BARANG);
        //echo $kd_barang;
        $sql = " SELECT ms.barang_kd,barang_nm,qsaw,qin,qout
                    FROM 
                        (   
                            SELECT ms.barang_kd, barang_nm,  
                                    IFNULL(masuk.jmh, 0) qin,IFNULL(jual.jmh, 0) +  IFNULL(keluar.jmh, 0) qout, 
                                    IFNULL(saw.saldo_qty, 0)  + IFNULL(saw_masuk.jmh, 0) - IFNULL(saw_jual.jmh, 0) - IFNULL(saw_keluar.jmh, 0) AS qsaw
                            FROM  im_mbarang ms 
                            LEFT OUTER JOIN im_msaldo_barang saw 
                                ON ms.barang_kd = saw.saldo_barang 
                                AND saw.saldo_tgl ='$tgl_saw'

                            -- PENJUALAN
                            LEFT OUTER JOIN
                                (
                                    SELECT IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM   ac_tjual hdr 
                                    INNER JOIN ac_tjual_dtl dtl
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE   (tgl BETWEEN '$tgl_saw'  AND '" . tgl_minus_hari($tgl,1)  ."')
                                    GROUP BY kd_barang 
                                ) saw_jual 
                                    ON ms.barang_kd = saw_jual.kd_barang

                            -- PINDAH BARANG KELUAR
                            LEFT OUTER JOIN
                                (
                                    SELECT  IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM    im_tpindah_barang hdr 
                                    INNER JOIN im_tpindah_barang_dtl dtl 
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE  (tgl BETWEEN '$tgl_saw'  AND '" . tgl_minus_hari($tgl,1)  ."')
                                        AND kd_gudang_asal ='". KD_GUDANG ."'
                                ) saw_keluar 
                                    ON ms.barang_kd = saw_keluar.kd_barang

                            -- PINDAH BARANG MASUK
                            LEFT OUTER JOIN
                                (
                                    SELECT  IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM    im_tpindah_barang hdr 
                                    INNER JOIN im_tpindah_barang_dtl dtl 
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE  (tgl BETWEEN '$tgl_saw'  AND '" . tgl_minus_hari($tgl,1)  ."')
                                        AND kd_gudang_tujuan ='". KD_GUDANG ."'
                                ) saw_masuk 
                                 ON ms.barang_kd = saw_masuk.kd_barang


                            -- MUTASI MASUK - KELUAR TANGGAL AKTIF --------------------------------------------
                            -- PENJUALAN
                            
                            LEFT OUTER JOIN
                                (
                                    SELECT IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM   ac_tjual hdr 
                                    INNER JOIN ac_tjual_dtl dtl
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE   (tgl = '$tgl')
                                    GROUP BY kd_barang 
                                ) jual 
                                ON ms.barang_kd = jual.kd_barang 
                                

                            -- PINDAH BARANG KELUAR
                            LEFT OUTER JOIN
                                (
                                    SELECT IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM    im_tpindah_barang hdr 
                                    INNER JOIN im_tpindah_barang_dtl dtl
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE  (tgl = '$tgl')
                                        AND kd_gudang_asal ='". KD_GUDANG ."'
                                ) keluar 
                                ON ms.barang_kd = keluar.kd_barang
                            
                            -- PINDAH BARANG MASUK
                            LEFT OUTER JOIN
                                (
                                    SELECT     IFNULL(kd_barang,'')kd_barang, SUM(qty) jmh
                                    FROM    im_tpindah_barang hdr 
                                    INNER JOIN im_tpindah_barang_dtl dtl 
                                        ON hdr.no_faktur = dtl.no_faktur
                                    WHERE  (tgl = '$tgl')
                                        AND kd_gudang_tujuan ='". KD_GUDANG ."'
                                )masuk 
                                ON ms.barang_kd= masuk.kd_barang

                            WHERE barang_kd = '$kd_barang'

                        ) ms ";
        $hasil = $this->db->query($sql);
        $saw = 0;
        $masuk = 0;
        $keluar = 0;
        if ($hasil->num_rows()>0)
        {
            $saw = $hasil->row()->qsaw;
            $masuk = $hasil->row()->qin;
            $keluar = $hasil->row()->qout;
        }
        $saldo = $saw+$masuk-$keluar;
        return $saldo;
    }
    function cek_imei_saldo($imei)
    {
        $hasil = 0;
        $this->db->select('saldo_imei');
        $this->db->where('saldo_imei', $imei);
        $qry = $this->db->get('im_msaldo_barang_imei');
        if($qry->num_rows()>0)
        {
            $hasil = 1;
        }

        return $hasil;

    }

    function get_stok_by_imei($kd_barang,$tgl)
    {
        $hasil=0;
        $this->db->where('saldo_barang', $kd_barang);
        $this->db->where('saldo_tgl', $tgl);
        $this->db->from('im_msaldo_barang_imei');
        
        $hasil  = $this->db->count_all_results();
        return $hasil;

    }
    
    function kartu_stok($kdbarang,$tgl_sekarang)
    {
        $CI                         = & get_instance();
        $hasil                      = array();
        $tgls                       = $CI->session->userdata('tanggal');
        $tgl                        = $this->get_tgl_saw($kdbarang);
        $etgls                      = explode('-',$tgls);
        $tgl_sebelumnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]-1,$etgls[0],0));
        $tgl_sesudahnya             = date('Y-m-d',mktime(0,0,0,$etgls[1],$etgls[2]+1,$etgls[0],0));
        
        /*
         * Lihat Qty Saldo
         */
        $this->db->select('saldo_qty');
        $this->db->where('saldo_barang',$kdbarang);
        $this->db->where('saldo_gudang',$CI->session->userdata('outlet_kd'));
        $this->db->where("saldo_tgl = '".$tgl."'",'',false);
        $saldoawal = $this->db->get('im_msaldo_barang');
        $hasil['nilaisaldoawal'] = ($saldoawal->num_rows() > 0) ? $saldoawal->row()->saldo_qty : 0;
        //$nilaisaldoawal = $this->get_saldoz($kdbarang,$CI->session->userdata('tanggal'),$CI->session->userdata('shift'));
        /*
         * Lihat Penjualan
         */
        $this->db->select_sum('qty');
        $this->db->join('ac_tjual','ac_tjual_dtl.no_faktur=ac_tjual.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $penjualan = $this->db->get('ac_tjual_dtl');
        $hasil['nilaipenjualan'] = ($penjualan->num_rows() > 0) ? $penjualan->row()->qty : 0;
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_asal',$CI->session->userdata('outlet_kd'));
        $mutasikeluar = $this->db->get('im_tpindah_barang_dtl');
        $hasil['nilaimutasikeluar'] = ($mutasikeluar->num_rows() > 0) ? $mutasikeluar->row()->qty : 0;
        
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl."'",'',false);
        $this->db->where("tgl < '".$tgl_sebelumnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_tujuan',$CI->session->userdata('outlet_kd'));
        $mutasimasuk = $this->db->get('im_tpindah_barang_dtl');
        $hasil['nilaimutasimasuk'] = ($mutasimasuk->num_rows() > 0) ? $mutasimasuk->row()->qty : 0;

        $hasil['saldo']                 = ((int)$hasil['nilaisaldoawal'] + (int)$hasil['nilaimutasimasuk']) - ((int)$hasil['nilaimutasikeluar'] + (int)$hasil['nilaipenjualan']);
        
        /*
         * Lihat Penjualan Hari ini
         */
        $this->db->select_sum('qty');
        $this->db->join('ac_tjual','ac_tjual_dtl.no_faktur=ac_tjual.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl_sekarang."'",'',false);
        $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $penjualan = $this->db->get('ac_tjual_dtl');
        $hasil['nilaipenjualansekarang'] = ($penjualan->num_rows() > 0) ? $penjualan->row()->qty : 0;
        //echo $this->db->last_query();
        //echo '<br/>';
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain Hari ini
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl_sekarang."'",'',false);
        $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_asal',$CI->session->userdata('outlet_kd'));
        $mutasikeluar = $this->db->get('im_tpindah_barang_dtl');
        $hasil['nilaimutasikeluarsekarang'] = ($mutasikeluar->num_rows() > 0) ? $mutasikeluar->row()->qty : 0;
        
        /*
         * Lihat Pindah Barang yang tujuannya Gudang lain Hari ini
         */
        $this->db->select_sum('qty');
        $this->db->join('im_tpindah_barang','im_tpindah_barang.no_faktur=im_tpindah_barang_dtl.no_faktur','inner');
        $this->db->where("tgl >= '".$tgl_sekarang."'",'',false);
        $this->db->where("tgl < '".$tgl_sesudahnya."'",'',false);
        $this->db->where('kd_barang',$kdbarang);
        $this->db->where('kd_gudang_tujuan',$CI->session->userdata('outlet_kd'));
        $mutasimasuk = $this->db->get('im_tpindah_barang_dtl');
        $hasil['nilaimutasimasuksekarang'] = ($mutasimasuk->num_rows() > 0) ? $mutasimasuk->row()->qty : 0;

        $hasil['masuk']             = $hasil['nilaimutasimasuksekarang'];
        $hasil['keluar']            = $hasil['nilaimutasikeluarsekarang'] + $hasil['nilaipenjualansekarang'];

        return $hasil;
    }
}
