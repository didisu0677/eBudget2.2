<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends MY_Controller {
    function __construct(){
        parent::__construct();
    }

    function file($encode_file='') {
    	$file = decode_string($encode_file);
    	$this->load->helper('download');
    	if(file_exists(FCPATH . $file)) {
    		force_download(FCPATH . $file, null);
    	} else render('File tidak ditemukan');
    }

    function ip(){
        echo $this->input->ip_address();
    }
}
