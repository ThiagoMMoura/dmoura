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
        $lista_cep = array();
        if($cep!=NULL){
            $select = array();
            $cep = str_replace('-', '', $cep);
            $select['select'] = array('cep','e.nome AS Estado','m.nome AS Municipio','b.nome AS bairro','l.nome AS logradouro');
            $select['join'] = array(
                array('estado e','e.uf = endereco.uf'),
                array('municipio m','m.id = endereco.municipio'),
                array('bairro b','b.id = endereco.bairro'),
                array('logradouro l','l.id = endereco.logradouro')
            );
            $select['where']['cep'] = $cep;
            if($this->endereco_model->selecionar($select)){
                $lista_cep = $this->endereco_model->registro();
            }
        }
        if($this->input->is_ajax_request()){
            echo json_encode($lista_cep);
        }else{
            $this->_add_data('lista_cep',$lista_cep);
            $this->_view("Consulta CEP",'consulta',parent::RELATIVO_CONTROLE);
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
