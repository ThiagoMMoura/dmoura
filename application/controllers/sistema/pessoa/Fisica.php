<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Cliente
 *
 * @author Thiago Moura
 */
class Fisica extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/pessoa/fisica',TRUE);
    }
    
    public function index(){
        $this->cadastro();
    }
    
    public function cadastro(){
        $this->load->model('estado_model');
        $this->_add_data('estados',$this->estado_model->obter_uf_estado());
        $this->_view("Cadastro Pessoa Física",'cadastro',parent::RELATIVO_CONTROLE);
    }
    
    public function salvar(){
        $this->load->library('form_validation');
        $this->load->model('pessoa_model');
        $this->load->model('pessoa_fisica_model');
        
        $this->form_validation->set_rules('cpf', 'CPF',array(
                'trim','required','is_natural','exact_length[11]','is_unique[pessoa_fisica.cpf]',
                array($this->pessoa_fisica_model,'cpf_valido')
            ),
            array('cpf_valido' => 'Esté não é um CPF válido.')
        );
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[pessoa.email]');
        $this->form_validation->set_rules('nascimento', 'Data Nascimento', 'trim|exact_length[10]');
        $this->form_validation->set_rules('nacionalidade', 'Nacionalidade', 'trim');
        $this->form_validation->set_rules('naturalidade', 'Naturalidade', 'trim');
        $this->form_validation->set_rules('estado_civil', 'Estado Civil', 'trim');
        $this->form_validation->set_rules('sexo', 'Sexo', 'trim|required|in_list[Masculino,Feminino]');
        $this->form_validation->set_rules('cep', 'CEP', 'trim|required|is_natural');
        $this->form_validation->set_rules('cidade', 'Cidade', 'trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'trim');
        $this->form_validation->set_rules('numero', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim');
        $this->form_validation->set_rules('complemento2', 'Complemento2', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->cadastro();
        } else {
            if($this->endereco_model->consulta_cep($this->input->post('cep'))===FALSE){
                $cep_dados = $this->input->post();
                $cep_dados['complemento'] = $this->input->post('complemento2');
                $this->endereco_model->salva_cep($cep_dados);
            }
            $pessoa_dados = $this->input->post();
            $pessoa_dados['grupo'] = $this->config->item('grupo_padrao_cliente');
            $pessoa_dados['tipo'] = $this->pessoa_model->CLIENTE;
            $pessoa_dados['resenha'] = 1;
            $pessoa_dados['ativo'] = 1;
            if($this->pessoa_model->inserir($pessoa_dados)){
                $fisica_dados = $this->input->post();
                $fisica_dados['pessoa'] = $this->pessoa_model->id_inserido();
                if($this->pessoa_fisica_model->inserir($fisica_dados)){
                    if($this->input->is_ajax_request()){
                        echo json_encode(array('estatus'=>'sucesso'));
                    }else{
                        $call['tipo'] = ALERTA_SUCESSO;
                        $call['titulo'] = '';
                        $call['mensagem'] = 'Cadastro efetuado com sucesso!';
                        $call['fechavel'] = TRUE;
                        $this->_add_data('_callout',$call);
                        $this->cadastro();
                    }
                }
            }
        }
    }
}
