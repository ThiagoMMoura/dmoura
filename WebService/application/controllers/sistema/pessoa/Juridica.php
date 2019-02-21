<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Juridica
 *
 * @author Thiago Moura
 */
class Juridica extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/pessoa/juridica','Pessoa Juridica','cadastro');
    }
    
    public function cadastro($id = NULL){
        $data = [
            'titulo' => 'Cadastro Pessoa Juridica',
            'sv_id' => $id
        ];
        $this->vc->display('sistema/pessoa/juridica/cadastro', $data);
    }
    
    public function consulta(){
        $data = [
            'titulo' => 'Consulta Pessoa Juridica'
        ];
        $this->vc->display('sistema/pessoa/juridica/listagem', $data);
    }
    
    protected function _insert($data_form) {
        // Setando regras de validação das entradas
        $this->load->library('aux_respect_validation',['data'=>$data_form],'rv');
        $v = $this->rv->getValidator();
        
        $this->rv->addRule('cnpj','CNPJ',$v::digit()->length(14,14,TRUE)->cnpj());
        $this->rv->addRule('inscricao_estadual','Inscrição Estadual',$v::digit()->length(NULL,32,TRUE),FALSE);
        $this->rv->addRule('apelido','Nome Fantasia',$v::notEmpty()->alnum()->length(5,100,TRUE));
        $this->rv->addRule('nome','Razão Social',$v::notEmpty()->alnum()->length(5,100,TRUE));
        /*$this->rv->addRule('autorizado','Autorizado',$v::arrayVal()->each($v::keySet(
                $v::key('id',$v::intVal(),FALSE),
                $v::key('nome',$v::notEmpty()->alpha()->length(5,100,TRUE)),
                $v::key('descricao',$v::stringType()->length(NULL,250,TRUE),FALSE)
            )),FALSE);*/
        $autorizado_rules = [
            ['field' => 'id', 'label' => 'Código', 'rules' => $v::intval(), 'mandatory' => FALSE],
            ['field' => 'nome', 'label' => 'Nome', 'rules' => $v::notEmpty()->alpha()->length(5,100,TRUE)],
            ['field' => 'descricao', 'label' => 'Descrição', 'rules' => $v::stringType()->length(NULL,250,TRUE), 'mandatory' => FALSE]
        ];
        $this->rv->addRulesSet('autorizado','Autorizado',$v::arrayVal(),$autorizado_rules,FALSE);
        $this->rv->addRulesSet('endereco','Endereço',$v::arrayVal()->length(1,10,TRUE), [], TRUE);
        // Setando mensagem padrão de erro.
        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        
        // Realizando validação
        if ($this->rv->isValid() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->rv->getMessages();
        } else {
            $type = MSG_SUCCESS;
                $message = 'Dados salvos com sucesso!';
                $status_header = 200;
                //$form['id'] = $oOperadora->getId();
        }
        
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Cadastro Operadora',
                'message' => $message,
                'closable' => TRUE
            ),
            'form' => $form
        );
        $this->output
            ->set_status_header($status_header)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
}