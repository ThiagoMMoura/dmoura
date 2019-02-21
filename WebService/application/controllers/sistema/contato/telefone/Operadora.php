<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Operadora
 *
 * @author Thiago Moura
 */
class Operadora extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/contato/telefone/operadora','Operadora Telefônica','cadastro');
        $this->_add_func_permission_id('insert','contato-telefone-operadora-inserir');
    }
    
    public function cadastro($id = NULL){
        // Verificação de permissões
        $this->_allowed_area('contato-telefone-operadora-cadastro');
        
        $data = [
            'titulo' => 'Cadastro Operadora Telefônica',
            'sv_id' => $id
        ];
        $this->vc->display('sistema/contato/telefone/operadora/cadastro', $data);
        //$this->_get_formulario('sistema/contato/telefone/operadora/cadastro', $data);
    }
    
    public function consulta(){
        // Verificação de permissões
        $this->_allowed_area('contato-telefone-operadora-consulta');
        
        $data = [
            'titulo' => 'Consulta Operadora Telefônica'
        ];
        $this->vc->display('sistema/contato/telefone/operadora/listagem', $data);
    }

    protected function _insert($data_form){
    	$operadora = $this->doctrine->em->getRepository('Entity\OperadoraTelefone');

    	// Setando regras de validação das entradas
        $this->load->library('aux_respect_validation',['data'=>$data_form],'rv');
        $v = $this->rv->getValidator();

        $this->rv->addRule('operadora','Operadora',$v::alnum()->length(2,50));
        if (!$this->rv->hasError('operadora') && $operadora->hasOperadora($data_form['operadora'])) {
            $this->rv->addError('operadora','Apelido já utilizado, tente outro.');
        }

        /*$this->load->library('form_validation');
        $this->load->model('operadora_telefone_model');
        
        $this->form_validation->set_data($data_form);
        $this->form_validation->set_rules('operadora', 'Operadora', 'trim|required|min_length[2]|is_unique[operadora_telefone.operadora]');*/
        
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
            // Caso não ocorra erros na validação
            // Criando entidade OperadoraTelefone para persistir dados.
            $oOperadora = new Entity\OperadoraTelefone();
            $oOperadora->setName($data_form['operadora']);

            $this->doctrine->em->persist($oOperadora);
            $this->doctrine->em->flush();

            if ($oOperadora->getId()) {
            	$type = MSG_SUCCESS;
                $message = 'Dados salvos com sucesso!';
                $status_header = 200;
                $form['id'] = $oOperadora->getId();
            }
        }

        /*if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
            if($this->operadora_telefone_model->inserir($data_form)){
                $type = MSG_SUCCESS;
                $message = 'Dados salvos com sucesso!';
                $status_header = 200;
                $form['id'] = $this->operadora_telefone_model->id_inserido();
            }
        }*/
        
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
    
    protected function _list($data_form){
        //$this->load->model('operadora_telefone_model');
        $form = []; //$this->operadora_telefone_model->obter_id_operadora();
        $operadoras = $this->doctrine->em->getRepository('Entity\OperadoraTelefone')->findAll();
        foreach($operadoras as $operadora){
            $form[$operadora->getId()] = $operadora->getName();
        }

        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => MSG_SUCCESS,
                'title' => 'Listagem',
                'message' => 'Listagem realizada com sucesso!',
                'closable' => TRUE
            ),
            'form' => $form
        );
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
    
    protected function _get($data_form){
        $type = MSG_ERROR;
        $message = 'Dados inválidos!';
        $status_header = 404;
        
        $form = $this->doctrine->em->getRepository('Entity\OperadoraTelefone')->find($data_form['id']);
        
        if($form){
            $type = MSG_SUCCESS;
            $message = 'Registro encontrado com sucesso!';
            $status_header = 200;
        }else{
            $message = 'Nenhum registro encontrado!';
        }

        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Cadastro Operadora Telefônica',
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
    
    protected function _query($filter){
        $form = $this->doctrine->em->getRepository('Entity\OperadoraTelefone')->findAll();
        
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => MSG_SUCCESS,
                'title' => 'Listagem',
                'message' => 'Listagem realizada com sucesso!',
                'closable' => TRUE
            ),
            'form' => $form
        );
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json')
            ->set_output(json_encode($json));
    }
}
