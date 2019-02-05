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
            'titulo' => ($id ? 'Editar' : 'Cadastro') . ' Usuário',
            'sv_id' => $id
        ];
        $this->vc->display('sistema/sistema/usuario/cadastro', $data);
    }
    
    public function consulta(){
        // Verificação de permissões
        $this->_allowed_area('sistema-usuario-consulta');
        
        $data = [
            'titulo' => 'Consulta Usuário'
        ];
        $this->vc->display('sistema/sistema/usuario/listagem', $data);
    }
    
    protected function _insert($data_form){
        $user = $this->doctrine->em->getRepository('Entity\User');
        // Setando regras de validação das entradas
        $this->load->library('aux_respect_validation',['data'=>$data_form],'rv');
        $v = $this->rv->getValidator();
        
        $this->rv->addRule('alias','Apelido',$v::alnum()->noWhitespace()->length(3,50));
        if (!preg_match('/^[a-zA-Z]/',$data_form['alias'])) {
            $this->rv->addError('alias','O Apelido deve ser iniciado com pelo menos uma letra.');
        } else if (!$this->rv->hasError('alias') && $user->hasAlias($data_form['alias'])) {
            $this->rv->addError('alias','Apelido já utilizado, tente outro.');
        }

        $this->rv->addRule('email','E-mail',$v::email()->length(0,150),FALSE);
        if (!$this->rv->hasError('email') && $data_form['email'] != NULL && $user->hasEmail($data_form['email'])) {
            $this->rv->addError('email','Já existe um usuário com este E-mail, tente outro.');
        }
        
        $this->rv->addRule('senha','Senha',$v::length(8,30),FALSE)->matchFields('senha','Senha','senhaconfirma','Confirma Senha');
        $this->rv->addRule('nivel','Nível',$v::intVal()->in([1,2,3]));
        
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
        	// Criando entidade User para persistir dados.
            $oUser = new Entity\User();
            $oUser->setName($data_form['alias']);
            $oUser->setEmail($data_form['email'] ?? NULL);
            $oUser->setNivel($data_form['nivel']);
            $oUser->setSenha(hash($this->config->item('hash-senha'),$data_form['senha'] ?? random_string()));
            
            foreach($data_form['alocado'] as $a){
                $oUser->alocar($this->doctrine->em->getRepository('Entity\Setor')->find($a));
            }
            
            $this->doctrine->em->persist($oUser);
            $this->doctrine->em->flush();
            log_message('DEBUG', 'Usuário: ' . json_encode($oUser));
            
            if($oUser->getId()){
                $type = MSG_SUCCESS;
                $message = 'Dados salvos com sucesso!';
                $status_header = 200;
                $form['id'] = $oUser->getId();
            }
        }
        
        // Enviar resposta
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
    
    protected function _update($data_form){
        $userRepository = $this->doctrine->em->getRepository('Entity\User');
        $oUser = $this->doctrine->em->getRepository('Entity\User')->find($data_form['id']);
        $this->load->library('aux_respect_validation',['data'=>$data_form],'rv');
        $v = $this->rv->getValidator();
        
        // Criando regras de validação dos campos
        $this->rv->addRule('alias','Apelido',$v::alnum()->noWhitespace()->length(3,50));
        if (!preg_match('/^[a-zA-Z]/',$data_form['alias'])) {
            $this->rv->addError('alias','O Apelido deve ser iniciado com pelo menos uma letra.');
        } else if (!$this->rv->hasError('alias') && $userRepository->hasAlias($data_form['alias'],$data_form['id'])) {
            $this->rv->addError('alias','Apelido já utilizado, tente outro.');
        }

        $this->rv->addRule('email','E-mail',$v::email()->length(0,150),FALSE);
        if (!$this->rv->hasError('email') && $data_form['email'] != NULL && $data_form['email'] != $oUser->getEmail() && $userRepository->hasEmail($data_form['email'])) {
            $this->rv->addError('email','Já existe um usuário com este E-mail, tente outro.');
        }
        
        $this->rv->addRule('senha','Senha',$v::length(8,30),FALSE)->matchFields('senha','Senha','senhaconfirma','Confirma Senha');
        $this->rv->addRule('nivel','Nível',$v::intVal()->in([1,2,3]));
        
        // Definindo messagem de falha
        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        
        // Validando campos
        if ($this->rv->isValid() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->rv->getMessages();
        } else {
            $oUser->setName($data_form['alias']);
            $oUser->setEmail($data_form['email'] ?? NULL);
            $oUser->setNivel($data_form['nivel']);

            $hash_senha = $data_form['senha'] ? hash($this->config->item('hash-senha'),$data_form['senha']) : '';
            if ($hash_senha) {
                $oUser->setSenha($hash_senha);
            }
            
            // Removendo Setores
            foreach($oUser->getSetores() as $s){
                if (!in_array($s->getId(),$data_form['alocado'])) {
                    $oUser->removeElementSetor($s);
                }
            }
            
            // Adicionando Setores
            foreach($data_form['alocado'] as $a){
                if (!$oUser->isAlocadoIn($a)) {
                    $oUser->alocar($this->doctrine->em->getRepository('Entity\Setor')->find($a));
                }
            }
            
            // Salvando dados
            $this->doctrine->em->persist($oUser);
            $this->doctrine->em->flush();
            $form = ['user'=>$oUser];
            if($oUser->getId()){
                $type = MSG_SUCCESS;
                $message = 'Dados salvos com sucesso!';
                $status_header = 200;
                $form['id'] = $oUser->getId();
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
    
    protected function _query($filter) {
        $form = $this->doctrine->em->getRepository('Entity\User')->getAllUsers();

        // Envia resposta.
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
    
    protected function _get($dataform){
        $type = MSG_ERROR;
        $message = 'Dados inválidos!';
        $status_header = 404;

        $arr_srl_config = new ArraySerializationConfig();
        $arr_srl_config_alocado = new ArraySerializationConfig(['id'],NULL,ArraySerializationConfig::MODE_ONLY_FIRST_VALUE);
        $arr_srl_config->setElementDefaultConfig('alocado',$arr_srl_config_alocado);

        $form = Arr::arraySerialization($this->doctrine->em->getRepository('Entity\User')->find($dataform['id']),$arr_srl_config);
        
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
                'title' => 'Editar ' . $this->_title,
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