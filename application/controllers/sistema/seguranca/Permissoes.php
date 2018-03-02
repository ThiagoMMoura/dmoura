<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Permissoes
 *
 * @author Thiago Moura
 */
class Permissoes extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/seguranca/permissoes','Permissoes');
    }
    
    protected function _list($dataform){
        $this->load->library('privilegios');
        $this->privilegios->parser('sistema/seguranca/permissoes');
        $lista = $this->privilegios->getPermissionsList();
        $form = [];
        foreach($lista as $l){
            $form[$l['id']] = $l['title'];
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
}