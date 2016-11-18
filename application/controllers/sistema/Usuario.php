<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of usuario
 *
 * @author Thiago Moura
 */
class Usuario extends MY_Controller{
	public function __construct() {
        parent::__construct('sistema/usuario',TRUE);
    }
	
	public function alterar_senha(){
		$this->_view("Alterar Senha",'alterar_senha',parent::RELATIVO_CONTROLE);
	}
	
	public function salvar_senha(){
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('senha_atual', 'Senha Atual', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('senha_nova', 'Nova Senha', 'trim|required|min_length[6]|differs[senha_atual]');
        $this->form_validation->set_rules('senha_confirma', 'Confirma Senha', 'trim|required|min_length[6]|matches[senha_nova]');
		if ($this->form_validation->run() == FALSE) {
            $this->alterar_senha();
        } else {
			//Declara variavéis para alerta, com valores padrões em caso de erro
            $call['tipo'] = ALERTA_ERRO;
            $call['titulo'] = '';
            $call['mensagem'] = 'Falha ao salvar nova senha!';
            $call['fechavel'] = TRUE;
            $json['estatus'] = 'falha';
			
			$this->load->model('pessoa_model');
			$dados = $this->input->post(array('nova_senha','senha_atual'));
			$dados['id'] = $this->session->id;
			if($this->pessoa_model->alterar_senha($dados)){
				//Altera variavéis do alerta para mensagem de sucesso
				$json['estatus'] = 'sucesso';
				$call['tipo'] = ALERTA_SUCESSO;
				$call['mensagem'] = 'Senha alterada com sucesso!';
			}
			
			if($this->input->is_ajax_request()){
                $json['callout'] = $call;
                echo json_encode($json);
            }else{
                $this->_add_data('_callout',$call);
                if($call['tipo'] === ALERTA_SUCESSO){
                    
                }else{
                    $this->alterar_senha();
                }
            }
		}
	}
}
