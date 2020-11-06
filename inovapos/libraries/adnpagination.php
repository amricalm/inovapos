<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Adnpagination
{
    public function config($baseurl,$totalrows,$perpage,$urisegment)
    {
        $CI                         = & get_instance();
        $CI->load->model('app_model');
        $general                    = $CI->app_model->general();
        $config['full_tag_open']    = '<div class="pagination">';
        $config['full_tag_close']   = '</div>';
        $config['first_link']       = '<img src="'.$general['base_img'].'/arrow-stop-180-small.gif" height="9" width="12" alt="Pertama" /> Pertama';
        /*
        $config['first_tag_open']   = '<span>';
        $config['first_tag_close']  = '</span>';
        */
        $config['prev_link']        = '<img src="'.$general['base_img'].'/arrow-180-small.gif" height="9" width="12" alt="Sebelumnya" /> Sebelumnya';
        /*
        $config['prev_tag_open']    = '<span>';
        $config['prev_tag_close']   = '</span>';
        */
        $config['next_link']        = 'Selanjutnya <img src="'.$general['base_img'].'/arrow-000-small.gif" height="9" width="12" alt="Selanjutnya" />';
        /*
        $config['next_tag_open']    = '<span>';
        $config['next_tag_close']   = '</span>';
        */
        $config['last_link']        = 'Terakhir <img src="'.$general['base_img'].'/arrow-stop-000-small.gif" height="9" width="12" alt="Terakhir" />';
        /*
        $config['last_tag_open']    = '<span>';
        $config['last_tag_close']   = '</span>';
        $config['num_tag_open']     = '<div class="numbers">';
        $config['num_tag_close']    = '</div>';
        */
        $config['num_links']        = 5;
        $config['base_url']         = $baseurl;
        $config['total_rows']       = $totalrows;
        $config['per_page']         = $perpage; 
        $config['uri_segment']      = $urisegment;
        
        return $config;
    }
}

?>