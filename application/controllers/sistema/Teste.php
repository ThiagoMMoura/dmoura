<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Controle de testes
 *
 * @author Thiago Moura
 */
class Teste extends MY_Controller{
    
    public function __construct() {
        parent::__construct('sistema/teste',TRUE);
    }
    
    public function index(){
        $this->_add_data('nome',$this->load->view('sistema/email_padrao/cadastro_cliente',array('senha'=>'XhjbheruY','nome'=>'Thiago Moura'),TRUE));
        $this->_view("Teste index",'teste',parent::RELATIVO_CONTROLE);
    }
    
    public function formulario(){
        $this->_add_body('formulario',parent::RELATIVO_CONTROLE);
        $this->_view('Formulario');
    }
    
    public function calendario(){
        $prefs = array(
            'month_type'   => 'long',
            'day_type'     => 'long',
            'show_next_prev'  => TRUE,
            'next_prev_url'   => base_url($this->_obter_caminho_controle().'/calendario/')
        );
        $this->load->library('calendar',$prefs);
        $data = array(
            3  => 'http://example.com/news/article/2006/06/03/',
            7  => 'http://example.com/news/article/2006/06/07/',
            13 => 'http://example.com/news/article/2006/06/13/',
            26 => 'http://example.com/news/article/2006/06/26/'
        );
        $this->_add_data('nome', $this->calendar->generate($this->uri->segment(4), $this->uri->segment(5),$data));
        
        $this->_view("Teste outro",'teste',parent::RELATIVO_CONTROLE);
    }
    
    public function senha($senha){
        $senha = md5(trim($senha));
        $this->_add_data('nome', $senha);
        $this->_view("Teste outro",'teste',parent::RELATIVO_CONTROLE);
    }
    
    public function gerar_string(){
        $this->load->helper('string');
        $senha = random_string('alnum', 8);
        $this->_add_data('nome',$senha);
        
        $this->load->library('email');
        $config['smtp_host'] = 'mx1.hostinger.com.br';
        $config['smtp_user'] = 'html';
        $config['smtp_pass'] = 'html';
        //$config['smtp_port'] = 'html';
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $this->email->from('contato@dmoura.esy.es', "D'Moura");
        $this->email->to('ago10_mariano@yahoo.com.br');

        $this->email->subject($senha);
        $this->email->message($this->load->view('sistema/email_padrao/cadastro_cliente',array('senha'=>$senha,'nome'=>'Thiago Moura'),TRUE));

        $this->email->send();
        $this->_view("Teste outro",'teste',parent::RELATIVO_CONTROLE);
    }
    
    public function post_print(){
        echo print_r($this->input->post(),TRUE);
    }
    
    public function cadastro_telefones(){
        $this->load->model('tipo_telefone_model');
        $this->load->model('operadora_telefone_model');
        $this->_add_data('tipos_telefone',$this->tipo_telefone_model->obter_id_tipo());
        $this->_add_data('operadoras_telefone',$this->operadora_telefone_model->obter_id_operadora());
        $this->_view("Cadastro Telefone - Teste",'telefone',parent::RELATIVO_CONTROLE);
    }
    
    public function salvar_telefone(){
        //Declara variavéis para alerta, com valores padrões em caso de erro
        $call['tipo'] = ALERTA_ERRO;
        $call['titulo'] = '';
        $call['mensagem'] = 'Falha ao salvar dados!';
        $call['fechavel'] = TRUE;
        //$json['estatus'] = 'falha';

        $this->load->model('telefone_model');
        $this->load->model('pessoa_model');
        $telefones = array();
        //Converte os dados dos telefones em um array legivel pela função salvar_telefones
        foreach($this->input->post('id_tel') as $id_tel){
            $telefones[] = array(
                'ddd' => $this->input->post('ddd')[$id_tel],
                'telefone' => $this->input->post('numero_telefone')[$id_tel],
                'operadora' => $this->input->post('operadora')[$id_tel],
                'tipo' =>$this->input->post('tipo_telefone')[$id_tel]
            );
        }
        //Salva telefones e altera o telefone principal na tabela pessoa
        $tel_principal = $this->telefone_model->salvar_telefones($telefones,$this->input->post('pessoa'));
        if($tel_principal!==0){
            $call['tipo'] = ALERTA_INFO;
            $call['mensagem'] = 'Telefone principal: ' . $tel_principal . '. Id Pessoa: ' . $this->input->post('pessoa');
            if($this->pessoa_model->alterar(array('tel_principal'=>$tel_principal),'id = ' . $this->input->post('pessoa'))){
                $call['titulo'] = 'Sucesso';
            }
        }
        $this->_add_data('_callout',$call);
        $this->cadastro_telefones();
    }
}
