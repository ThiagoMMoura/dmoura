<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Teste
 *
 * @author Thiago Moura
 */
class Teste extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/ferramentas/teste','Teste','cadastro');
    }
    
    public function cadastro($id = NULL){
        $data = [
            'titulo' => 'Formulário de Testes',
            'sv_id' => $id
        ];
        //$this->_get_custom('ferramentas/teste/formulario', $data);
        $this->vc->display('sistema/pessoa/juridica/cadastro',$data);
        //$this->_get_formulario('sistema/ferramentas/teste/cadastro', $data);
    }

    protected function _insert($data_form){
        $this->load->library('aux_respect_validation',['data'=>$data_form],'rv');
        //$this->form_validation->set_data($data_form);
        $v = $this->rv->getValidator();
        $this->rv->addRule('apelido','Apelido',$v::alpha());
        $this->rv->addRule('nome','Nome',$v::alpha()->length(3,50));
        $this->rv->addRule('nascimento','Nascimento',$v::date());
        $this->rv->addRule('genero','Genêro',$v::alpha()->in(['feminino','masculino']));
//        $this->form_validation->set_rules('apelido', 'Apelido', 'trim');
//        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[5]');
//        $this->form_validation->set_rules('nascimento', 'Data Nascimento', 'trim|max_length[10]');
//        $this->form_validation->set_rules('nacionalidade', 'Nacionalidade', 'trim');
//        $this->form_validation->set_rules('naturalidade', 'Naturalidade', 'trim');
//        $this->form_validation->set_rules('estado_civil', 'Estado Civil', 'trim');
//        $this->form_validation->set_rules('genero', 'Gênero', 'trim|required|in_list[masculino,feminino]');
//        $this->form_validation->set_rules('conjuge', 'Conjuge', 'trim');
//
//        $this->form_validation->set_rules_for_field_list('enderecos','Endereços','required');
//        $rules_enderecos = [
//            ['field'=>'id'          , 'label'=>'Id Endereço'  ,'rules'=>'trim'],
//            ['field'=>'tipo'        , 'label'=>'Tipo Endereço','rules'=>'trim|required|in_list[residencial,comercial]'],
//            ['field'=>'cep'         , 'label'=>'CEP'          ,'rules'=>'trim|required'],
//            ['field'=>'uf'          , 'label'=>'Estado'       ,'rules'=>'trim|required'],
//            ['field'=>'municipio'   , 'label'=>'Municipio'    ,'rules'=>'trim|required'],
//            ['field'=>'bairro'      , 'label'=>'Bairro'       ,'rules'=>'trim|required'],
//            ['field'=>'logradouro'  , 'label'=>'Logradouro'   ,'rules'=>'trim|required'],
//            ['field'=>'numero'      , 'label'=>'Número'       ,'rules'=>'trim|is_natural'],
//            ['field'=>'complemento' , 'label'=>'Complemento'  ,'rules'=>'trim'],
//            ['field'=>'complemento2', 'label'=>'Complemento2' ,'rules'=>'trim']
//        ];
//        $this->form_validation->set_rules_for_all_fields_in_list('enderecos','Endereços',$rules_enderecos);

        /*if(isset($data_form['enderecos']) and $data_form['enderecos'] != NULL){
            $this->form_validation->set_rules('enderecos[0]', 'Endereços', 'required');

            foreach($data_form['enderecos'] as $k => $v){
                $this->form_validation->set_rules('enderecos[' . $k . '][id]', 'Id Endereço', 'trim');
                $this->form_validation->set_rules('enderecos[' . $k . '][tipo]', 'Tipo Endereço', 'trim|required|in_list[residencial,comercial]');
                $this->form_validation->set_rules('enderecos[' . $k . '][cep]', 'CEP', 'trim|required');
                $this->form_validation->set_rules('enderecos[' . $k . '][uf]', 'Estado', 'trim|required');
                $this->form_validation->set_rules('enderecos[' . $k . '][municipio]', 'Municipio', 'trim|required');
                $this->form_validation->set_rules('enderecos[' . $k . '][bairro]', 'Bairro', 'trim|required');
                $this->form_validation->set_rules('enderecos[' . $k . '][logradouro]', 'Logradouro', 'trim|required');
                $this->form_validation->set_rules('enderecos[' . $k . '][numero]', 'Número', 'trim|is_natural');
                $this->form_validation->set_rules('enderecos[' . $k . '][complemento]', 'Complemento', 'trim');
                $this->form_validation->set_rules('enderecos[' . $k . '][complemento2]', 'Complemento2', 'trim');
            }
        }else{
            $this->form_validation->set_rules('enderecos', 'Endereços', 'required');
        }*/

        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();

//        if ($this->form_validation->run() == FALSE) {
        if ($this->rv->isValid() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->rv->getMessages();
            //$form['_teste_']=$data_form['enderecos'];
        }else{

            $type = MSG_SUCCESS;
            $message = 'Dados salvos com sucesso!';
            $status_header = 200;
            $form = $data_form;
        }

        $json = array(
            'action' => $this->_action,
            'message' => array(
                'type' => $type,
                'title' => 'Cadastro Pessoa Física',
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