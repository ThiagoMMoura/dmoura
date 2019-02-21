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
        $this->load->library('form_validation');
        $this->load->library('twig');
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
    
    public function login($alerta = '',$tipo = MSG_INFO){
        if($alerta == 'error_permissao'){
            $this->_logoff();
        }
        if($this->_logado()){
            redirect($this->config->item('home'));
        }
        $data = [
            'titulo' => "Login D'Moura"
        ];
        if($alerta != NULL){
            $data['message']['type'] = $tipo;
            $data['message']['title'] = $this->input->post('titulo');
            $data['message']['message'] = $this->lang->line($alerta);
            $data['message']['closable'] = TRUE;
        }
        $this->twig->display($this->config->item('theme') . 'login',$data);
    }
    
    public function entrar(){
        $dados = array();
//        if(is_numeric($this->input->post('usuario'))){
//            $this->form_validation->set_rules('usuario', 'Usuário', 'trim|required');
//            $dados['id'] = $this->input->post('usuario');
//        }else{
        if(strpos($this->input->post('usuario'),'@')){
            $this->form_validation->set_rules('usuario', 'Usuário', 'trim|required|valid_email');
            $dados['email'] = $this->input->post('usuario');
        }else{
            $this->form_validation->set_rules('usuario', 'Usuário', 'trim|required');
            $dados['alias'] = $this->input->post('usuario');
        }
//        }
        $this->form_validation->set_rules('senha', 'Senha', 'trim|required|' . $this->config->item('hash-senha'));

        if ($this->form_validation->run() == FALSE) {
            $this->login('error_login',MSG_ERROR);
        } else {
            $this->load->model('user_model');
            $dados['nivel'] = NIVEL_OPERARIO;
            $dados['senha'] = $this->input->post('senha');
            if($this->user_model->valida_usuario($dados)){//Validação de dados do usuário no banco
                $userdata = $this->user_model->registro(); //Armazena dados do usuário na sessão
                $userdata['logado'] = TRUE;

                $this->session->set_userdata($userdata);
                redirect($this->config->item('home'));
            }else{
                //redirect('sistema/login/error_login_incorreto/' . ALERTA_ERRO);
                $this->login('error_login_incorreto',MSG_ERROR);
            }
        }
    }
    
    public function sair(){
        $this->_logoff();
        redirect('sistema/autenticacao/login');
    }
    
    private function _logoff(){
        if($this->_logado()){
            $this->load->model('user_model');
            $userdata = $this->user_model->obter_nome_colunas();

            $this->session->set_userdata('logado', FALSE);
            $this->session->unset_userdata('logado');

            $this->session->unset_userdata($userdata);
        }
    }
}
