<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Tipo
 *
 * @author Thiago Moura
 */
class Tipo extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/basico/telefone/tipo',TRUE);
    }
    
    public function cadastro(){
        $this->load->model('tipo_telefone_model');

        $selecionar['select'] = array('id','tipo');
        $selecionar['order_by'] = 'tipo';
        if($this->tipo_telefone_model->selecionar($selecionar)){
            $this->_add_data('lista_tipos', $this->tipo_telefone_model->registros());
        }
        
        $this->_view("Cadastro Tipo Telefone",'cadastro',parent::RELATIVO_CONTROLE);
    }
    
    public function salvar(){
        $this->load->library('form_validation');
        $this->load->model('tipo_telefone_model');
        
        $this->form_validation->set_rules('tipo', 'Tipo Telefone', 'trim|required|min_length[2]|is_unique[tipo_telefone.tipo]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->cadastro();
        } else {
            $call['tipo'] = ALERTA_ERRO;
            $call['titulo'] = '';
            $call['mensagem'] = 'Falha ao salvar dados!';
            $call['fechavel'] = TRUE;
            $json['estatus'] = 'falha';
            
            if($this->tipo_telefone_model->inserir($this->input->post())){
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
