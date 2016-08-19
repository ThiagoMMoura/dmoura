<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Cliente
 *
 * @author Thiago Moura
 */
class Fisica extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/pessoa/fisica',TRUE);
    }
    
    public function index(){
        $this->cadastro();
    }
    
    public function cadastro(){
        $this->load->model('estado_model');
        $this->_add_data('estados',$this->estado_model->obter_uf_estado());
        $this->_view("Cadastro Pessoa Física",'cadastro',parent::RELATIVO_CONTROLE);
    }
    
    public function salvar(){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('cpf', 'CPF', 'trim|required|exact_length[11]|numeric');
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|alpha|min_length[5]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('sexo', 'Sexo', 'required|in_list[Masculino,Feminino]');

        if ($this->form_validation->run() == FALSE) {
            $this->cadastro();
        } else {
            
        }
    }
}
