<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_var_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get($sys_col)
    {
        $hasil = '';

        $this->db->where('sys_col', $sys_col);
        $this->db->select('sys_val');
        $query = $this->db->get('sys_var');

        if($query->num_rows()>0)
        {
            $hasil = $query->row()->sys_val;
        }
        return $hasil;
    }

}
?>