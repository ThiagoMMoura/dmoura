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
    
    public function __construct() {
        parent::__construct();
        $this->_pai = 'sistema';//strstr(uri_string(),'/'); //Descobre o nome da arvore pai.
        $this->config->load($this->_pai); //Carrega configurações da arvore pai.
    }
    
    /**
     * Função de carregamento de view para o browser.
     * 
     * @param string $page
     */
    public function view($page){
        add_head_title($page);
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
}
