<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Excel {
    public function __construct() {
        require_once APPPATH . 'third_party/excel/vendor/autoload.php';
    }
}
?>