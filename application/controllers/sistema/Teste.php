<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Controle de testes
 *
 * @author Thiago Moura
 */
class Teste extends MY_Controller{
    
    public function __construct() {
        parent::__construct('sistema/teste');
    }
    
    public function index(){
        $this->add_data('nome','Thiago');
        $this->view('welcome_message','teste');
    }
    
    public function formulario(){
        
    }
}
