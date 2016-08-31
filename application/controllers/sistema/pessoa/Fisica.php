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
        $this->busca();
    }
    
    public function cadastro(){
        $this->load->model('estado_model');
        $this->load->model('tipo_telefone_model');
        $this->load->model('operadora_telefone_model');
        $this->_add_data('estados',$this->estado_model->obter_uf_estado());
        $this->_add_data('tipos_telefone',$this->tipo_telefone_model->obter_id_tipo());
        $this->_add_data('operadoras_telefone',$this->operadora_telefone_model->obter_id_operadora());
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
        $this->form_validation->set_rules('nascimento[dia]', 'Dia Nascimento', 'trim|required|exact_length[2]');
        $this->form_validation->set_rules('nascimento[mes]', 'Mes Nascimento', 'trim|required|exact_length[2]');
        $this->form_validation->set_rules('nascimento[ano]', 'Ano Nascimento', 'trim|required|exact_length[4]');
        $this->form_validation->set_rules('nacionalidade', 'Nacionalidade', 'trim');
        $this->form_validation->set_rules('naturalidade', 'Naturalidade', 'trim');
        $this->form_validation->set_rules('estado_civil', 'Estado Civil', 'trim');
        $this->form_validation->set_rules('sexo', 'Sexo', 'trim|required|in_list[Masculino,Feminino]');
        $this->form_validation->set_rules('cep', 'CEP', 'trim|required|is_natural');
        $this->form_validation->set_rules('municipio', 'Municipio', 'trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'trim');
        $this->form_validation->set_rules('numero', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim');
        $this->form_validation->set_rules('complemento2', 'Complemento2', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->cadastro();
        } else {
            $this->load->helper('string');
            $this->load->model('endereco_model');
            
            if($this->endereco_model->consulta_cep($this->input->post('cep'))===FALSE){
                $cep_dados = $this->input->post();
                $cep_dados['complemento'] = $this->input->post('complemento2');
                $this->endereco_model->salva_cep($cep_dados);
            }
            $call['tipo'] = ALERTA_ERRO;
            $call['titulo'] = '';
            $call['mensagem'] = 'Falha ao salvar dados!';
            $call['fechavel'] = TRUE;
            $json['estatus'] = 'falha';
            
            $senha = random_string();
            $pessoa_dados = $this->input->post();
            $pessoa_dados['grupo'] = $this->config->item('grupo_padrao_cliente');
            $pessoa_dados['tipo'] = Pessoa_model::CLIENTE;
            $pessoa_dados['senha'] = md5(random_string());
            $pessoa_dados['resenha'] = 1;
            $pessoa_dados['ativo'] = 1;
            if($this->pessoa_model->inserir($pessoa_dados)){
                $fisica_dados = $this->input->post();
                $fisica_dados['nascimento'] = $fisica_dados['nascimento']['ano'] . '-' . $fisica_dados['nascimento']['mes'] . '-' . $fisica_dados['nascimento']['dia'];
                $fisica_dados['pessoa'] = $this->pessoa_model->id_inserido();
                if($this->pessoa_fisica_model->inserir($fisica_dados)){
                    $json['estatus'] = 'sucesso';
                    $call['tipo'] = ALERTA_SUCESSO;
                    $call['mensagem'] = 'Cadastro efetuado com sucesso!';
                    if($pessoa_dados['email']!=NULL && $this->input->post('enviar_email')){
                        $this->load->library('email');
                        if(array_key_exists('email_suporte', $this->config->item('email_smtp'))){
                            $config =  $this->config->item('email_smtp')['email_suporte'];
                            $this->email->initialize($config);
                        }
                        $this->email->from($this->config->item('email_suporte'),$this->config->item('nome_fantasia'));
                        $this->email->to($pessoa_dados['email']);

                        $this->email->subject($this->config->item('nome_fantasia') . ' - Cadastro Efetuado');
                        $this->email->message($this->load->view('sistema/email_padrao/cadastro_cliente',array('senha'=>$senha,'nome'=>$pessoa_dados['nome']),TRUE));

                        $this->email->send();
                    }
                }else{
                    $this->pessoa_model->deletar($fisica_dados['pessoa']);
                }
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
    
    public function busca(){
        //$this->load->model('pessoa_model');
        $this->load->model('pessoa_fisica_model');
        
        $selecionar['select'] = array('pessoa_fisica.id','cpf','nome','email','nascimento','cep');
        $selecionar['join'] = array('pessoa p','p.id = pessoa_fisica.pessoa');
        if($this->pessoa_fisica_model->selecionar($selecionar)){
            $this->_add_data('lista_pessoas',$this->pessoa_fisica_model->registros());
        }
        $this->_view("Busca Pessoa Física",'busca',parent::RELATIVO_CONTROLE);
    }
}
