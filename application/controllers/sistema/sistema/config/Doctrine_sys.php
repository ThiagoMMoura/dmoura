<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of doctrine
 *
 * @author Thiago Moura
 */
class Doctrine_sys extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/sistema/config/doctrine_sys','Doctrine','schema_generator');
    }
    
    public function schema_generator(){
        // Verificação de permissões
        $this->_allowed_area('sistema-config-doctrine');
        
        $data = [
            'titulo' => 'Doctrine - Schema Generator'
        ];
        $this->vc->display('sistema/sistema/config/doctrine/schema_generator', $data);
    }
    
    protected function _insert($data_form){
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->doctrine->em);
        $classes = [];
        
        foreach ($data_form['entities'] as $entity) {
            $classes[] = $this->doctrine->em->getClassMetadata('Entity\\' . $entity);
        }
        
        /*/ Setando mensagem padrão de erro.
        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;*/
        $form = array();
            
        $type = MSG_SUCCESS;
        $message = 'Schema gerado com sucesso';
        $status_header = 200;
        //$form['id'] = $oUser->getId();
        
        $tool->updateSchema($classes);
        
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
}