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
        $this->load->model('pessoa_fisica_model');
        
        $this->form_validation->set_rules('cpf', 'CPF',array(
                'trim','required','numeric','exact_length[11]','is_unique[pessoa_fisica.cpf]',
                array($this->pessoa_fisica_model,'cpf_valido')
            ),
            array('cpf_valido' => 'Esté não é um CPF válido.')
        );
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|alpha|min_length[5]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('sexo', 'Sexo', 'trim|required|in_list[Masculino,Feminino]');
        $this->form_validation->set_rules('cep', 'CEP', 'trim|required|in_list[Masculino,Feminino]');

        if ($this->form_validation->run() == FALSE) {
            $this->cadastro();
        } else {
            
        }
    }
}
