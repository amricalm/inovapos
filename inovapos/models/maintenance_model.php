<?php

class Maintenance_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function cek_maintenance()
    {
        $this->db->where('tipe','MAINTENANCE');
        
        return $this->db->get('log_proses');
    }
    function set_maintenance($data)
    {
        return $this->db->insert('log_proses',$data);
    }
    function cek_engine($table)
    {
        $CI     =& get_instance();
        $this->db->select('ENGINE');
        $this->db->from('information_schema.TABLES');
        $this->db->where('TABLE_SCHEMA',$CI->db->database);
        $this->db->where('TABLE_NAME',$table);
        
        return $this->db->get();
    }
    function set_engine($table,$typetable)
    {
        $sql            = ' ALTER TABLE '.$table.' ENGINE = '.$typetable;
        return $this->db->query($sql);
    }
}

?>