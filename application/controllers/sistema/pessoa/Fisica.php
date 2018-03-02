<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Fisica
 *
 * @author Thiago Moura
 */
class Fisica extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/pessoa/fisica','Pessoa Física','cadastro');
    }
    
    public function cadastro($id = NULL){
        $data = [
            'titulo' => 'Cadastro Pessoa Física',
            'sv_id' => $id
        ];
        $this->_get_formulario('sistema/pessoa/fisica/cadastro', $data);
    }
    
    public function consulta(){
        $data = [
            'titulo' => 'Consulta Pessoa Física'
        ];
        $this->_get_listagem('sistema/pessoa/fisica/listagem', $data);
    }
    
    protected function _get($data_form){
        $this->load->model('pessoa_fisica_model');

        $valor = $data_form['id'];
        $type = MSG_ERROR;
        $message = 'Dados inválidos!';
        $status_header = 404;
        $form = array();
        
        if($valor!=NULL){
            $selecionar['select'] = array('pessoa_fisica.id','p.id AS pessoa_id','cpf','p.nome AS nome',
                'email','nascimento','nacionalidade','naturalidade','estado_civil',
                'sexo','p.cep','en.uf AS uf','m.nome AS municipio',
                'b.nome AS bairro','l.nome AS logradouro','numero','p.complemento','resenha','p.ativo','en.complemento AS complemento2');
            $selecionar['join'] = array(
                array('pessoa p','pessoa_fisica.pessoa = p.id'),
                array('endereco en','en.cep = p.cep'),
                //array('estado e','e.uf = en.uf'),
                array('municipio m','m.id = en.municipio'),
                array('bairro b','b.id = en.bairro'),
                array('logradouro l','l.id = en.logradouro')
            );

            $selecionar['where']['pessoa_fisica.id'] = $valor;

            $selecionar['where']['p.ativo'] = 1;
            if($this->pessoa_fisica_model->selecionar($selecionar) && $this->pessoa_fisica_model->num_registros()===1){
                $form = $this->pessoa_fisica_model->registro();
                $nascimento = explode('-',$form['nascimento']);
                $form['nascimento'] = $nascimento[2] . '/' . $nascimento[1] . '/' . $nascimento[0];

                $this->load->model('telefone_model');
                $tel_sel = array();
                $tel_sel['where']['pessoa'] = $this->pessoa_fisica_model->campo('pessoa_id');
                if($this->telefone_model->selecionar($tel_sel)){
                    $telefones['id'] = array();
                    $telefones['ddd'] = array();
                    $telefones['telefone'] = array();
                    $telefones['tipo'] = array();
                    $telefones['operadora'] = array();
                    while($this->telefone_model->possui_proximo()){
                        $telefones['id'][] = $this->telefone_model->campo('id');
                        $telefones['ddd'][] = $this->telefone_model->campo('ddd');
                        $telefones['telefone'][] = $this->telefone_model->campo('telefone');
                        $telefones['tipo'][] = $this->telefone_model->campo('tipo');
                        $telefones['operadora'][] = $this->telefone_model->campo('operadora');
                    }
                    $form['telefones'] = $telefones;
                }
                $type = MSG_SUCCESS;
                $message = 'Registro encontrado com sucesso!';
                $status_header = 200;
            }else{
                $message = 'Nenhum registro encontrado!';
            }
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
    
    protected function _insert($data_form){
        $this->load->library('form_validation');
        $this->load->model('pessoa_model');
        $this->load->model('pessoa_fisica_model');
        
        $this->form_validation->set_data($data_form);
        $this->form_validation->set_rules('cpf', 'CPF',array(
                'trim','required','is_natural','exact_length[11]','is_unique[pessoa_fisica.cpf]',
                array('is_valid_cpf',array($this->pessoa_fisica_model,'cpf_valido'))
            ),
            array('is_valid_cpf' => 'O CPF digitado não é válido.')
        );
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|is_unique[pessoa.email]');
        $this->form_validation->set_rules('nascimento', 'Data Nascimento', 'trim|required|max_length[10]');
        $this->form_validation->set_rules('enviar_email', "Enviar Email", 'trim');
        //$this->form_validation->set_rules('nascimento[dia]', 'Dia Nascimento', 'trim|required|exact_length[2]');
        //$this->form_validation->set_rules('nascimento[mes]', 'Mes Nascimento', 'trim|required|exact_length[2]');
        //$this->form_validation->set_rules('nascimento[ano]', 'Ano Nascimento', 'trim|required|exact_length[4]');
        $this->form_validation->set_rules('nacionalidade', 'Nacionalidade', 'trim');
        $this->form_validation->set_rules('naturalidade', 'Naturalidade', 'trim');
        $this->form_validation->set_rules('estado_civil', 'Estado Civil', 'trim');
        $this->form_validation->set_rules('sexo', 'Sexo', 'trim|required|in_list[masculino,feminino]');
        $this->form_validation->set_rules('cep', 'CEP', 'trim|required|is_natural');
        $this->form_validation->set_rules('uf', 'Estado', 'trim|required');
        $this->form_validation->set_rules('municipio', 'Municipio', 'trim|required');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'trim');
        $this->form_validation->set_rules('numero', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim');
        $this->form_validation->set_rules('complemento2', 'Complemento2', 'trim');
        $this->form_validation->set_rules('telefones[id][]', 'Id Telefone', 'trim');
        $this->form_validation->set_rules('telefones[ddd][]', 'DDD', 'trim');
        $this->form_validation->set_rules('telefones[telefone][]', 'Número Telefone', 'trim');
        $this->form_validation->set_rules('telefones[operadora][]', 'Operadora', 'trim');
        $this->form_validation->set_rules('telefones[tipo][]', 'Tipo Telefone', 'trim');

        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        
        if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
            $this->load->helper('string');
            $this->load->model('endereco_model');
            
            if($this->endereco_model->consulta_cep($data_form['cep'])===FALSE){
                $cep_dados = $data_form;
                $cep_dados['complemento'] = $data_form['complemento2'];
                $this->endereco_model->salva_cep($cep_dados);
            }
            
            //Prepara dados para gravação na tabela pessoa
            $senha = random_string();//Gera uma senha aleatória
            $pessoa_dados = $data_form;
            $pessoa_dados['grupo'] = $this->config->item('grupo_padrao_cliente');
            $pessoa_dados['tipo'] = NIVEL_CLIENTE;
            $pessoa_dados['senha'] = hash($this->config->item('hash-senha'),$senha);
            $pessoa_dados['resenha'] = 1;
            $pessoa_dados['ativo'] = 1;
            if($this->pessoa_model->inserir($pessoa_dados)){
                
                //Prepara dados para gravação na tabela pessoa_fisica
                $fisica_dados = $data_form;
                $nascimento = explode('/', $fisica_dados['nascimento']);
                $fisica_dados['nascimento'] = $nascimento['2'] . '-' . $nascimento['1']  . '-' . $nascimento['0'] ;
                $fisica_dados['pessoa'] = $this->pessoa_model->id_inserido();
                if($this->pessoa_fisica_model->inserir($fisica_dados)){
                    
                    //Cadastra números de telefone para a pessoa
                    if($data_form['telefones']['id']){
                        $this->load->model('telefone_model');
                        $telefones = array();
                        //Converte os dados dos telefones em um array legivel pela função salvar_telefones
                        foreach($data_form['telefones']['id'] as $k => $id_tel){
                            $telefones[] = array(
                               // 'ddd' => $data_form['telefones']['ddd'][$k],
                                'telefone' => $data_form['telefones']['telefone'][$k],
                                'operadora' => $data_form['telefones']['operadora'][$k],
                                'tipo' =>$data_form['telefones']['tipo'][$k]
                            );
                        }
                        //Salva telefones e altera o telefone principal na tabela pessoa
                        $tel_principal = $this->telefone_model->salvar_telefones($telefones,$fisica_dados['pessoa']);
                        if(!empty($tel_principal)){
                            $this->pessoa_model->alterar(array('tel_principal'=>$tel_principal[0]),'id = ' . $fisica_dados['pessoa']);
                        }
                    }
                    
                    //Altera variavéis do alerta para mensagem de sucesso
                    $type = MSG_SUCCESS;
                    $message = 'Dados salvos com sucesso!';
                    $status_header = 200;
                    $form['id'] = $this->pessoa_fisica_model->id_inserido();
                    
                    //Envia email com a senha caso tenha algum email cadastrado
                    //if($pessoa_dados['email']!=NULL && $data_form['enviar_email']){
//                        $this->load->library('email');
//                        if(array_key_exists('email_suporte', $this->config->item('email_smtp'))){
//                            $config =  $this->config->item('email_smtp')['email_suporte'];
//                            $this->email->initialize($config);
//                        }
//                        $this->email->from($this->config->item('email_suporte'),$this->config->item('nome_fantasia'));
//                        $this->email->to($pessoa_dados['email']);
//
//                        $this->email->subject($this->config->item('nome_fantasia') . ' - Cadastro Efetuado');
//                        $this->email->message($this->load->view('sistema/email_padrao/cadastro_cliente',array('senha'=>$senha,'nome'=>$pessoa_dados['nome']),TRUE));
//
//                        $this->email->send();
                    //}
                }else{
                    //Deleta o registro na tabela pessoa caso falhe o insert na tabela pessoa_fisica
                    $this->pessoa_model->deletar($fisica_dados['pessoa']);
                }
            }
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
    
    protected function _update($data_form){
        $this->load->library('form_validation');
        $this->load->model('pessoa_model');
        $this->load->model('pessoa_fisica_model');
        
        $this->form_validation->set_data($data_form);
        $this->form_validation->set_rules('id_pessoa', 'Id Pessoa', 'trim');
        $this->form_validation->set_rules('cpf', 'CPF',array(
                'trim','required','is_natural','exact_length[11]',
                array('is_valid_cpf',array($this->pessoa_fisica_model,'cpf_valido'))
            ),
            array('is_valid_cpf' => 'O CPF digitado não é válido.')
        );
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('resenha', 'Resenha', 'trim');
        $this->form_validation->set_rules('ativo', 'Ativo', 'trim');
        $this->form_validation->set_rules('nascimento', 'Nascimento', 'trim|required|exact_length[10]');
        $this->form_validation->set_rules('nacionalidade', 'Nacionalidade', 'trim');
        $this->form_validation->set_rules('naturalidade', 'Naturalidade', 'trim');
        $this->form_validation->set_rules('estado_civil', 'Estado Civil', 'trim');
        $this->form_validation->set_rules('sexo', 'Sexo', 'trim|required|in_list[masculino,feminino]');
        $this->form_validation->set_rules('cep', 'CEP', 'trim|required|is_natural');
        $this->form_validation->set_rules('uf', 'Estado', 'trim');
        $this->form_validation->set_rules('municipio', 'Municipio', 'trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'trim');
        $this->form_validation->set_rules('numero', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim');
        $this->form_validation->set_rules('complemento2', 'Complemento2', 'trim');
        $this->form_validation->set_rules('telefones[id][]', 'Id Telefone', 'trim');
        $this->form_validation->set_rules('telefones[ddd][]', 'DDD', 'trim');
        $this->form_validation->set_rules('telefones[telefone][]', 'Número Telefone', 'trim');
        $this->form_validation->set_rules('telefones[operadora][]', 'Operadora', 'trim');
        $this->form_validation->set_rules('telefones[tipo][]', 'Tipo Telefone', 'trim');

        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        
        if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
            $this->load->helper('string');
            $this->load->model('endereco_model');
            
            if($this->endereco_model->consulta_cep($data_form['cep'])===FALSE){
                $cep_dados = $data_form;
                $cep_dados['complemento'] = $data_form['complemento2'];
                $this->endereco_model->salva_cep($cep_dados);
            }
            
            $id_pessoa = 0;
            $selecionar['select'] = array('pessoa');
            $selecionar['join'] = array(array('pessoa p','p.id = pessoa_fisica.pessoa'));
            $selecionar['where'] = 'cpf = ' . $data_form['cpf'];
            if($this->pessoa_fisica_model->selecionar($selecionar)){
                if($this->pessoa_fisica_model->num_registros() === 1){
                    $id_pessoa = $this->pessoa_fisica_model->campo('pessoa');
                }
            }
            if($id_pessoa > 0){
                //Prepara dados para gravação na tabela pessoa_fisica
                $fisica_dados = $data_form;
                $nascimento = explode('/',$fisica_dados['nascimento']);
                $fisica_dados['nascimento'] = $nascimento[2] . '-' . $nascimento[1] . '-' . $nascimento[0];
                //$fisica_dados['pessoa'] = $this->input->post('id_pessoa');
                //Altera os dados na tabela Pessoa Fisica
                $this->pessoa_fisica_model->alterar($fisica_dados,array('cpf'=>$data_form['cpf']));

                //Cadastra números de telefone para a pessoa
                $this->load->model('telefone_model');
                $telefones_old = array();
                $telefones_new = array();
                //Converte os dados dos telefones em um array legivel pela função salvar_telefones
                foreach($data_form['telefones']['id'] as $k => $id_tel){
                    $telefone = array(
                        //'ddd' => $data_form['telefones']['ddd'][$k],
                        'telefone' => $data_form['telefones']['telefone'][$k],
                        'operadora' => $data_form['telefones']['operadora'][$k],
                        'tipo' => $data_form['telefones']['tipo'][$k]
                    );
                    if($id_tel>0){
                        $telefone['id'] = $id_tel;
                        $telefones_old[] = $telefone;
                    }else{
                        $telefones_new[] = $telefone;
                    }
                }
                //Salva telefones
                $this->telefone_model->salvar_telefones($telefones_new,$id_pessoa);
                $this->telefone_model->alterar_telefones($telefones_old,$id_pessoa);

                //Prepara dados para gravação na tabela pessoa
                $senha = random_string();//Gera uma senha aleatória
                $pessoa_dados = $data_form;
                //$pessoa_dados['grupo'] = $this->config->item('grupo_padrao_cliente');
                //$pessoa_dados['tipo'] = Pessoa_model::CLIENTE;
                if($pessoa_dados['resenha']==1){
                    $pessoa_dados['senha'] = hash($this->config->item('hash-senha'),$senha);
                }
                //Alterar o telefone principal na tabela pessoa
//                if(!empty($tel_principal)){
//                    $pessoa_dados['tel_principal'] = $tel_principal[0];
//                }
                
                //Altera os dados na tabela Pessoa e
                //Envia email com a senha caso tenha algum email cadastrado
//                if($this->pessoa_model->alterar($pessoa_dados,array('id'=>$id_pessoa)) && 
//                        $pessoa_dados['email']!=NULL && $pessoa_dados['resenha']==1){
//                    $this->load->library('email');
//                    if(array_key_exists('email_suporte', $this->config->item('email_smtp'))){
//                        $config =  $this->config->item('email_smtp')['email_suporte'];
//                        $this->email->initialize($config);
//                    }
//                    $this->email->from($this->config->item('email_suporte'),$this->config->item('nome_fantasia'));
//                    $this->email->to($pessoa_dados['email']);
//
//                    $this->email->subject($this->config->item('nome_fantasia') . ' - Cadastro Efetuado');
//                    $this->email->message($this->load->view('sistema/email_padrao/cadastro_cliente',array('senha'=>$senha,'nome'=>$pessoa_dados['nome']),TRUE));
//
//                    $this->email->send();
//                }
                
                //Altera variavéis do alerta para mensagem de sucesso
                $type = MSG_SUCCESS;
                $message = 'Dados alterados com sucesso!';
                $status_header = 200;
                $form['id'] = $this->pessoa_fisica_model->id_inserido();
            }

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
