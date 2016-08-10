<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Controle de login
 *
 * @author Thiago Moura
 */
class Login extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        if($this->_logado()){
            $this->config->load('sistema');
            redirect($this->config->item('home'));
        }
        $this->load->library('form_validation');
    }
    
    public function index(){
        $data = array();
        $this->load->view("sistema/login",$data);
    }
    
    /**
     * Retorna <b>TRUE</b> se o usuário estiver logado, <b>FALSE</b> caso contrário.
     * 
     * @return boolean
     */
    private function _logado() {
        return ($this->session->has_userdata('logado') && $this->session->logado);
    }
}
