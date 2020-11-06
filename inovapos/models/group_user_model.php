<?php

class Group_user_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function get($kd)
    {
        if($kd!='')
        {
            $this->db->where('group_kd',$kd);
        }
        return $this->db->get('mgroup');
    }
}

?>