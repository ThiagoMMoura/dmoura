<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Extensão de CI_Controller
 *
 * @author Thiago Moura
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
    public function __construct($caminho_controle) {
        parent::__construct();
        $this->_caminho_controle = $caminho_controle;
        $this->_pai = strstr($caminho_controle,'/',TRUE);//Descobre o nome da arvore pai.
        $this->config->load($this->_pai); //Carrega configurações da arvore pai.
    }
    
    /**
     * Função de carregamento de view para o browser.
     * 
     * @param string $titulo
     */
    public function view($titulo = '',$body = ''){
        if($titulo!=NULL){
            add_head_title($titulo);
        }
        $this->add_body($body);
        $this->add_data('imprimir_body',$this->_body);
        $this->load->view($this->config->item('template-html'),$this->_data_view); //Carrega o template base do web app
    }
    
    protected function add_data($data,$value = ''){
        if(is_array($data)){
            $this->_data_view = array_merge($this->_data_view,$data);
        }else{
            $this->_data_view[$data] = $value;
        }
    }
    
    protected function remove_data($key){
        unset($this->_data_view[$key]);
    }
    
    protected function add_body($arquivo,$relativo = self::RELATIVO_PAI){
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
    
    protected function imprimir_body(){
        return $this->_body;
    }
    
    protected function obter_caminho_controle(){
        return $this->_caminho_controle;
    }
//    protected function carregar_configuracoes_pagina($page = ''){
//        if($page == NULL){
//            $page = uri_string();
//        }
//        $segmentos = explode('/',$page);
//        $segmentos[] = 'index';
//        $pastas = directory_map('./application/config/configuracoes_pagina/');
//        $caminho = '';
//        foreach($segmentos as $segmento){
//            if($segmento!=NULL){
//                if(array_key_exists($segmento.'\\', $pastas)){
//                    $pastas = $pastas[$segmento.'\\'];
//                    $caminho .= $segmento.'/';
//                }else{
//                    if(array_search($segmento.'.php', $pastas)!==FALSE){
//                        $caminho .= $segmento.'.php';
//                        break;
//                    }else{
//                        return FALSE;
//                    }
//                }
//            }else{
//                $caminho .= 'index.php';
//                break;
//            }
//        }
//
//        return $this->config->load('configuracoes_pagina/'. $caminho,FALSE,TRUE);
//    }
}
