<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Controle de testes
 *
 * @author Thiago Moura
 */
class Teste extends MY_Controller{
    public function index(){
        $this->add_data('nome','Thiago');
        $this->add_data('text',$this->config->item('teste-config'));
        $this->view('welcome_message','teste');
    }
}
