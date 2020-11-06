<?php

class Promosi_model extends CI_Model
{
    function get()
    {
        return $this->db->get('im_tpromosi');
    }
    function simpan($data)
    {
        return $this->db->insert('im_tpromosi',$data);
    }
    function update($data)
    {
        return $this->db->update('im_tpromosi',$data);
    }
}

?>