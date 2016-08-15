<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Controle de login
 *
 * @author Thiago Moura
 */
class Autenticacao extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->config->load('sistema');
        if($this->_logado()){
            redirect($this->config->item('home'));
        }
        $this->load->library('form_validation');
    }
    
    public function index(){
        $this->login();
    }
    
    /**
     * Retorna <b>TRUE</b> se o usuário estiver logado, <b>FALSE</b> caso contrário.
     * 
     * @return boolean
     */
    private function _logado() {
        return ($this->session->has_userdata('logado') && $this->session->logado);
    }
    
    public function login($alerta = '',$tipo = ALERTA_INFO){
        $data = array();
        if($alerta != NULL){
            $data['alerta']['tipo'] = $tipo;
            $data['alerta']['titulo'] = $this->input->post('titulo');
            $data['alerta']['mensagem'] = $this->lang->line($alerta);
            $data['alerta']['fechavel'] = TRUE;
        }
        $this->load->view("sistema/login",$data);
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

        if ($this->form_validation->run() == FALSE) {
            $this->login('error_login',ALERTA_ERRO);
        } else {
            $this->load->model('pessoa_model');
            $dados['senha'] = $this->input->post('senha');
            if($this->pessoa_model->valida_usuario($dados)){//Validação de dados do usuário no banco
                $userdata = $this->pessoa_model->registro(); //Armazena dados do usuário na sessão
                $userdata['logado'] = TRUE;

                $this->session->set_userdata($userdata);
                redirect($this->config->item('home'));
            }else{
                //redirect('sistema/login/error_login_incorreto/' . ALERTA_ERRO);
                $this->login('error_login_incorreto',ALERTA_ERRO);
            }
        }
    }
    
    public function sair(){
        $userdata = $this->pessoa_model->obter_nome_colunas();
        
        $this->session->set_userdata('logado', FALSE);
        $this->session->unset_userdata('logado');
        
        $this->session->unset_userdata($userdata);
        redirect('autenticacao/login');
    }
}
