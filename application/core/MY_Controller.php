<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Extensão de CI_Controller
 *
 * @author Thiago Moura
 * @version 1.2
 */
class MY_Controller extends CI_Controller{
    
    private $_data_view = array();
    /**
     * Arvore pai - Nome da partição de configurações e funcionalidades do web app.
     * @var string 
     */
    private $_pai;
    private $_body = '';
    private $_caminho_controle;
    
    const RELATIVO_SISTEMA = 0;
    const RELATIVO_PAI = 1;
    const RELATIVO_CONTROLE = 2;
    /**
     * Construtor da classe
     * 
     * @param string $caminho_controle - Caminho dentro da pasta controller até o 
     * arquivo do controle atual.
     */
    public function __construct($caminho_controle,$area_restrita = FALSE,$resenha = TRUE) {
        parent::__construct();
        $this->_caminho_controle = $caminho_controle;
        
        $this->_pai = strstr($caminho_controle,'/',TRUE);//Descobre o nome da arvore pai.
        $this->config->load($this->_pai); //Carrega configurações da arvore pai.
        
        $atributos['pai'] = $this->_pai;
        $atributos['controle'] = $this->_caminho_controle;
        $atributos['metodo'] = $this->_obter_nome_metodo();
        $atributos['area_restrita'] = $area_restrita;
	$atributos['resenha'] = $resenha;
        $this->load->library('controle_acesso',$atributos);
        if(!$this->controle_acesso->pai()){
            redirect($this->_pai . '/autenticacao/login/error_permissao/' . ALERTA_ERRO);
        }
        if(!$this->controle_acesso->controle() || !$this->controle_acesso->metodo()){
            $this->area_restrita();
            $this->output->_display();
            exit;
        }
        if($this->controle_acesso->logado()){
            $this->twig->addGlobal('app',$this);
            $this->twig->addGlobal('_pai',$this->_pai);
            $this->twig->addGlobal('_url_pagina',$this->_caminho_controle . '/' . $atributos['metodo']);
        }
        
    }
    
    /**
     * Função de carregamento de view para o browser.
     * 
     * @param string $titulo
     * @deprecated since version 1.0
     */
    protected function _view($titulo = '',$body = '',$relativo = self::RELATIVO_PAI){
        if($titulo!=NULL){
            add_head_title($titulo);
        }
        $this->_add_data('_titulo',$titulo);
        $this->_add_data('_pai',$this->_pai);
        if($this->controle_acesso->logado()){
            $usuario['nome'] = $this->session->nome;
            $usuario['cargo'] = '';
            $this->_add_data('_usuario',$usuario);
        }
        $this->_add_body($body,$relativo);
        $this->_add_data('_imprimir_body',$this->_body);
        $this->load->view($this->config->item('template-html'),$this->_data_view); //Carrega o template base do web app
    }
    
    /**
     * 
     * @param type $data
     * @param type $value
     * @deprecated since version 1.0
     */
    protected function _add_data($data,$value = ''){
        if(is_array($data)){
            $this->_data_view = array_merge($this->_data_view,$data);
        }else{
            $this->_data_view[$data] = $value;
        }
    }
    
    /**
     * 
     * @param type $key
     * @deprecated since version 1.0
     */
    protected function _remove_data($key){
        unset($this->_data_view[$key]);
    }
    
    /**
     * 
     * @param type $arquivo
     * @param type $relativo
     * @deprecated since version 1.0
     */
    protected function _add_body($arquivo,$relativo = self::RELATIVO_PAI){
        if($arquivo!=NULL && !empty($arquivo)){
            if(!is_array($arquivo)){
                $arquivo = array($arquivo);
            }
            foreach($arquivo as $arq){
                switch($relativo){
                    case self::RELATIVO_CONTROLE:{
                        $this->_body .= $this->load->view($this->_caminho_controle . '/' . $arq,$this->_data_view,TRUE);
                        break;
                    }case self::RELATIVO_SISTEMA:{
                        $this->_body .= $this->load->view($arq,$this->_data_view,TRUE);
                        break;
                    }default:{
                        $this->_body .= $this->load->view($this->_pai . '/' .$arq,$this->_data_view,TRUE);
                    }
                }
            }
        }
    }
    
    /**
     * 
     * @return type
     * @deprecated since version 1.0
     */
    protected function _imprimir_body(){
        return $this->_body;
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
