<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Controle de testes
 *
 * @author Thiago Moura
 */
class Teste extends MY_Controller{
    
    public function __construct() {
        parent::__construct('sistema/teste',FALSE);
    }
    
    public function index(){
        $this->_add_data('nome','Thiago');
        $this->_view("Formulário",'formulario',parent::RELATIVO_CONTROLE);
    }
    
    public function formulario(){
        $this->_add_body('formulario',parent::RELATIVO_CONTROLE);
        $this->_view('Formulario');
    }
}
