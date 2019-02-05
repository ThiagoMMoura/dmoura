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
    private $_permissions_id;
    protected $_page_index;
    protected $_action;
    protected $_title;
    
    /**
     * Construtor da classe
     * 
     * @param string $caminho_controle - Caminho dentro da pasta controller até o 
     * arquivo do controle atual.
     */
    public function __construct($caminho_controle,$title,$page_index = '') {
        parent::__construct();
        $this->_caminho_controle = $caminho_controle;
        $this->_page_index = $page_index;
        $this->_action = $this->input->post('action');
        $this->_title = $title;
        $this->_pai = strstr($caminho_controle,'/',TRUE);//Descobre o nome da arvore pai.
        $this->_permissions_id = [
            'insert'=>'',
            'update'=>'',
            'delet'=>'',
            'list'=>'',
            'query'=>'',
            'get'=>''
        ];
        $this->config->load($this->_pai); //Carrega configurações da arvore pai.

        if (!$this->input->is_ajax_request()) {
            register_shutdown_function(function () {
                echo '<a style="position:absolute;bottom:25px;right:15px;z-index:99999;padding:10px;background-color:#e9e9e9b3;" onclick="this.style.display = \'none\';">Pico de uso da memória: ', memory_get_peak_usage() / 1024, 'kb</a>', PHP_EOL;
            });
        }
        //central permitting processing
        $this->load->library('controle_acesso',NULL,'cpp');
        $this->load->library('vision_control',NULL,'vc');
    }
    
    public function index(){
        
        if($this->input->is_ajax_request()){
            log_message('DEBUG','Requisição do tipo Ajax.');
            if($this->_action!=NULL){
                // Verificação de permissão de execução
                $this->_allowed_function($this->_permissions_id[$this->_action]);
                
                switch ($this->_action) {
                    case "insert":
                        $this->_insert($this->input->post('form'));
                        break;
                    case "update":
                        $this->_update($this->input->post('form'));
                        break;
                    case "delet":
                        $this->_delet($this->input->post('form'));
                        break;
                    case "list":
                        $this->_list($this->input->post('form'));
                        break;
                    case "query":
                        $this->_query($this->input->post('form'));
                        break;
                    case "get":
                    default:
                        $this->_get($this->input->post('form'));
                        break;
                }
            }else{
                $json = array(
                    'message' => array(
                        'type' => MSG_ERROR,
                        'title' => 'Solicitação inválida.',
                        'message' => 'A requisição enviada não é válida ou não está completa.',
                        'closable' => TRUE
                    ),
                    'debug' => $this->input->post()
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
    protected function _delet($dataform){
        $this->load->model($dataform['model']);
        $form = [];
        $message = 'Não foi possível excluir!';
        $type = MSG_INFO;
        if($this->{$dataform['model']}->deletar($dataform['id'])){
            $form = $this->{$dataform['model']}->registros();
            $message = "Exclusão realizada com sucesso!";
            $type = MSG_SUCCESS;
        }
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Excluir ' . $this->_title,
                'message' => $message,
                'closable' => TRUE
            ),
            'form' => $form
        );
        $this->output
            ->set_status_header($type==MSG_SUCCESS?200:401)
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
                'title' => $this->_title || 'Sem Ação',
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
        
        $valor = $form['id'];
        $type = MSG_ERROR;
        $message = 'Dados inválidos!';
        $status_header = 404;
        $data_form = array();
        
        if(key_exists('model', $form) && key_exists('get', $form)){
            $this->load->model($form['model']);
        
            if($valor!=NULL){
                $selecionar = $form['get'];
                $selecionar['where']['id'] = $valor;
                if($this->{$form['model']}->selecionar($selecionar) && $this->{$form['model']}->num_registros()===1){
                    $data_form = $this->{$form['model']}->registro();

                    $type = MSG_SUCCESS;
                    $message = 'Registro encontrado com sucesso!';
                    $status_header = 200;
                }else{
                    $message = 'Nenhum registro encontrado!';
                }
            }
        }
        
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Editar ' . $this->_title,
                'message' => $message,
                'closable' => TRUE
            ),
            'form' => $data_form
        );
        $this->output
            ->set_status_header($status_header)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
    
    /*
     * Função que retorna uma query direto do banco de dados.
     */
    protected function _query($dataform){
        $this->load->model($dataform['model']);
        $form = [];
        $message = 'Nenhum registro encontrado!';
        $type = MSG_INFO;
        $sql['select'] = $dataform['select'];
        // Join
        if(key_exists('join', $dataform)){
            $sql['join'] = $dataform['join'];
        }
        // Order By
        if(key_exists('orderby', $dataform)){
            $sql['order_by'] = $dataform['orderby'];
        }
        // Like
        if(key_exists('like', $dataform)){
            $sql['like'] = $dataform['like'];
        }
        // Executa Query
        if($this->{$dataform['model']}->selecionar($sql) && $this->{$dataform['model']}->num_registros()>0){
            $form = $this->{$dataform['model']}->registros();
            $message = "Consulta realizada com sucesso!";
            $type = MSG_SUCCESS;
        }
        // Envia resposta.
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Consulta ' . $this->_title,
                'message' => $message,
                'closable' => TRUE
            ),
            'form' => $form,
            'sql' => $this->{$dataform['model']}->obter_ultimo_sql()
        );
        $this->output
            ->set_status_header($type==MSG_SUCCESS?200:401)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
    
    protected function display($path,$data = [],$without_theme_path = FALSE){
        $this->load->library('twig');
        $this->twig->addGlobal('app',$this);
        $this->twig->addGlobal('_pai',$this->_pai);
        $this->twig->addGlobal('_url_pagina',$this->_caminho_controle . '/' . $this->_obter_nome_metodo());
        $this->load->library('menu');
        $this->twig->addGlobal('sv_main_menu',$this->menu->parser('sistema/main_menu')['sv_menu']);
        $this->twig->display(($without_theme_path?'':$this->config->item('theme')) . $path,$data);
    }
    
    protected function _get_view($sPath, $oData = []){
        //$this->vc->xmlParser2($sPath);
        //$this->load->library($this->config->item('theme') . 'theme_control');
        //$this->display('master_view',$oData);
        $this->vc->display($sPath,$oData);
    }
    
    protected function _get_formulario($path, $data = array()){
        $this->load->library('formulario');
        $data = array_merge($data, $this->formulario->parser($path));
        $this->display('forms',$data);
    }
    
    protected function _get_listagem($path, $data = []){
        $this->load->library('tabela');
        $data = array_merge($data, $this->tabela->parser($path));
        $this->display('table',$data);
    }
    
    protected function _get_custom($path, $data = []){
        $this->display('custom_pages/' . $path,$data);
    }

    protected function _obter_caminho_controle(){
        return $this->_caminho_controle;
    }
    
    protected function _add_func_permission_id($func,$id = NULL){
        if(is_array($func)){
            $this->_permissions_id = array_merge($this->_permissions_id,$func);
        }else{
            $this->_permissions_id[$func] = $id;
        }
    }

    protected function _allowed_area($id){
        if(!$this->cpp->area($id)){
            $this->_get_custom('area_restrita');
            $this->output->_display();
            exit;
        }
    }
    
    protected function _allowed_function($id){
        if(!$this->cpp->funcao($id)){
            $json = array(
                'action' => $this->_action,
                'message' => array(
                    'type' => MSG_WARNING,
                    'title' => 'Função Restrita',
                    'message' => 'Você não tem permissão para executar está função, caso necessário, entre em contato com o administrador.',
                    'closable' => TRUE
                )
            );
            $this->output
                ->set_status_header(400)
                ->set_content_type('application/json')
                ->set_output(json_encode($json))
                ->_display();
            exit;
        }
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
