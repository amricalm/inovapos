<?php

class Export extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    function stok_opname()
    {
        $this->load->model('barang_model');
        $this->load->model('barang_imei_model');
        $this->load->model('barang_saldo_model');
        $this->load->model('barang_saldo_model2');
        $data                           = $this->app_model->general();
        $data['option_tampilan']        = 'tanpa_menu';
        if($data['tutup_shift'])
        {
            $etglsekarang               = explode('-',$this->session->userdata('tanggal'));
            // if($this->session->userdata('shift')=='1')
            // {
            //     $shiftselanjutnya       = '2';
            //     $tglselanjutnya         = $this->session->userdata('tanggal');
            // }
            // else
            // {
                $shiftselanjutnya       = '1';
                $tglselanjutnya         = date('Y-m-d',mktime(0,0,0,$etglsekarang[1],$etglsekarang[2]+1,$etglsekarang[0]));
            // }
            $query                      = $this->barang_saldo_model->saldo('',$tglselanjutnya,$shiftselanjutnya);
        }
        else 
        {
            $query                      = $this->barang_saldo_model->saldo_hari_ini();
        }
        $namafile_awal                  = 'saldo_awal_'.$this->session->userdata('outlet_kd').'_'.$this->session->userdata('tanggal').'_'.$this->session->userdata('shift');
        $namafile                       = $namafile_awal.".xls";
        $data['cetak']                  = '';
        //$ourFileName                = $data['base_upload'].$namafile;
        //$ourFileHandle              = fopen($ourFileName, 'w') or die("can't open file");
        //fwrite($ourFileHandle,'Stok '.$this->session->userdata('outlet_nm')."\r\n");
        //fwrite($ourFileHandle,'Tanggal : '.$this->session->userdata('tanggal')."\r\n");
        //fwrite($ourFileHandle,'Shift : '.$this->session->userdata('shift')."\r\n");
        $data['cetak']                  .= 'Stok '.$this->session->userdata('outlet_nm').'<br/>';
        $data['cetak']                  .= 'Tanggal : '.$this->session->userdata('tanggal').'<br/>';
        $data['cetak']                  .= 'Shift : '.$this->session->userdata('shift').'<br/>';
        $data['cetak']                  .= '<table>';
        $data['cetak']                  .= '<tr>';
        $data['cetak']                  .= '<td>Kode Barang</td>';
        $data['cetak']                  .= '<td>Nama Barang</td>';
        $data['cetak']                  .= '<td>Stok</td>';
        $data['cetak']                  .= '<td>IMEI</td>';
        $data['cetak']                  .= '</tr>'; 
        for($i=0;$i<count($query);$i++)
        {
            if($query[$i]['saldo_qty']!='0' && $query[$i]['saldo_qty']!='')
            {
                $namabarang             = $this->barang_model->get($query[$i]['saldo_barang'],'','','','')->row()->barang_nm;
                $dataimei               = $this->barang_imei_model->get($query[$i]['saldo_barang'],'',1);
                if($dataimei->num_rows()>0)
                {
                    foreach($dataimei->result() as $rowimei)
                    {
                        //fwrite($ourFileHandle,$query[$i]['saldo_barang'] . ';' . $namabarang . ';' . $query[$i]['saldo_qty'] . ';' . $rowimei->imei_no . "\r\n");
                        $data['cetak']  .= '<tr>';
                        $data['cetak']  .= '<td>'.$query[$i]['saldo_barang'].'</td>';
                        $data['cetak']  .= '<td>'.$namabarang.'</td>';
                        $data['cetak']  .= '<td>'.$query[$i]['saldo_qty'].'</td>';
                        $data['cetak']  .= '<td>'.$rowimei->imei_no.'</td>';
                        $data['cetak']  .= '</tr>';   
                    }
                }
                else
                {
                    //fwrite($ourFileHandle,$query[$i]['saldo_barang'] . ';' . $namabarang . ';' . $query[$i]['saldo_qty'] . ';'."\r\n");
                    $data['cetak']      .= '<tr>';
                    $data['cetak']      .= '<td>'.$query[$i]['saldo_barang'].'</td>';
                    $data['cetak']      .= '<td>'.$namabarang.'</td>';
                    $data['cetak']      .= '<td>'.$query[$i]['saldo_qty'].'</td>';
                    $data['cetak']      .= '<td>&nbsp;</td>';
                    $data['cetak']      .= '</tr>';
                }
            }
        }
        $data['cetak']              .= '</table>';
        $data['halaman']            = 'export';
        //print_r($data['option_tampilan']);
        $this->load->view('layout/index',$data);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename= $namafile");
        
//        
//        $sdata['log_col']               = 'close stock opname';
//        $sdata['log_val']               = 'sukses';
//        $sdata['tgl']                   = $this->session->userdata('tanggal').' '.date('h:i:s');
//        $sdata['tipe']                  = 'close stock opname';
//        $sdata['uid']                   = $this->session->userdata('user_kd');
//        $sdata['tgl_tambah']            = $this->session->userdata('tanggal').' '.date('h:i:s');
//        $this->log_proses_model->simpanLogJual($sdata);        
    }

}