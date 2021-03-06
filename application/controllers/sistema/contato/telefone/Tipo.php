<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Tipo
 *
 * @author Thiago Moura
 */
class Tipo extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/basico/telefone/tipo','Tipo Telefone','cadastro');
    }
    
    public function cadastro($id = NULL){
        // Verificação de permissões
        $this->_allowed_area('contato-telefone-tipo-cadastro');
        
        $data = [
            'titulo' => 'Cadastro Tipo Telefone',
            'sv_id' => $id
        ];
        $this->vc->display('sistema/contato/telefone/tipo/cadastro', $data);
    }
    
    public function consulta(){
        // Verificação de permissões
        $this->_allowed_area('contato-telefone-tipo-consulta');
        
        $data = [
            'titulo' => 'Consulta Tipo Telefone'
        ];
        $this->vc->display('sistema/contato/telefone/tipo/listagem', $data);
    }
    
    protected function _insert($data_form){
        // Verificação de permissões
        $this->_allowed_function('contato-telefone-tipo-inserir');
        
        $this->load->library('form_validation');
        $this->load->model('tipo_telefone_model');
        
        $this->form_validation->set_data($data_form);
        $this->form_validation->set_rules('tipo', 'Tipo Telefone', 'trim|required|min_length[2]|is_unique[tipo_telefone.tipo]');
        
        // Pré configurando menssagem de erro.
        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 404;
        $form = array();
        
        if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
            $oTipo = new Entity\TipoTelefone();
            $oTipo->setTipo($data_form['tipo']);

            $this->doctrine->em->persist($oTipo);
            $this->doctrine->em->flush();

            if($oTipo->getId()){
                $type = MSG_SUCCESS;
                $message = 'Dados salvos com sucesso!';
                $status_header = 200;
                $form['id'] = $oTipo->getId();
            }
        }
        
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Cadastro Tipo Telefone',
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
        $form = [];
        $tipos = $this->doctrine->em->getRepository('Entity\TipoTelefone')->findAll();
        foreach($tipos as $tipo){
            $form[$tipo->getId()] = $tipo->getName();
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
    
    protected function _get($form){
        $this->load->model('tipo_telefone_model');
        
        $valor = $form['id'];
        $type = MSG_ERROR;
        $message = 'Dados inválidos!';
        $status_header = 404;
        $data_form = array();
        
        if($valor!=NULL){
            $selecionar['select'] = array('id','tipo');
            $selecionar['where']['id'] = $valor;
            if($this->tipo_telefone_model->selecionar($selecionar) && $this->tipo_telefone_model->num_registros()===1){
                $data_form = $this->tipo_telefone_model->registro();
                
                $type = MSG_SUCCESS;
                $message = 'Registro encontrado com sucesso!';
                $status_header = 200;
            }else{
                $message = 'Nenhum registro encontrado!';
            }
        }
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Cadastro Tipo Telefone',
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
    
    protected function _query($filter){
        $form = $this->doctrine->em->getRepository('Entity\TipoTelefone')->findAll();
        
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
