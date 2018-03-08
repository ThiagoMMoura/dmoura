<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Setor
 *
 * @author Thiago Moura
 */
class Setor extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/seguranca/setor','Setor','cadastro');
    }
    
    public function cadastro($id = NULL){
        // Verificação de permissões
        $this->_allowed_area('seguranca-setor-cadastro');
        
        $data = [
            'titulo' => 'Cadastro Setor',
            'sv_id' => $id
        ];
        $this->_get_formulario('sistema/seguranca/setor/cadastro', $data);
    }
    
    public function consulta(){
        // Verificação de permissões
        $this->_allowed_area('seguranca-setor-consulta');
        
        $data = [
            'titulo' => 'Consulta Setor'
        ];
        $this->_get_listagem('sistema/seguranca/setor/listagem', $data);
    }
    
    protected function _insert($data_form){
        // Verificação de permissões
        $this->_allowed_function('seguranca-setor-inserir');
        
        $this->load->library('form_validation');
        $this->load->model('setor_model');
        
        $this->form_validation->set_data($data_form);
        $this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|min_length[3]|is_unique[setor.titulo]');
        $this->form_validation->set_rules('descricao', 'Descrição', 'trim|max_length[250]');
        
        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        
        if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
            if($this->setor_model->inserir($data_form)){
                $form['id'] = $this->setor_model->id_inserido();
                
                $this->load->model('permissao_model');
                $this->load->library('privilegios');
                
                $this->privilegios->parser('sistema/seguranca/permissoes');
                $lista = $this->privilegios->getPermissionsList();
                $lote = [];
                
                foreach($lista as $p){
                    $lote[] = [
                        'idsetor' => $form['id'],
                        'idpermissao' => $p['id'],
                        'acesso' => array_search($p['id'], $data_form['permissoes'])!==FALSE,
                        'tipo' => $p['type']
                    ];
                }
                
                $this->permissao_model->inserir_lote($lote);
                
                $type = MSG_SUCCESS;
                $message = 'Dados salvos com sucesso!';
                $status_header = 200;
            }
        }
        
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Cadastro Setor',
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
    
    /**
     * Realiza atualização do registro no banco de dados.
     * 
     * @param mixed $dataform
     */
    protected function _update($dataform){
        // Verificação de permissões
        $this->_allowed_function('seguranca-setor-alterar');
        
        $this->load->library('form_validation');
        $this->load->model('setor_model');
        
        $this->form_validation->set_data($dataform);
        $this->form_validation->set_rules('id','Código','trim|required');
        $this->form_validation->set_rules('titulo', 'Titulo', 'trim|required|min_length[3]|callback_is_unique_title');
        $this->form_validation->set_rules('descricao', 'Descrição', 'trim|max_length[250]');
        
        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
            if($this->setor_model->alterar($dataform,['id'=>$dataform['id']])){
                
                $this->load->model('permissao_model');
                $this->load->library('privilegios');
                
                $this->privilegios->parser('sistema/seguranca/permissoes');
                $lista = $this->privilegios->getPermissionsList();
                $select = [
                    'where' => 'idsetor = ' . $dataform['id']
                ];
                $reg = [];
                if($this->permissao_model->selecionar($select)){
                    $reg = $this->permissao_model->registros();
                }
                
                foreach($lista as $l){
                    $p = NULL;
                    foreach($reg as $r){
                        if($l['id']===$r['idpermissao']){
                            $p = $r;
                            break;
                        }
                    }
                    if($p!==NULL){
                        $p['acesso'] = key_exists('permissoes', $dataform) && array_search($p['idpermissao'], $dataform['permissoes'])!==FALSE;
                        $this->permissao_model->alterar($p,['id'=>$p['id']]);
                    }else{
                        $p = [
                            'idsetor' => $dataform['id'],
                            'idpermissao' => $l['id'],
                            'acesso' => key_exists('permissoes', $dataform) && array_search($l['id'], $dataform['permissoes'])!==FALSE,
                            'tipo' => $l['type']
                        ];
                        $this->permissao_model->inserir($p);
                    }
                }
                
                $type = MSG_SUCCESS;
                $message = 'Dados salvos com sucesso!';
                $status_header = 200;
                $form['id'] = $dataform['id'];
            }
        }
        
        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Cadastro Setor',
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
    
    protected function _get($dataform){
        $valor = $dataform['id'];
        $type = MSG_ERROR;
        $message = 'Dados inválidos!';
        $status_header = 404;
        $data_form = array();
        
        if($valor!=NULL){
            $this->load->model('setor_model');
            $selecionar = [
                'select' => '*'
            ];
            $selecionar['where']['id'] = $valor;
            if($this->setor_model->selecionar($selecionar) && $this->setor_model->num_registros()===1){
                $data_form = $this->setor_model->registro();
                
                $this->load->model('permissao_model');
                $selecionar = [
                    'selecionar' => 'idpermissao',
                    'where' => ['idsetor' => $data_form['id'], 'acesso' => TRUE]
                ];
                if($this->permissao_model->selecionar($selecionar)){
                    $data_form['permissoes'] = [];
                    foreach($this->permissao_model->registros() as $reg){
                        $data_form['permissoes'][] = $reg['idpermissao'];
                    }
                }
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
    
    protected function _list($dataform){
        $this->load->model('setor_model');
        $form = $this->setor_model->obter_id_setor();
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
    
    public function is_unique_title($str){
        $select = [
            'where' => [
                'id <> ' . $this->input->post('id'),
                'titulo = ' . $str
            ]
        ];
        if($this->setor_model->selecionar($select)){
            if($this->setor_model->num_registros()!=0){
                $this->form_validation->set_message('is_unique_title', 'O {field} informado já existe, tente outro.');
                return FALSE;
            }
        }
        return TRUE;
    }
}
