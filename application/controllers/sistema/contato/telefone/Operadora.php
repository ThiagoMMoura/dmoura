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
    }
    
    public function cadastro($id = NULL){
        $data = [
            'titulo' => 'Cadastro Operadora Telefônica',
            'sv_id' => $id
        ];
        $this->_get_formulario('sistema/contato/telefone/operadora/cadastro', $data);
    }
    
    public function consulta(){
        $data = [
            'titulo' => 'Consulta Operadora Telefônica'
        ];
        $this->_get_listagem('sistema/contato/telefone/operadora/listagem', $data);
    }

    protected function _insert($data_form){
        $this->load->library('form_validation');
        $this->load->model('operadora_telefone_model');
        
        $this->form_validation->set_data($data_form);
        $this->form_validation->set_rules('operadora', 'Operadora', 'trim|required|min_length[2]|is_unique[operadora_telefone.operadora]');
        
        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        
        if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
            if($this->operadora_telefone_model->inserir($data_form)){
                $type = MSG_SUCCESS;
                $message = 'Dados salvos com sucesso!';
                $status_header = 200;
                $form['id'] = $this->operadora_telefone_model->id_inserido();
            }
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
    
    protected function _list($data_form){
        $this->load->model('operadora_telefone_model');
        $form = $this->operadora_telefone_model->obter_id_operadora();
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
        $this->load->model('operadora_telefone_model');
        
        $valor = $form['id'];
        $type = MSG_ERROR;
        $message = 'Dados inválidos!';
        $status_header = 404;
        $data_form = array();
        
        if($valor!=NULL){
            $selecionar['select'] = array('id','operadora');
            $selecionar['where']['id'] = $valor;
            if($this->operadora_telefone_model->selecionar($selecionar) && $this->operadora_telefone_model->num_registros()===1){
                $data_form = $this->operadora_telefone_model->registro();
                
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
                'title' => 'Cadastro Operadora Telefônica',
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
}
