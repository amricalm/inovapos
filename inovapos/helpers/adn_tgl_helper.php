<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------
/**
* Convert String Tanggal (dd/mm/yyyy) to  MySQL's DATE (YYYY-MM-DD)
*
* Returns the MySQL's DATE (YYYY-MM-DD)
*
* @todo ...
* @author Yan Sofyan (yansofyan@gmail.com)
* @access    public
* @return    string
*/

function tgl_to_tglmysql($tgl="")
{
    $timestamp = strtotime($tgl);
    return date('Y-m-d', $timestamp);
}

function tgl_minus_hari($tgl="",$hari=0)
{
    $DETIK_PER_MENIT = 60;
    $MENIT_PER_JAM = 60;
    $JAM_PER_HARI = 24;

    $day_interval = $hari;

    $timestamp_awal = strtotime($tgl);
    $interval = $day_interval * $DETIK_PER_MENIT * $MENIT_PER_JAM * $JAM_PER_HARI  ;
    $timestamp_baru = $timestamp_awal - $interval;
    
    return date('Y-m-d', $timestamp_baru);

}

function test()
{
    return 'test';
}

function str_to_tglmysql($str="")
{
    $timestamp = strtotime(substr($str,0,4).'-'.substr($str,4,2).'-'.substr($str,6,2));
    return date('Y-m-d', $timestamp);
}

function str_part($str="",$start=0,$length=0)
{
    $panjang    = strlen($str);
    if ($panjang>$length){
        $str = substr($str, $start,$length) . "...";
    }
    return $str;
}

function adn_ctgl($tgl="")
{
    if($tgl!='')
    {
        $tgl = explode('-',$tgl);
        $tgl = $tgl[2].'-'.$tgl[1].'-'.$tgl[0];
    }
    else
    {
        $tgl = '0000-00-00';
    }
    return $tgl;
}

function adn_ctgl_dbulan($tgl)
{
    if($tgl!='')
    {
        $tgls = explode('-',$tgl);
        $tgl = $tgls[2].' - '.bulan_id($tgls[1]).' - '.$tgls[0];
    }
    else
    {
        $tgl = '-';
    }
    return $tgl;
}
function adn_ctgl_dbulan_sertifikat($tgl)
{
    if($tgl!='')
    {
        $tgls = explode('-',$tgl);
        $tgl = $tgls[2].' '.bulan_id($tgls[1]).' '.$tgls[0];
    }
    else
    {
        $tgl = '-';
    }
    return $tgl;
}
function adn_rctgl($tgl="")
{
    $tgl = explode('-',$tgl);
    $tgl = $tgl[2].'-'.$tgl[1].'-'.$tgl[0];

    return $tgl;
}
function bulan_id($kd='')
{
    $bulan_id   = ''; 
    $namabulan  = array(
                  1 => 'Januari',
                  2 =>   'Februari',
                  3 =>   'Maret',
                  4 =>   'April',
                  5 =>   'Mei',
                  6 =>   'Juni',
                  7 =>   'Juli',
                  8 =>   'Agustus',
                  9 =>   'September',
                  10 =>   'Oktober',
                  11 =>   'November',
                  12 =>   'Desember'
                );
    if($kd!='')
    {
        for($i=1;$i<=count($namabulan);$i++)
        {
            if($kd==$i)
            {
                $bulan_id = $namabulan[$i];
                break;
            }
        }
    }
    return $bulan_id;
}
?>
