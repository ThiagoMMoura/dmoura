<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Estado
 *
 * @author Thiago Moura
 */
class Estado extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/endereco/estado','Estado','cadastro');
    }
    
    protected function _list($data_form){
        $this->load->model('estado_model');
        $form = $this->estado_model->obter_uf_estado();
        
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
