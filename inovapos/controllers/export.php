<?php
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Export extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('barang_model');
        $this->load->model('laporan_model');
        $this->load->model('barang_saldo_model');
        $this->load->model('history_kasir_model');
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

    public function export_daftar_barang()
    {
        $this->load->library('excel');// me-load library excel
        $tgl                        = $this->session->userdata('tanggal');
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupbarang');
        $data_all                   = $this->barang_model->get('','','',$data['txtcari'],$data['cbogrup']);
        
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()
                    ->setTitle('Daftar Barang')
                    ->setSubject('Daftar Barang')
                    ->setDescription('Daftar Barang')
                    ->setKeywords('Daftar Barang')
                    ->setCategory('Barang');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->session->userdata('outlet_nm'))->mergeCells('A1:G1')
                    ->setCellValue('A2', 'DAFTAR BARANG')->mergeCells('A2:H2')
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Kode Barang')
                    ->setCellValue('C3', 'Barcode')
                    ->setCellValue('D3', 'Nama Barang')
                    ->setCellValue('E3', 'Satuan')
                    ->setCellValue('F3', 'Group')
                    ->setCellValue('G3', 'Stok')
                    ->setCellValue('H3', 'Harga Jual')
                    ->getStyle('A1')->getFont()->setSize(16)->setBold(true)
        ;
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2')->getFont()->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A3:H3')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(40);
        $spreadsheet->getActiveSheet()->getStyle('A3:H3')
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A3:H3')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('A3:H3')
                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $spreadsheet->getActiveSheet()->getStyle('A3:H3')
                    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        
        $seq = 1 + $this->uri->segment(3);
        $kdbarang = '';
        $i = 4;
        foreach($data_all->result() as $row) {
            $saldo     = $this->barang_saldo_model->saldo_hari_ini($row->barang_kd);
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, ($kdbarang!=$row->barang_kd) ? $seq : '')
                    ->setCellValue('B'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_kd : '')
                    ->setCellValue('C'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_barcode : '')
                    ->setCellValue('D'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_nm : '')
                    ->setCellValue('E'.$i, ($kdbarang!=$row->barang_kd) ? $row->satuan_nm : '')
                    ->setCellValue('F'.$i, ($kdbarang!=$row->barang_kd) ? $row->group_nm : '')
                    ->setCellValue('G'.$i, ($kdbarang!=$row->barang_kd) ? $saldo[0]['saldo_qty'] : '')
                    ->setCellValue('H'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_harga_jual : '')
                    ->getStyle('G:H')->getNumberFormat()->setFormatCode("_(* #,##0_);_(* \(#,##0\);_(* \"-\"??_);_(@_)")
                ;
                $spreadsheet->setActiveSheetIndex(0)->getStyle('C')->getNumberFormat()->setFormatCode("_(* ###0_);_(* \(###0\);_(* \"-\"??_);_(@_)");
            
            //Border style to the cells
            $styleArray = [
                'font' => [
                    'size' => 12
                ]
            ];  
            
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(47);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(18);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);
            $spreadsheet->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
            $spreadsheet->getActiveSheet()->getStyle('A'.$i.':H'.$i)
                        ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('B3')->getAlignment()->setWrapText(true); 
            $i++;
            if($kdbarang!=$row->barang_kd)
            {
                $seq++;
                $kdbarang = $row->barang_kd;
            }
        }

        
        //  Apply border style to the cells
         $spreadsheet->getActiveSheet()->getStyle(
            'A3:' . 
            $spreadsheet->getActiveSheet()->getHighestColumn() . 
            $spreadsheet->getActiveSheet()->getHighestRow()
        )->applyFromArray($styleArray);

        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Daftar Barang '.$tgl);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Set Paper Size
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Daftar Barang '.$tgl.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function export_stok_awal()
    {
        $this->load->library('excel');// me-load library excel
        $data['tgl']                = "'".$this->uri->segment(3)."'";
        $data['kdgrup']             = $this->uri->segment(5);
        $data['shift']              = $this->uri->segment(4);
        $grup                       = ($this->input->post('submit')!='') ? $this->input->post('search') : $this->session->userdata('searchso');
        $this->session->set_userdata(array('searchso'=>$grup));
        $data['filter']		       = $this->session->userdata('searchso');

        $data_all                   = $this->barang_saldo_model->get2($data['tgl'],$data['shift'],$data['kdgrup'],$data['filter'],'','');
        
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()
                    ->setTitle('Stok Awal')
                    ->setSubject('Stok Awal')
                    ->setDescription('Stok Awal')
                    ->setKeywords('Stok Awal')
                    ->setCategory('Stok');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->session->userdata('outlet_nm'))->mergeCells('A1:G1')
                    ->setCellValue('A2', 'STOK AWAL '.$data['tgl'])->mergeCells('A2:E2')
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Kode Barang')
                    ->setCellValue('C3', 'Nama Barang')
                    ->setCellValue('D3', 'Grup')
                    ->setCellValue('E3', 'Jumlah Fisik')
                    ->getStyle('A1')->getFont()->setSize(16)->setBold(true)
        ;
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2')->getFont()->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(40);
        $spreadsheet->getActiveSheet()->getStyle('A3:E3')
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A3:E3')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('A3:E3')
                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $spreadsheet->getActiveSheet()->getStyle('A3:E3')
                    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        
        $seq = 1 + $this->uri->segment(3);
        $kdbarang = '';
        $i = 4;
        foreach($data_all->result() as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, ($kdbarang!=$row->barang_kd) ? $seq : '')
                    ->setCellValue('B'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_kd : '')
                    ->setCellValue('C'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_nm : '')
                    ->setCellValue('D'.$i, ($kdbarang!=$row->barang_kd) ? $row->group_nm : '')
                    ->setCellValue('E'.$i, ($kdbarang!=$row->barang_kd) ? $row->saldo_qty : '')
                    ->getStyle('E')->getNumberFormat()->setFormatCode("_(* #,##0_);_(* \(#,##0\);_(* \"-\"??_);_(@_)")
                ;
            
            //Border style to the cells
            $styleArray = [
                'font' => [
                    'size' => 12
                ]
            ];  
            
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(65);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getStyle('A'.$i.':E'.$i)
                        ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
           $i++;
            if($kdbarang!=$row->barang_kd)
            {
                $seq++;
                $kdbarang = $row->barang_kd;
            }
        }
        
        //  Apply border style to the cells
         $spreadsheet->getActiveSheet()->getStyle(
            'A3:' . 
            $spreadsheet->getActiveSheet()->getHighestColumn() . 
            $spreadsheet->getActiveSheet()->getHighestRow()
        )->applyFromArray($styleArray);

        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Stok Awal '.$data['tgl']);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Set Paper Size
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Stok Awal.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function export_kartu_stok()
    {
        $this->load->library('excel');// me-load library excel
        $tgl                        = $this->session->userdata('tanggal');
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupbarang');
        $data_all                   = $this->barang_model->get('','','',$data['txtcari'],$data['cbogrup']);
        
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()
                    ->setTitle('Kartu Stok')
                    ->setSubject('Kartu Stok')
                    ->setDescription('Kartu Stok')
                    ->setKeywords('Kartu Stok')
                    ->setCategory('Stok');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->session->userdata('outlet_nm'))->mergeCells('A1:G1')
                    ->setCellValue('A2', 'KARTU STOK '.$tgl)->mergeCells('A2:G2')
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Kode')
                    ->setCellValue('C3', 'Nama Barang')
                    ->setCellValue('D3', 'Saldo Awal')
                    ->setCellValue('E3', 'Masuk')
                    ->setCellValue('F3', 'Keluar')
                    ->setCellValue('G3', 'Saldo')
                    ->getStyle('A1')->getFont()->setSize(16)->setBold(true)
        ;
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2')->getFont()->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(40);
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('A3:G3')
                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')
                    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        
        $seq = 1 + $this->uri->segment(3);
        $kdbarang = '';
        $i = 4;
        foreach($data_all->result() as $row) {
            $saldo          = $this->barang_saldo_model->saldo_hari_ini($row->barang_kd);
            $penyesuaianpositif = ($saldo[0]['penyesuaian']>0) ? $saldo[0]['penyesuaian'] : 0;
            $penyesuaiannegatif = ($saldo[0]['penyesuaian']<0) ? $saldo[0]['penyesuaian'] : 0;
        
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, ($kdbarang!=$row->barang_kd) ? $seq : '')
                    ->setCellValue('B'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_kd : '')
                    ->setCellValue('C'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_nm : '')
                    ->setCellValue('D'.$i, ($kdbarang!=$row->barang_kd) ? number_format($saldo[0]['saldo_awal'],0,',','.') : '')
                    ->setCellValue('E'.$i, ($kdbarang!=$row->barang_kd) ? number_format($saldo[0]['pembelian']+$saldo[0]['mutasi_masuk']+$saldo[0]['tukar_masuk']+$penyesuaianpositif,0,',','.') : 0)
                    ->setCellValue('F'.$i, ($kdbarang!=$row->barang_kd) ? number_format($saldo[0]['mutasi_keluar']+$saldo[0]['tukar_keluar']+$penyesuaiannegatif+$saldo[0]['penjualan'],0,',','.') : '')
                    ->setCellValue('G'.$i, ($kdbarang!=$row->barang_kd) ? number_format(($saldo[0]['saldo_qty']),0,',','.') : '')
                    ->getStyle('D:G')->getNumberFormat()->setFormatCode("_(* #,##0_);_(* \(#,##0\);_(* \"-\"??_);_(@_)")
                ;
                $spreadsheet->setActiveSheetIndex(0)->getStyle('C')->getNumberFormat()->setFormatCode("_(* ###0_);_(* \(###0\);_(* \"-\"??_);_(@_)");
            
            //Border style to the cells
            $styleArray = [
                'font' => [
                    'size' => 12
                ]
            ];  
            
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(55);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $spreadsheet->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
            $spreadsheet->getActiveSheet()->getStyle('A'.$i.':G'.$i)
                        ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $i++;
            if($kdbarang!=$row->barang_kd)
            {
                $seq++;
                $kdbarang = $row->barang_kd;
            }
        }

        
        //  Apply border style to the cells
         $spreadsheet->getActiveSheet()->getStyle(
            'A3:' . 
            $spreadsheet->getActiveSheet()->getHighestColumn() . 
            $spreadsheet->getActiveSheet()->getHighestRow()
        )->applyFromArray($styleArray);

        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle('Kartu Stok '.$tgl);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Set Paper Size
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Kartu Stok '.$tgl.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }
    
    public function export_penjualan()
    {
        $this->load->library('excel');// me-load library excel
        
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupbarang');
        $data_all                   = $this->history_kasir_model->penjualan_excel($this->session->userdata('tanggal'),$this->session->userdata('shift'),$data['cbogrup'],$data['txtcari'],'','');
        
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()
                    ->setTitle('Laporan Penjualan Per Barang')
                    ->setSubject('Laporan Penjualan Per Barang')
                    ->setDescription('Laporan Penjualan Per Barang')
                    ->setKeywords('Laporan Penjualan Per Barang')
                    ->setCategory('Laporan');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->session->userdata('outlet_nm'))->mergeCells('A1:G1')
                    ->setCellValue('A2', 'LAPORAN PENJUALAN PER BARANG')->mergeCells('A2:G2')
                    ->setCellValue('A3', 'No')
                    ->setCellValue('B3', 'Kode Barang')
                    ->setCellValue('C3', 'Nama Barang')
                    ->setCellValue('D3', 'No. Faktur')
                    ->setCellValue('E3', 'Qty')
                    ->setCellValue('F3', 'Harga')
                    ->setCellValue('G3', 'Jumlah')
                    ->getStyle('A1')->getFont()->setSize(16)->setBold(true)
        ;
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2')->getFont()->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getRowDimension('3')->setRowHeight(40);
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('A3:G3')
                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $spreadsheet->getActiveSheet()->getStyle('A3:G3')
                    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        
        $seq = 1 + $this->uri->segment(3);
        $kdbarang = '';
        $i = 4;
        $startRow = -1;
        $previousKey = '';
        foreach($data_all->result() as $row) {
            if($startRow == -1){
                $startRow = $i;
                $previousKey = $row->barang_kd;
            }
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, ($kdbarang!=$row->barang_kd) ? $seq : '')
                    ->setCellValue('B'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_kd : '')
                    ->setCellValue('C'.$i, ($kdbarang!=$row->barang_kd) ? $row->barang_nm : '')
                    ->setCellValue('D'.$i, ($kdbarang!=$row->no_faktur) ? $row->no_faktur : '')
                    ->setCellValue('E'.$i, $row->qty)
                    ->setCellValue('F'.$i, ($kdbarang!=$row->harga) ? $row->harga : '')
                    ->setCellValue('G'.$i, ($kdbarang!=$row->jmh) ? $row->jmh : '')
                    ->getStyle('E:G')->getNumberFormat()->setFormatCode("_(* #,##0_);_(* \(#,##0\);_(* \"-\"??_);_(@_)")
                ;
            
            //Border style to the cells
            $styleArray = [
                'font' => [
                    'size' => 12
                ]
            ];  
            
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(55);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(13);
            $spreadsheet->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
            $spreadsheet->getActiveSheet()->getStyle('A'.$i.':G'.$i)
                        ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $i++;
            if($kdbarang!=$row->barang_kd)
            {
                $seq++;
                $kdbarang = $row->barang_kd;
            }
        }

        $spreadsheet->getActiveSheet()->getStyle('A'.$i.':G3'.$i)
                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        
        //  Apply border style to the cells
         $spreadsheet->getActiveSheet()->getStyle(
            'A3:' . 
            $spreadsheet->getActiveSheet()->getHighestColumn() . 
            $spreadsheet->getActiveSheet()->getHighestRow()
        )->applyFromArray($styleArray);

        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle(date('d-m-Y H'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Set Paper Size
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Laporan Penjualan.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function export_rekap_penjualan()
    {
        $this->load->library('excel');// me-load library excel
        
        $data['tgldarifilter']      = $this->session->userdata('tgldarifilter');
        $data['tglsampaifilter']    = $this->session->userdata('tglsampaifilter');
        $data['txtcari']            = $this->session->userdata('txtcaribarang');
        $data['cbogrup']            = $this->session->userdata('cbogrupbarang');
        $data_all           = $this->history_kasir_model->rekap_penjualan($data['tgldarifilter'],$data['tglsampaifilter'],$data['txtcari'],$data['cbogrup']);
        
        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()
                    ->setTitle('Rekap Penjualan')
                    ->setSubject('Rekap Penjualan')
                    ->setDescription('Rekap Penjualan Periode '.$data['tgldarifilter'].' - '.$data['tglsampaifilter'])
                    ->setKeywords('Rekap Penjualan Periode '.$data['tgldarifilter'].' - '.$data['tglsampaifilter'])
                    ->setCategory('Laporan');

        // Add some data
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', $this->session->userdata('outlet_nm'))->mergeCells('A1:H1')
                    ->setCellValue('A2', 'REKAP PENJUALAN')->mergeCells('A2:H2')
                    ->setCellValue('A3', 'Periode : '.$this->adntgl->tgl_panjang($data['tgldarifilter'] ).' s/d '.$this->adntgl->tgl_panjang($data['tglsampaifilter']))->mergeCells('A3:H3')
                    ->setCellValue('A4', 'No')
                    ->setCellValue('B4', 'Tanggal')
                    ->setCellValue('C4', 'No. Faktur')
                    ->setCellValue('D4', 'Kode Barang')
                    ->setCellValue('E4', 'Nama Barang')
                    ->setCellValue('F4', 'Qty')
                    ->setCellValue('G4', 'Harga')
                    ->setCellValue('H4', 'Jumlah')
                    ->getStyle('A1')->getFont()->setSize(16)->setBold(true)
        ;
        $spreadsheet->setActiveSheetIndex(0)->getStyle('A2')->getFont()->setSize(14);
        $spreadsheet->getActiveSheet()->getStyle('A4:H4')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getRowDimension('4')->setRowHeight(40);
        $spreadsheet->getActiveSheet()->getStyle('A4:H4')
                    ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $spreadsheet->getActiveSheet()->getStyle('A4:H4')
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('A4:H4')
                    ->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $spreadsheet->getActiveSheet()->getStyle('A4:H4')
                    ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        

        $i=5;
        foreach($data_all->result() as $row) {
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $i-4)
                        ->setCellValue('B'.$i, $row->tgl)
                        ->setCellValue('C'.$i, $row->no_faktur)
                        ->setCellValue('D'.$i, $row->barang_kd)
                        ->setCellValue('E'.$i, $row->barang_nm)
                        ->setCellValue('F'.$i, $row->jumlah)
                        ->setCellValue('G'.$i, $row->harga)
                        ->setCellValue('H'.$i, $row->harga*$row->jumlah)
                        ->getStyle('F:H')->getNumberFormat()->setFormatCode("_(* #,##0_);_(* \(#,##0\);_(* \"-\"??_);_(@_)")
            ;

            //Border style to the cells
            $styleArray = [
                'font' => [
                    'size' => 12
                ]
            ];  
            
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(4);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(49);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(8);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);
            $spreadsheet->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
            $spreadsheet->getActiveSheet()->getStyle('A'.$i.':H'.$i)
                        ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $i++;
        }

        $spreadsheet->getActiveSheet()->getStyle('A'.$i.':H'.$i)
        ->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        $spreadsheet->getActiveSheet()->getRowDimension($i)->setRowHeight(20);
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, 'Jumlah Barang Terjual')
                    ->mergeCells('A'.$i.':E'.$i)
                    ->getStyle('A'.$i)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()
                    ->setCellValue('F'.$i, '=SUBTOTAL(109,F5:F'.$i.')')
                    ->getStyle('F'.$i)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('G'.$i, 'Total')
                    ->getStyle('G'.$i)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()
                    ->setCellValue('H'.$i, '=SUBTOTAL(109,H5:H'.$i.')')
                    ->getStyle('H'.$i)->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('A'.$i.':H'.$i)
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

         //Apply border style to the cells
         $spreadsheet->getActiveSheet()->getStyle(
            'A4:' . 
            $spreadsheet->getActiveSheet()->getHighestColumn() . 
            $spreadsheet->getActiveSheet()->getHighestRow()
        )->applyFromArray($styleArray);

        // Rename worksheet
        $spreadsheet->getActiveSheet()->setTitle(date('d-m-Y H'));

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Set Paper Size
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Rekap Penjualan '.$data['tgldarifilter'].' - '.$data['tglsampaifilter'].'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}