<?php

class Gol_akun_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function ambil_gol_akun()
    {
        return $this->db->get('ac_sys_gol_perk');
    }
}

?>