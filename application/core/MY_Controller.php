<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Extensão de CI_Controller
 *
 * @author Thiago Moura
 * @version 0.1
 */
class MY_Controller extends CI_Controller{
    
    /**
     * Arvore pai - Nome da partição de configurações e funcionalidades do web app.
     * @var string 
     */
    private $_pai;
    private $_caminho_controle;
    protected $_page_index;
    protected $_action;
    
    /**
     * Construtor da classe
     * 
     * @param string $caminho_controle - Caminho dentro da pasta controller até o 
     * arquivo do controle atual.
     */
    public function __construct($caminho_controle,$page_index = '') {
        parent::__construct();
        $this->_caminho_controle = $caminho_controle;
        $this->_page_index = $page_index;
        $this->_action = $this->input->post('action');
        $this->_pai = strstr($caminho_controle,'/',TRUE);//Descobre o nome da arvore pai.
        $this->config->load($this->_pai); //Carrega configurações da arvore pai.

        $this->load->library('controle_acesso');

        $this->twig->addGlobal('app',$this);
        $this->twig->addGlobal('_pai',$this->_pai);
        $this->twig->addGlobal('_url_pagina',$this->_caminho_controle . '/' . $this->_obter_nome_metodo());

        
    }
    
    public function index(){
        
        if($this->input->is_ajax_request()){
            if($this->_action!=NULL){
                switch ($this->_action) {
                    case "insert":
                        $this->_insert($this->input->post('form'));
                        break;
                    case "update":
                        $this->_update($this->input->post('form'));
                        break;
                    case "delet":
                        $this->_delete($this->input->post('form'));
                        break;
                    case "list":
                        $this->_list($this->input->post('form'));
                        break;
                    case "get":
                    default:
                        $this->_get($this->input->post('form'));
                        break;
                }
            }else{
                $json = array(
                    'action' => $this->_action,
                    'message' => array(
                        'type' => MSG_ERROR,
                        'title' => 'Solicitação inválida.',
                        'message' => 'A requisição enviada não é válida ou não está completa.',
                        'closable' => TRUE
                    ),
                    'debug' => var_dump($this->input->post())
                );
                $this->output
                    ->set_status_header(400)
                    ->set_content_type('application/json')
                    ->set_output(json_encode($json));
            }
        }else{
            if($this->_page_index!=''){
                return $this->{$this->_page_index}();
            }else{
                show_error("A página solicitada ainda não foi implementada!",501,"Sem conteúdo");
            }
        }
        
    }
    
    /*
     * Função de insert no banco de dados
     */
    protected function _insert($form){
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => MSG_WARNING,
                'title' => 'Sem Ação',
                'message' => 'A ação requisitada não foi implementada!',
                'closable' => TRUE
            )
        );
        $this->output
            ->set_status_header(501)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
    
    /*
     * Função de update no banco de dados
     */
    protected function _update($form){
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => MSG_WARNING,
                'title' => 'Sem Ação',
                'message' => 'A ação requisitada não foi implementada!',
                'closable' => TRUE
            )
        );
        $this->output
            ->set_status_header(501)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
    
    /*
     * Função para deletar item no banco de dados
     */
    protected function _delete($form){
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => MSG_WARNING,
                'title' => 'Sem Ação',
                'message' => 'A ação requisitada não foi implementada!',
                'closable' => TRUE
            )
        );
        $this->output
            ->set_status_header(501)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
    
    /*
     * Função para listar itens do banco de dados
     */
    protected function _list($form){
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => MSG_WARNING,
                'title' => 'Sem Ação',
                'message' => 'A ação requisitada não foi implementada!',
                'closable' => TRUE
            )
        );
        $this->output
            ->set_status_header(501)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
    
    /*
     * Função para buscar um item no banco de dados
     */
    protected function _get($form){
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => MSG_WARNING,
                'title' => 'Sem Ação',
                'message' => 'A ação requisitada não foi implementada!',
                'closable' => TRUE
            )
        );
        $this->output
            ->set_status_header(501)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
    
    protected function _get_formulario($path, $data = array()){
        $this->load->library('formulario');
        $data = array_merge($data, $this->formulario->parser($path));
        $this->twig->display($this->config->item('theme') . 'forms',$data);
    }

    protected function _obter_caminho_controle(){
        return $this->_caminho_controle;
    }
    
    public function area_restrita(){
        $this->_view('Área Restrita', 'area_restrita');
    }
    
    /**
     * Retorna o nome do método chamado pela requisição HTTP.
     * 
     * @return string Se o método existir ele retorna o nome do método, senão, 
     * retorna uma <code>string</code> vazia.
     */
    private function _obter_nome_metodo(){
        $remove_url_control = substr(uri_string(), strlen($this->_caminho_controle)+1);
        $method_name = stristr($remove_url_control, '/',TRUE);
        if($method_name===FALSE){
            $method_name = stristr($remove_url_control, '.',TRUE);
            if($method_name===FALSE){
                $method_name = $remove_url_control;
            }
        }
        if($method_name==NULL){
            $method_name = 'index';
        }
        if(method_exists(get_class($this), $method_name)){
            return $method_name;
        }else{
            return '';
        }
    }
}
