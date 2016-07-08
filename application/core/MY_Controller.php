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
    
    public function __construct() {
        parent::__construct();
        $this->_pai = strstr(uri_string(),'/',TRUE);//Descobre o nome da arvore pai.
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
    
    protected function add_body($arquivo,$considera_pai = TRUE){
        if($arquivo!=NULL && !empty($arquivo)){
            if(!is_array($arquivo)){
                $arquivo = array($arquivo);
            }
            foreach($arquivo as $arq){
                if($considera_pai){
                    $this->_body .= $this->load->view($this->_pai . '/' . $arq,$this->_data_view,TRUE);
                }else{
                    $this->_body .= $this->load->view($arq,$this->_data_view,TRUE);
                }
            }
        }
    }
    
    protected function imprimir_body(){
        return $this->_body;
    }
}
