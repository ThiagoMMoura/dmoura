<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Controle de testes
 *
 * @author Thiago Moura
 */
class Teste extends MY_Controller{
    
    public function __construct() {
        parent::__construct('sistema/teste',TRUE);
    }
    
    public function index(){
        $this->_add_data('nome',$this->controle_acesso->controle('sistema'));
        $this->_view("Teste index",'teste',parent::RELATIVO_CONTROLE);
    }
    
    public function formulario(){
        $this->_add_body('formulario',parent::RELATIVO_CONTROLE);
        $this->_view('Formulario');
    }
    
    public function outro(){
//        $this->load->library('xmlrpc');
//
//        $this->xmlrpc->server('http://viacep.com.br', 80);
//        $this->xmlrpc->method('ws');
//
//        $request = array("94085150", 'json');
//        $this->xmlrpc->request($request);
//
//        if ( ! $this->xmlrpc->send_request())
//        {
//                echo $this->xmlrpc->display_error();
//        }
//        $this->_add_data('nome',print_r($this->xmlrpc->display_response(),TRUE));
        $this->_view("Teste outro",'teste',parent::RELATIVO_CONTROLE);
    }
    
    public function senha($senha){
        $senha = md5(trim($senha));
        $this->_add_data('nome', $senha);
        $this->_view("Teste outro",'teste',parent::RELATIVO_CONTROLE);
    }
}
