<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of usuario
 *
 * @author Thiago Moura
 */
class Usuario extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/sistema/usuario','Usuário','consulta');
        $this->_add_func_permission_id('insert', 'sistema-usuario-inserir');
        $this->_add_func_permission_id('update', 'sistema-usuario-alterar');
        $this->_add_func_permission_id('get', 'sistema-usuario-visualizar');
    }
    
    public function cadastro($id = NULL){
        // Verificação de permissões
        $this->_allowed_area('sistema-usuario-cadastro');
        
        $data = [
            'titulo' => 'Cadastro Usuário',
            'sv_id' => $id
        ];
        $this->_get_formulario('sistema/sistema/usuario/cadastro', $data);
    }
    
    public function consulta(){
        // Verificação de permissões
        $this->_allowed_area('sistema-usuario-consulta');
        
        $data = [
            'titulo' => 'Consulta Usuário'
        ];
        $this->_get_listagem('sistema/sistema/usuario/listagem', $data);
    }
    
    protected function _insert($dataform){
        $this->load->library('form_validation');
        $this->load->model('user_model');
        
        $this->form_validation->set_data($dataform);
        $this->form_validation->set_rules('alias', 'Apelido', 'trim|required|min_length[3]|is_unique[user.alias]');
        $this->form_validation->set_rules('email', 'Email', 'trim|max_length[150]|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('senha', 'Senha', 'trim|min_length[8]|max_length[30]');
        $this->form_validation->set_rules('senhaconfirma', 'Confirma Senha', 'trim|matches[senha]');
        $this->form_validation->set_rules('nivel', 'Nível', 'trim|required|in_list[1,2,3]');
        $this->form_validation->set_rules('alocado[]', 'Setores Alocado', 'required');
        
        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        
        if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
            $dataform['senha'] = hash($this->config->item('hash-senha'),$dataform['senha']);
            if($dataform['email']==''){
               $dataform['email'] = NULL; 
            }
            if($this->user_model->inserir($dataform)){
                $form['id'] = $this->user_model->id_inserido();
                
                $this->load->model('alocado_model');
                $lote = [];
                
                foreach($dataform['alocado'] as $a){
                    $lote[] = [
                        'iduser' => $form['id'],
                        'idsetor' => $a
                    ];
                }
                if($this->alocado_model->inserir_lote($lote)){
                    $type = MSG_SUCCESS;
                    $message = 'Dados salvos com sucesso!';
                    $status_header = 200;
                }
                
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
    
    protected function _update($dataform){
        $this->load->library('form_validation');
        $this->load->model('user_model');
        
        $this->form_validation->set_data($dataform);
        $this->form_validation->set_rules('id', 'Código', 'trim|required|min_length[1]');
        $this->form_validation->set_rules('alias', 'Apelido', 'trim|required|min_length[3]|callback_is_unique_alias');
        $this->form_validation->set_rules('email', 'Email', 'trim|max_length[150]|valid_email|callback_is_unique_email');
        $this->form_validation->set_rules('senha', 'Senha', 'trim|min_length[8]|max_length[30]');
        $this->form_validation->set_rules('senhaconfirma', 'Confirma Senha', 'trim|matches[senha]');
        $this->form_validation->set_rules('nivel', 'Nível', 'trim|required|in_list[1,2,3]');
        $this->form_validation->set_rules('alocado[]', 'Setores Alocado', 'required');
        
        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        
        if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
            if($dataform['senha']!=''){
                $dataform['senha'] = hash($this->config->item('hash-senha'),$dataform['senha']);
            }else{
                unset($dataform['senha']);
            }
            if($dataform['email']==''){
               $dataform['email'] = NULL; 
            }
            if($this->user_model->alterar($dataform,['id'=>$dataform['id']])){
                
                $this->load->model('alocado_model');
                $setores = ['idsetor'=>[],'id'=>[]];
                if($this->alocado_model->selecionar(['where'=>['iduser'=>$dataform['id']]])){
                    foreach($this->alocado_model->registros() as $r){
                        $setores['idsetor'][] = $r['idsetor'];
                        $setores['id'][] = $r['id'];
                    }
                }
                $lote = [];
                
                foreach($dataform['alocado'] as $a){
                    if(array_search($a, $setores['idsetor'])===FALSE){
                        $lote[] = [
                            'iduser' => $dataform['id'],
                            'idsetor' => $a
                        ];
                    }
                }
                if(count($lote)>0){
                    $this->alocado_model->inserir_lote($lote);
                }
                foreach($setores['idsetor'] as $k => $s){
                    if(array_search($s, $dataform['alocado'])===FALSE){
                        $this->alocado_model->deletar($setores['id'][$k]);
                    }
                }
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
    
    protected function _query($dataform) {
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
        // Where
        $sql['where'] = ['nivel <'=>4];
        if(key_exists('where', $dataform)){
            $sql['where'] = array_merge($dataform['where'],$sql['where']);
        }
        // Executa Query
        if($this->{$dataform['model']}->selecionar($sql) && $this->{$dataform['model']}->num_registros()>0){
            $form = $this->{$dataform['model']}->registros();
            $niveis = ['Institucional','Intermediário','Operário','Cliente'];
            foreach($form as $k=>$r){
                $form[$k]['nivel'] = $niveis[$r['nivel'] - 1];
            }
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
    
    protected function _get($dataform){
        $valor = $dataform['id'];
        $type = MSG_ERROR;
        $message = 'Dados inválidos!';
        $status_header = 404;
        $data_form = array();
        
        if($valor!=NULL){
            $this->load->model('user_model');
            $selecionar = [
                'select' => 'id,alias,email,nivel'
            ];
            $selecionar['where'] = [
                'id' => $valor,
                'nivel <' => 4
            ];
            if($this->user_model->selecionar($selecionar) && $this->user_model->num_registros()===1){
                $data_form = $this->user_model->registro();
                
                $this->load->model('alocado_model');
                $selecionar = [
                    'selecionar' => 'id,idsetor',
                    'where' => ['iduser' => $dataform['id']]
                ];
                if($this->alocado_model->selecionar($selecionar)){
                    $data_form['alocado'] = [];
                    foreach($this->alocado_model->registros() as $reg){
                        $data_form['alocado'][] = $reg['idsetor'];
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
    
    public function is_unique_alias($str){
        $select = [
            'where' => [
                'id <> ' . $this->input->post('id'),
                'alias = ' . $str
            ]
        ];
        if($this->user_model->selecionar($select)){
            if($this->user_model->num_registros()!=0){
                $this->form_validation->set_message('is_unique_alias', 'O {field} informado já existe, tente outro.');
                return FALSE;
            }
        }
        return TRUE;
    }
    
    public function is_unique_email($str){
        $select = [
            'where' => [
                'id <> ' . $this->input->post('id'),
                'email = ' . $str
            ]
        ];
        if($this->user_model->selecionar($select)){
            if($this->user_model->num_registros()!=0){
                $this->form_validation->set_message('is_unique_email', 'O {field} informado já existe, tente outro.');
                return FALSE;
            }
        }
        return TRUE;
    }
}