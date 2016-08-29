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
        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        $this->email->from('contato@dmoura.esy.es', "D'Moura");
        $this->email->to('ago10_mariano@yahoo.com.br');

        $this->email->subject($senha);
        $this->email->message($this->load->view('sistema/email_padrao/cadastro_cliente',array('senha'=>$senha,'nome'=>'Thiago Moura'),TRUE));

        $this->email->send();
        $this->_view("Teste outro",'teste',parent::RELATIVO_CONTROLE);
    }
}
