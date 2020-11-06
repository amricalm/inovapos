<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Curl Class
 *
 * Work with remote servers via cURL much easier than using the native PHP bindings.
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @author        	Yan Sofyan
 * @link			http://andhana.com
 */
class AdnResponse {
    
        var $IsSuccess;
        var $Message;
        var $ID;
        
        function __construct($isSuccess=false, $message="")
        {
            $this->IsSuccess = $isSuccess;
            $this->Message = $message;
        }
        function getWithID($isSuccess, $message,$id)
        {
            $this->IsSuccess = $isSuccess;
            $this->Message = $message;
            $this->ID = $id;
        }
    
}


/* End of file AdnFungsi.php */
/* Location: ./application/libraries/AdnFungsi.php */