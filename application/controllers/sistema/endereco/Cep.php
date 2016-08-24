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
    
    public function consulta($cep){
        if($cep!=NULL){
            $select = array();
            $cep = str_replace('-', '', $cep);
            $select['where']['cep'] = $cep;
            if($this->endereco_model->selecionar($select)){
                if($this->input->is_ajax_request()){
                    echo json_encode($this->endereco_model->resultado());
                }
            }
            
        }
    }
    
    public function salvar(){
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('cep', 'CEP', 'trim|required|numeric');
        $this->form_validation->set_rules('uf', 'UF', 'trim|required|alpha|exact_length[2]');
        $this->form_validation->set_rules('cidade', 'Cidade', 'trim|alpha');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'trim');
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim');
        
        if ($this->form_validation->run() == FALSE) {
            if($this->input->is_ajax_request()){
                
            }else{
                $this->index();
            }
        } else {
            $resposta = array('estatus'=>'');
            if($this->endereco_model->consulta_cep($this->input->post('cep'))===FALSE){
                if($this->endereco_model->salva_cep($this->input->post())){
                    $resposta['estatus'] = 'sucesso';
                }else{
                    $resposta['estatus'] = 'falha';
                }
            }else{
                $resposta['estatus'] = 'existente';
            }
            if($this->input->is_ajax_request()){
                echo json_encode($resposta);
            }
        }
    }
}
