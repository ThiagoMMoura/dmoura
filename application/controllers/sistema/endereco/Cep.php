<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Cep
 *
 * @author 61171
 */
class Cep extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/endereco/cep',TRUE);
        $this->load->model('endereco_model');
    }
    
    public function consulta($cep,$retorno = 'json'){
        if($cep!=NULL){
            $select = array();
            $cep = str_replace('-', '', $cep);
            $select['where']['cep'] = $cep;
            if($this->endereco_model->selecionar($select)){
                if($retorno == 'json'){
                    echo json_encode($this->endereco_model->resultado());
                }
            }
            
        }
    }
}
