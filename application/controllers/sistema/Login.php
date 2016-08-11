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
    
    public function index($erro = ''){
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
    
    public function entrar(){
        $dados = array();
        if(is_numeric($this->input->post('usuario'))){
            $this->form_validation->set_rules('usuario', 'Usuário', 'trim|required');
            $dados['id'] = $this->input->post('usuario');
        }else{
            $this->form_validation->set_rules('usuario', 'Usuário', 'trim|required|valid_email');
            $dados['email'] = $this->input->post('usuario');
        }
        $this->form_validation->set_rules('senha', 'Senha', 'trim|required|md5');
        $dados['senha'] = $this->input->post('senha');

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $this->load->model('pessoa_model');
            
            if($this->pessoa_model->valida_usuario($dados)){//Validação de dados do usuário no banco
                $userdata = $this->pessoa_model->registro(); //Armazena dados do usuário na sessão
                $userdata['logado'] = TRUE;

                $this->session->set_userdata($userdata);
                redirect($this->config->item('home'));
            }else{
                //$this->session->set_flashdata('alerta', 'error_login_incorreto');
                $this->index();
            }
        }
    }
}
