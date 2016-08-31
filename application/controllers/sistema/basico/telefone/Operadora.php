<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Operadora
 *
 * @author Thiago Moura
 */
class Operadora extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/basico/telefone/operadora',TRUE);
    }
    
    public function cadastro(){
        $this->_view("Cadastro Operadora Telefônica",'cadastro',parent::RELATIVO_CONTROLE);
    }
    
    public function salvar(){
        $this->load->library('form_validation');
        $this->load->model('operadora_telefone_model');
        
        $this->form_validation->set_rules('operadora', 'Operadora', 'trim|required|min_length[2]|is_unique[operadora_telefone.operadora]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->cadastro();
        } else {
            $call['tipo'] = ALERTA_ERRO;
            $call['titulo'] = '';
            $call['mensagem'] = 'Falha ao salvar dados!';
            $call['fechavel'] = TRUE;
            $json['estatus'] = 'falha';
            
            if($this->operadora_telefone_model->inserir($this->input->post())){
                $json['estatus'] = 'sucesso';
                $call['tipo'] = ALERTA_SUCESSO;
                $call['mensagem'] = 'Cadastro efetuado com sucesso!';
            }
            
            if($this->input->is_ajax_request()){
                $json['callout'] = $call;
                echo json_encode($json);
            }else{
                $this->_add_data('_callout',$call);
                $this->cadastro();
            }
        }
    }
}
