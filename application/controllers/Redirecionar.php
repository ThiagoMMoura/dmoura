<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Description of Redirecionar
 *
 * @author Thiago Moura
 */
class Redirecionar extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->config->load('redirecionar_config');
    }
    
    public function index(){
        $url = uri_string();
        if($url == NULL){
            $url = 'index';
        }
        $this->_redirect($url);
    }
    
    private function _redirect($url){
        $urls = $this->config->item('urls');
        if(array_key_exists($url, $urls)){
            redirect($urls[$url]);
        }
        show_404();
    }
}
