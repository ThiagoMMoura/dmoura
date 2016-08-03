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
    public function __construct($caminho_controle,$login_obrigatorio = FALSE) {
        parent::__construct();
        $this->_caminho_controle = $caminho_controle;
        $this->_pai = strstr($caminho_controle,'/',TRUE);//Descobre o nome da arvore pai.
        $this->config->load($this->_pai); //Carrega configurações da arvore pai.
        if($login_obrigatorio){
            $this->area_restrita();
            $this->output->_display();
            exit;
        }
    }
    
    /**
     * Função de carregamento de view para o browser.
     * 
     * @param string $titulo
     */
    protected function _view($titulo = '',$body = '',$relativo = self::RELATIVO_PAI){
        if($titulo!=NULL){
            add_head_title($titulo);
        }
        $this->_add_data('titulo',$titulo);
        $this->_add_body($body,$relativo);
        $this->_add_data('imprimir_body',$this->_body);
        $this->load->view($this->config->item('template-html'),$this->_data_view); //Carrega o template base do web app
    }
    
    protected function _add_data($data,$value = ''){
        if(is_array($data)){
            $this->_data_view = array_merge($this->_data_view,$data);
        }else{
            $this->_data_view[$data] = $value;
        }
    }
    
    protected function _remove_data($key){
        unset($this->_data_view[$key]);
    }
    
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
    
    protected function _imprimir_body(){
        return $this->_body;
    }
    
    protected function _obter_caminho_controle(){
        return $this->_caminho_controle;
    }
    
    /**
     * Retorna <b>TRUE</b> se o usuário estiver logado, <b>FALSE</b> caso contrário.
     * 
     * @return boolean
     */
    protected function _logado() {
        return ($this->session->has_userdata('logado') && $this->session->logado);
    }
    
    protected function _tem_permissao(){
        $this->config->item('permissoes');
        return $this->logado();
    }
    
    public function area_restrita(){
        $this->_view('Área Restrita', 'area_restrita');
    }
}
