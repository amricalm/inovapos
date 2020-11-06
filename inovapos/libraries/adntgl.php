<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Adntgl
{
    /**
     * parameter $bln = 01,02,...
     * jika kosong, data yang muncul, daftar bulan
    */
    public function bln($bln='')
    {
        $bulan                      = array(
                                    '01'   => 'Januari',
                                    '02'   => 'Februari',
                                    '03'   => 'Maret',
                                    '04'   => 'April',
                                    '05'   => 'Mei',
                                    '06'   => 'Juni',
                                    '07'   => 'Juli',
                                    '08'   => 'Agustus',
                                    '09'   => 'September',
                                    '10'  => 'Oktober',
                                    '11'  => 'November',
                                    '12'  => 'Desember'
                                    );
        if($bln!='')
        {
            return $bulan[$bln];
        }
        else
        {
            return $bulan;   
        }
    }
    /**
     * parameter $tgl berarti YYYY-MM-DD
    */
    function tgl_panjang($tgl)
    {
        $e_tgl                      = explode('-',$tgl);
        $tgl                        = $e_tgl[2].'-'.$this->bln($e_tgl[1]).'-'.$e_tgl[0];
        return $tgl;
    }
    /**
     * parameter $tgl berarti YYYY-MM-DD
    */
    function convert_to_date_mysql($tgl)
    {
        $e_tgl                      = explode('-',$tgl);
        $tgl                        = $e_tgl[2].'-'.$e_tgl[1].'-'.$e_tgl[0];
        return $tgl;
    }
}

?>