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
            $selecionar['select'] = array('pessoa_fisica.id','pessoa_fisica.idpessoa','cpf','p.nome AS nome','p.apelido',
                'nascimento','nacionalidade','naturalidade','estado_civil','sexo','conjuge','cnpj',
                'razao','telefone1','idoperadora1','telefone2','idoperadora2','telefone3','idoperadora3',
                'cargo','pessoa_fisica.cep','en.uf AS uf','m.nome AS municipio',
                'b.nome AS bairro','l.nome AS logradouro','numero','pessoa_fisica.complemento AS complemento2','p.ativo','en.complemento AS complemento');
            $selecionar['join'] = array(
                array('pessoa p','pessoa_fisica.idpessoa = p.id'),
                array('endereco en','en.cep = pessoa_fisica.cep'),
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

                // Lista endereços da pessoa
                $this->load->model('endereco_pessoa_model');
                $selecionar = [];
                $selecionar['select'] = ['endereco_pessoa.*','en.uf AS uf','m.nome AS municipio',
                    'b.nome AS bairro','l.nome AS logradouro','numero','endereco_pessoa.complemento AS complemento2',
                    'en.complemento AS complemento'];
                $selecionar['join'] = [
                    ['endereco en','en.cep = endereco_pessoa.cep'],
                    ['municipio m','m.id = en.municipio'],
                    ['bairro b','b.id = en.bairro'],
                    ['logradouro l','l.id = en.logradouro']
                ];
                $selecionar['where']['idpessoa'] = $this->pessoa_fisica_model->campo('idpessoa');
                if($this->endereco_pessoa_model->selecionar($selecionar)){
                    $lote = [
                        'id' => [],
                        'cep' => [],
                        'tipo' => [],
                        'numero' => [],
                        'complemento2' => [],
                        'uf' => [],
                        'municipio' => [],
                        'bairro' => [],
                        'logradouro' => [],
                        'complemento' => []
                    ];
                    while($this->endereco_pessoa_model->possui_proximo()){
                        $lote['id'][] = $this->endereco_pessoa_model->campo('id');
                        $lote['cep'][] = $this->endereco_pessoa_model->campo('cep');
                        $lote['tipo'][] = $this->endereco_pessoa_model->campo('tipo');
                        $lote['numero'][] = $this->endereco_pessoa_model->campo('numero');
                        $lote['complemento2'][] = $this->endereco_pessoa_model->campo('complemento2');
                        $lote['uf'][] = $this->endereco_pessoa_model->campo('uf');
                        $lote['municipio'][] = $this->endereco_pessoa_model->campo('municipio');
                        $lote['bairro'][] = $this->endereco_pessoa_model->campo('bairro');
                        $lote['logradouro'][] = $this->endereco_pessoa_model->campo('logradouro');
                        $lote['complemento'][] = $this->endereco_pessoa_model->campo('complemento');
                    }
                    $form['enderecos'] = $lote;
                }
                
                // Lista emails da pessoa
                $this->load->model('email_contato_model');
                $selecionar = [];
                $selecionar['where']['idpessoa'] = $this->pessoa_fisica_model->campo('idpessoa');
                if($this->email_contato_model->selecionar($selecionar)){
                    $lote = [
                        'id' => [],
                        'email' => [],
                        'descricao' => []
                    ];
                    while($this->email_contato_model->possui_proximo()){
                        $lote['id'][] = $this->email_contato_model->campo('id');
                        $lote['email'][] = $this->email_contato_model->campo('email');
                        $lote['descricao'][] = $this->email_contato_model->campo('descricao');
                    }
                    $form['emails'] = $lote;
                }
                
                // Lista telefones da pessoa
                $this->load->model('telefone_model');
                $selecionar = [];
                $selecionar['where']['idpessoa'] = $this->pessoa_fisica_model->campo('idpessoa');
                if($this->telefone_model->selecionar($selecionar)){
                    $lote = [
                        'id' => [],
                        'telefone' => [],
                        'idtipo' => [],
                        'idoperadora' => []
                    ];
                    while($this->telefone_model->possui_proximo()){
                        $lote['id'][] = $this->telefone_model->campo('id');
                        $lote['telefone'][] = $this->telefone_model->campo('telefone');
                        $lote['idtipo'][] = $this->telefone_model->campo('idtipo');
                        $lote['idoperadora'][] = $this->telefone_model->campo('idoperadora');
                    }
                    $form['telefones'] = $lote;
                }
                
                // Lista contatos cobrança da pessoa
                $this->load->model('contato_cobranca_model');
                $selecionar = [];
                $selecionar['select'] = ['contato_cobranca.*','en.uf AS uf','m.nome AS municipio',
                    'b.nome AS bairro','l.nome AS logradouro','numero','contato_cobranca.complemento AS complemento2',
                    'en.complemento AS complemento'];
                $selecionar['join'] = [
                    ['endereco en','en.cep = contato_cobranca.cep'],
                    ['municipio m','m.id = en.municipio'],
                    ['bairro b','b.id = en.bairro'],
                    ['logradouro l','l.id = en.logradouro']
                ];
                $selecionar['where']['idpessoafisica'] = $this->pessoa_fisica_model->campo('id');
                if($this->contato_cobranca_model->selecionar($selecionar)){
                    $lote = [
                        'id' => [],
                        'nome' => [],
                        'telefone' => [],
                        'idoperadora' => [],
                        'parentesco' => [],
                        'cep' => [],
                        'numero' => [],
                        'complemento2' => [],
                        'uf' => [],
                        'municipio' => [],
                        'bairro' => [],
                        'logradouro' => [],
                        'complemento' => []
                    ];
                    while($this->contato_cobranca_model->possui_proximo()){
                        $lote['id'][] = $this->contato_cobranca_model->campo('id');
                        $lote['nome'][] = $this->contato_cobranca_model->campo('nome');
                        $lote['telefone'][] = $this->contato_cobranca_model->campo('telefone');
                        $lote['idoperadora'][] = $this->contato_cobranca_model->campo('idoperadora');
                        $lote['parentesco'][] = $this->contato_cobranca_model->campo('parentesco');
                        $lote['cep'][] = $this->contato_cobranca_model->campo('cep');
                        $lote['numero'][] = $this->contato_cobranca_model->campo('numero');
                        $lote['complemento2'][] = $this->contato_cobranca_model->campo('complemento2');
                        $lote['uf'][] = $this->contato_cobranca_model->campo('uf');
                        $lote['municipio'][] = $this->contato_cobranca_model->campo('municipio');
                        $lote['bairro'][] = $this->contato_cobranca_model->campo('bairro');
                        $lote['logradouro'][] = $this->contato_cobranca_model->campo('logradouro');
                        $lote['complemento'][] = $this->contato_cobranca_model->campo('complemento');
                    }
                    $form['contato_cobranca'] = $lote;
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
        $this->form_validation->set_rules('apelido', 'Apelido', 'trim');
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('nascimento', 'Data Nascimento', 'trim|max_length[10]');
        $this->form_validation->set_rules('nacionalidade', 'Nacionalidade', 'trim');
        $this->form_validation->set_rules('naturalidade', 'Naturalidade', 'trim');
        $this->form_validation->set_rules('estado_civil', 'Estado Civil', 'trim');
        $this->form_validation->set_rules('sexo', 'Sexo', 'trim|required|in_list[masculino,feminino]');
        $this->form_validation->set_rules('conjuge', 'Conjuge', 'trim');
        $this->form_validation->set_rules('enderecos[id][]', 'Id Endereço', 'trim');
        $this->form_validation->set_rules('enderecos[tipo][]', 'Tipo Endereço', 'trim|required|in_list[residencial,comercial]');
        $this->form_validation->set_rules('enderecos[cep][]', 'CEP', 'trim|required');
        $this->form_validation->set_rules('enderecos[uf][]', 'Estado', 'trim|required');
        $this->form_validation->set_rules('enderecos[municipio][]', 'Municipio', 'trim|required');
        $this->form_validation->set_rules('enderecos[bairro][]', 'Bairro', 'trim|required');
        $this->form_validation->set_rules('enderecos[logradouro][]', 'Logradouro', 'trim|required');
        $this->form_validation->set_rules('enderecos[numero][]', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('enderecos[complemento][]', 'Complemento', 'trim');
        $this->form_validation->set_rules('enderecos[complemento2][]', 'Complemento2', 'trim');
        $this->form_validation->set_rules('emails[id][]', 'Id Email', 'trim');
        $this->form_validation->set_rules('emails[email][]', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('emails[descricao][]', 'Descrição', 'trim');
        $this->form_validation->set_rules('telefones[id][]', 'Id Telefone', 'trim');
        $this->form_validation->set_rules('telefones[telefone][]', 'Número Telefone', 'trim');
        $this->form_validation->set_rules('telefones[idoperadora][]', 'Operadora', 'trim');
        $this->form_validation->set_rules('telefones[idtipo][]', 'Tipo Telefone', 'trim');
        $this->form_validation->set_rules('contato_cobranca[id][]', 'Id Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[nome][]', 'Nome Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[parentesco][]', 'Parentesco Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[telefone][]', 'Telefone Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[idoperadora][]', 'Operadora Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[cep][]', 'CEP Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[uf][]', 'Estado Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[municipio][]', 'Cidade Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[bairro][]', 'Bairro Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[logradouro][]', 'Logradouro Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[numero][]', 'Número Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[complemento][]', 'Complemento Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[complemento2][]', 'Complemento Endereço Contato Cobrança', 'trim');
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'trim');
        $this->form_validation->set_rules('razao', 'Razão Social', 'trim');
        $this->form_validation->set_rules('cargo', 'Cargo', 'trim');
        $this->form_validation->set_rules('telefone1', 'Telefone', 'trim');
        $this->form_validation->set_rules('idoperadora1', 'Operadora', 'trim');
        $this->form_validation->set_rules('telefone2', 'Telefone', 'trim');
        $this->form_validation->set_rules('idoperadora2', 'Operadora', 'trim');
        $this->form_validation->set_rules('telefone3', 'Telefone', 'trim');
        $this->form_validation->set_rules('idoperadora3', 'Operadora', 'trim');
        $this->form_validation->set_rules('cep', 'CEP', 'trim');
        $this->form_validation->set_rules('uf', 'Estado', 'trim');
        $this->form_validation->set_rules('municipio', 'Municipio', 'trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'trim');
        $this->form_validation->set_rules('numero', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim');
        $this->form_validation->set_rules('complemento2', 'Complemento2', 'trim');

        $type = MSG_ERROR;
        $message = 'Falha ao salvar dados!';
        $status_header = 400;
        $form = array();
        
        if ($this->form_validation->run() == FALSE) {
            $message = 'Campos com preenchimento incorreto!';
            $form = $this->form_validation->error_array();
        } else {
//            $this->load->helper('string');
            $this->load->model('endereco_model');
            
            if($this->endereco_model->consulta_cep($data_form['cep'])===FALSE){
                $cep_dados = $data_form;
                $this->endereco_model->salva_cep($cep_dados);
            }
            
            //Prepara dados para gravação na tabela pessoa
            $pessoa_dados['nome'] = $data_form['nome'];
            $pessoa_dados['apelido'] = $data_form['apelido'];
            $pessoa_dados['ativo'] = 1;
            if($this->pessoa_model->inserir($pessoa_dados)){
                
                //Prepara dados para gravação na tabela pessoa_fisica
                $fisica_dados = $data_form;
                $nascimento = explode('/', $fisica_dados['nascimento']);
                $fisica_dados['nascimento'] = $nascimento['2'] . '-' . $nascimento['1']  . '-' . $nascimento['0'] ;
                $fisica_dados['idpessoa'] = $this->pessoa_model->id_inserido();
                if($this->pessoa_fisica_model->inserir($fisica_dados)){
                    $form['id'] = $this->pessoa_fisica_model->id_inserido();
                    
                    //Cadastra endereços da pessoa
                    if(key_exists('enderecos', $data_form) && key_exists('id', $data_form['enderecos']) && $data_form['enderecos']['id']){
                        $this->load->model('endereco_pessoa_model');
                        $lote = [];
                        // Cria lotes de registros para inserção
                        foreach($data_form['enderecos']['id'] as $k => $d){
                            if($this->endereco_model->consulta_cep($data_form['enderecos']['cep'][$k])===FALSE){
                                $cep_dados = [
                                    'cep' => $data_form['enderecos']['cep'][$k],
                                    'uf' => $data_form['enderecos']['uf'][$k],
                                    'municipio' => $data_form['enderecos']['municipio'][$k],
                                    'bairro' => $data_form['enderecos']['bairro'][$k],
                                    'logradouro' => $data_form['enderecos']['logradouro'][$k],
                                    'complemento' => $data_form['enderecos']['complemento'][$k]
                                ];
                                $this->endereco_model->salva_cep($cep_dados);
                            }
                            $lote[] = [
                                'destinatario' => $data_form['nome'],
                                'cep' => $data_form['enderecos']['cep'][$k],
                                'tipo' => $data_form['enderecos']['tipo'][$k],
                                'numero' => $data_form['enderecos']['numero'][$k],
                                'complemento' => $data_form['enderecos']['complemento2'][$k],
                                'idpessoa' => $fisica_dados['idpessoa']
                            ];
                        }
                        //Salva lote de registros no banco de dados
                        $this->endereco_pessoa_model->inserir_lote($lote);
                    }
                    
                    //Cadastra emails da pessoa
                    if(key_exists('emails', $data_form) && key_exists('id', $data_form['emails']) && $data_form['emails']['id']){
                        $this->load->model('email_contato_model');
                        $lote = [];
                        // Cria lotes de registros para inserção
                        foreach($data_form['emails']['id'] as $k => $d){
                            $lote[] = [
                                'email' => $data_form['emails']['email'][$k],
                                'descricao' => $data_form['emails']['descricao'][$k],
                                'idpessoa' => $fisica_dados['idpessoa']
                            ];
                        }
                        //Salva lote de registros no banco de dados
                        $this->email_contato_model->inserir_lote($lote);
                    }
                    
                    //Cadastra números de telefone para a pessoa
                    if(key_exists('telefones', $data_form) && key_exists('id', $data_form['telefones']) && $data_form['telefones']['id']){
                        $this->load->model('telefone_model');
                        $telefones = [];
                        //Converte os dados dos telefones em um array legivel pela função salvar_telefones
                        foreach($data_form['telefones']['id'] as $k => $d){
                            $telefones[] = [
                                'telefone' => $data_form['telefones']['telefone'][$k],
                                'idoperadora' => $data_form['telefones']['idoperadora'][$k],
                                'idtipo' =>$data_form['telefones']['idtipo'][$k]
                            ];
                        }
                        //Salva telefones e altera o telefone principal na tabela pessoa
                        $this->telefone_model->salvar_telefones($telefones,$fisica_dados['idpessoa']);
                    }
                    
                    //Cadastra contatos de cobrança da pessoa
                    if(key_exists('contato_cobranca', $data_form) && key_exists('id', $data_form['contato_cobranca']) && $data_form['contato_cobranca']['id']){
                        $this->load->model('contato_cobranca_model');
                        $lote = array();
                        // Cria lotes de registros para inserção
                        foreach($data_form['contato_cobranca']['id'] as $k => $d){
                            if($this->endereco_model->consulta_cep($data_form['contato_cobranca']['cep'][$k])===FALSE){
                                $cep_dados = [
                                    'cep' => $data_form['contato_cobranca']['cep'][$k],
                                    'uf' => $data_form['contato_cobranca']['uf'][$k],
                                    'municipio' => $data_form['contato_cobranca']['municipio'][$k],
                                    'bairro' => $data_form['contato_cobranca']['bairro'][$k],
                                    'logradouro' => $data_form['contato_cobranca']['logradouro'][$k],
                                    'complemento' => $data_form['contato_cobranca']['complemento'][$k]
                                ];
                                $this->endereco_model->salva_cep($cep_dados);
                            }
                            $lote[] = [
                                'nome' => $data_form['contato_cobranca']['nome'][$k],
                                'parentesco' => $data_form['contato_cobranca']['parentesco'][$k],
                                'telefone' => $data_form['contato_cobranca']['telefone'][$k],
                                'idoperadora' => $data_form['contato_cobranca']['idoperadora'][$k],
                                'cep' => $data_form['contato_cobranca']['cep'][$k],
                                'numero' => $data_form['contato_cobranca']['numero'][$k],
                                'complemento' => $data_form['contato_cobranca']['complemento2'][$k],
                                'idpessoafisica' => $form['id']
                            ];
                        }
                        //Salva lote de registros no banco de dados
                        $this->contato_cobranca_model->inserir_lote($lote);
                    }
                    
                    //Altera variavéis do alerta para mensagem de sucesso
                    $type = MSG_SUCCESS;
                    $message = 'Dados salvos com sucesso!';
                    $status_header = 200;
                    $form['id'] = $this->pessoa_fisica_model->id_inserido();
                    
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
        $this->form_validation->set_rules('id', 'Id Pessoa Física', 'trim|required');
        $this->form_validation->set_rules('cpf', 'CPF',array(
                'trim','required','is_natural','exact_length[11]',
                array('is_valid_cpf',array($this->pessoa_fisica_model,'cpf_valido'))
            ),
            array('is_valid_cpf' => 'O CPF digitado não é válido.')
        );
        $this->form_validation->set_rules('apelido', 'Apelido', 'trim');
        $this->form_validation->set_rules('nome', 'Nome', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('nascimento', 'Nascimento', 'trim|exact_length[10]');
        $this->form_validation->set_rules('nacionalidade', 'Nacionalidade', 'trim');
        $this->form_validation->set_rules('naturalidade', 'Naturalidade', 'trim');
        $this->form_validation->set_rules('estado_civil', 'Estado Civil', 'trim');
        $this->form_validation->set_rules('sexo', 'Sexo', 'trim|required|in_list[masculino,feminino]');
        $this->form_validation->set_rules('conjuge', 'Conjuge', 'trim');
        $this->form_validation->set_rules('enderecos[id][]', 'Id Endereço', 'trim');
        $this->form_validation->set_rules('enderecos[tipo][]', 'Tipo Endereço', 'trim|required|in_list[residencial,comercial]');
        $this->form_validation->set_rules('enderecos[cep][]', 'CEP', 'trim|required');
        $this->form_validation->set_rules('enderecos[uf][]', 'Estado', 'trim|required');
        $this->form_validation->set_rules('enderecos[municipio][]', 'Municipio', 'trim|required');
        $this->form_validation->set_rules('enderecos[bairro][]', 'Bairro', 'trim|required');
        $this->form_validation->set_rules('enderecos[logradouro][]', 'Logradouro', 'trim|required');
        $this->form_validation->set_rules('enderecos[numero][]', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('enderecos[complemento][]', 'Complemento', 'trim');
        $this->form_validation->set_rules('enderecos[complemento2][]', 'Complemento2', 'trim');
        $this->form_validation->set_rules('emails[id][]', 'Id Email', 'trim');
        $this->form_validation->set_rules('emails[email][]', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('emails[descricao][]', 'Descrição', 'trim');
        $this->form_validation->set_rules('contato_cobranca[id][]', 'Id Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[nome][]', 'Nome Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[parentesco][]', 'Parentesco Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[telefone][]', 'Telefone Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[idoperadora][]', 'Operadora Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[cep][]', 'CEP Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[uf][]', 'Estado Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[municipio][]', 'Cidade Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[bairro][]', 'Bairro Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[logradouro][]', 'Logradouro Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[numero][]', 'Número Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[complemento][]', 'Complemento Contato Cobrança', 'trim');
        $this->form_validation->set_rules('contato_cobranca[complemento2][]', 'Complemento Endereço Contato Cobrança', 'trim');
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'trim');
        $this->form_validation->set_rules('razao', 'Razão Social', 'trim');
        $this->form_validation->set_rules('cargo', 'Cargo', 'trim');
        $this->form_validation->set_rules('telefone1', 'Telefone', 'trim');
        $this->form_validation->set_rules('idoperadora1', 'Operadora', 'trim');
        $this->form_validation->set_rules('telefone2', 'Telefone', 'trim');
        $this->form_validation->set_rules('idoperadora2', 'Operadora', 'trim');
        $this->form_validation->set_rules('telefone3', 'Telefone', 'trim');
        $this->form_validation->set_rules('idoperadora3', 'Operadora', 'trim');
        $this->form_validation->set_rules('cep', 'CEP', 'trim|is_natural');
        $this->form_validation->set_rules('uf', 'Estado', 'trim');
        $this->form_validation->set_rules('municipio', 'Municipio', 'trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'trim');
        $this->form_validation->set_rules('numero', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim');
        $this->form_validation->set_rules('complemento2', 'Complemento2', 'trim');
        $this->form_validation->set_rules('telefones[id][]', 'Id Telefone', 'trim');
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
            $this->load->model('endereco_model');
            
            if($this->endereco_model->consulta_cep($data_form['cep'])===FALSE){
                $this->endereco_model->salva_cep($data_form);
            }
            
            $id_pessoa = 0;
            $selecionar['select'] = array('idpessoa');
            $selecionar['join'] = array(array('pessoa p','p.id = pessoa_fisica.idpessoa'));
            $selecionar['where'] = 'cpf = ' . $data_form['cpf'];
            if($this->pessoa_fisica_model->selecionar($selecionar)){
                if($this->pessoa_fisica_model->num_registros() === 1){
                    $id_pessoa = $this->pessoa_fisica_model->campo('idpessoa');
                }
            }
            //Prepara dados para gravação na tabela pessoa
            $pessoa_dados = [
                'nome' => $data_form['nome'],
                'apelido' => $data_form['apelido']
            ];
            if($id_pessoa > 0 && $this->pessoa_model->alterar($pessoa_dados,array('id'=>$id_pessoa))){
                
                //Prepara dados para gravação na tabela pessoa_fisica
                $fisica_dados = $data_form;
                $nascimento = explode('/',$fisica_dados['nascimento']);
                $fisica_dados['nascimento'] = $nascimento[2] . '-' . $nascimento[1] . '-' . $nascimento[0];
                
                //Altera os dados na tabela Pessoa Fisica
                if($this->pessoa_fisica_model->alterar($fisica_dados,array('cpf'=>$data_form['cpf']))){
                    $form['id'] = $data_form['id'];
                    
                    //Cadastra endereços da pessoa
                    if(key_exists('enderecos', $data_form) && key_exists('id', $data_form['enderecos']) && $data_form['enderecos']['id']){
                        $this->load->model('endereco_pessoa_model');
                        $lote_altera = [];
                        $lote_insere = [];
                        $condition = ['where_not_in'=>['id'=>[]],'where'=>['idpessoa'=>$id_pessoa]];
                        // Cria lotes de registros para inserção
                        foreach($data_form['enderecos']['id'] as $k => $id){
                            if($this->endereco_model->consulta_cep($data_form['enderecos']['cep'][$k])===FALSE){
                                $cep_dados = [
                                    'cep' => $data_form['enderecos']['cep'][$k],
                                    'uf' => $data_form['enderecos']['uf'][$k],
                                    'municipio' => $data_form['enderecos']['municipio'][$k],
                                    'bairro' => $data_form['enderecos']['bairro'][$k],
                                    'logradouro' => $data_form['enderecos']['logradouro'][$k],
                                    'complemento' => $data_form['enderecos']['complemento'][$k]
                                ];
                                $this->endereco_model->salva_cep($cep_dados);
                            }
                            $lote = [
                                'destinatario' => $data_form['nome'],
                                'cep' => $data_form['enderecos']['cep'][$k],
                                'tipo' => $data_form['enderecos']['tipo'][$k],
                                'numero' => $data_form['enderecos']['numero'][$k],
                                'complemento' => $data_form['enderecos']['complemento2'][$k],
                                'idpessoa' => $id_pessoa
                            ];
                            if($id>0){
                                $condition['where_not_in']['id'][] = $id;
                                $lote['id'] = $id;
                                $lote_altera[] = $lote;
                            }else{
                                $lote_insere[] = $lote;
                            }
                        }
                        //Salva lote de registros no banco de dados
                        $this->endereco_pessoa_model->deletar_condicional($condition);
                        $this->endereco_pessoa_model->inserir_lote($lote_insere);
                        $this->endereco_pessoa_model->alterar_lote($lote_altera,'id');
                    }
                    
                    //Cadastra emails da pessoa
                    if(key_exists('emails', $data_form) && key_exists('id', $data_form['emails']) && $data_form['emails']['id']){
                        $this->load->model('email_contato_model');
                        $lote_altera = [];
                        $lote_insere = [];
                        $condition = ['where_not_in'=>['id'=>[]],'where'=>['idpessoa'=>$id_pessoa]];
                        // Cria lotes de registros para inserção
                        foreach($data_form['emails']['id'] as $k => $id){
                            $lote = [
                                'email' => $data_form['emails']['email'][$k],
                                'descricao' => $data_form['emails']['descricao'][$k],
                                'idpessoa' => $id_pessoa
                            ];
                            if($id>0){
                                $condition['where_not_in']['id'][] = $id;
                                $lote['id'] = $id;
                                $lote_altera[] = $lote;
                            }else{
                                $lote_insere[] = $lote;
                            }
                        }
                        //Salva lote de registros no banco de dados
                        $this->email_contato_model->deletar_condicional($condition);
                        $this->email_contato_model->inserir_lote($lote_insere);
                        $this->email_contato_model->alterar_lote($lote_altera,'id');
                    }
                    
                    //Cadastra números de telefone para a pessoa
                    if(key_exists('telefones', $data_form) && key_exists('id', $data_form['telefones']) && $data_form['telefones']['id']){
                        $this->load->model('telefone_model');
                        $lote_altera = [];
                        $lote_insere = [];
                        $condition = ['where_not_in'=>['id'=>[]],'where'=>['idpessoa'=>$id_pessoa]];
                        //Converte os dados dos telefones em um array legivel pela função salvar_telefones
                        foreach($data_form['telefones']['id'] as $k => $id){
                            $lote = [
                                'telefone' => $data_form['telefones']['telefone'][$k],
                                'idoperadora' => $data_form['telefones']['idoperadora'][$k],
                                'idtipo' => $data_form['telefones']['idtipo'][$k]
                            ];
                            if($id>0){
                                $condition['where_not_in']['id'][] = $id;
                                $lote['id'] = $id;
                                $lote_altera[] = $lote;
                            }else{
                                $lote_insere[] = $lote;
                            }
                        }
                        //Salva telefones
                        $this->telefone_model->deletar_condicional($condition);
                        $this->telefone_model->salvar_telefones($lote_insere,$id_pessoa);
                        $this->telefone_model->alterar_telefones($lote_altera,$id_pessoa);
                    }
                    
                    //Cadastra contatos de cobrança da pessoa
                    if(key_exists('contato_cobranca', $data_form) && key_exists('id', $data_form['contato_cobranca']) && $data_form['contato_cobranca']['id']){
                        $this->load->model('contato_cobranca_model');
                        $lote_altera = [];
                        $lote_insere = [];
                        $condition = ['where_not_in'=>['id'=>[]],'where'=>['idpessoafisica'=>$form['id']]];
                        // Cria lotes de registros para inserção
                        foreach($data_form['contato_cobranca']['id'] as $k => $id){
                            if($this->endereco_model->consulta_cep($data_form['contato_cobranca']['cep'][$k])===FALSE){
                                $cep_dados = [
                                    'cep' => $data_form['contato_cobranca']['cep'][$k],
                                    'uf' => $data_form['contato_cobranca']['uf'][$k],
                                    'municipio' => $data_form['contato_cobranca']['municipio'][$k],
                                    'bairro' => $data_form['contato_cobranca']['bairro'][$k],
                                    'logradouro' => $data_form['contato_cobranca']['logradouro'][$k],
                                    'complemento' => $data_form['contato_cobranca']['complemento'][$k]
                                ];
                                $this->endereco_model->salva_cep($cep_dados);
                            }
                            $lote = [
                                'nome' => $data_form['contato_cobranca']['nome'][$k],
                                'parentesco' => $data_form['contato_cobranca']['parentesco'][$k],
                                'telefone' => $data_form['contato_cobranca']['telefone'][$k],
                                'idoperadora' => $data_form['contato_cobranca']['idoperadora'][$k],
                                'cep' => $data_form['contato_cobranca']['cep'][$k],
                                'numero' => $data_form['contato_cobranca']['numero'][$k],
                                'complemento' => $data_form['contato_cobranca']['complemento2'][$k],
                                'idpessoafisica' => $form['id']
                            ];
                            if($id>0){
                                $condition['where_not_in']['id'][] = $id;
                                $lote['id'] = $id;
                                $lote_altera[] = $lote;
                            }else{
                                $lote_insere[] = $lote;
                            }
                        }
                        //Salva lote de registros no banco de dados
                        $this->contato_cobranca_model->deletar_condicional($condition);
                        $this->contato_cobranca_model->inserir_lote($lote_insere);
                        $this->contato_cobranca_model->alterar_lote($lote_altera,'id');
                    }
                    
                    //Altera variavéis do alerta para mensagem de sucesso
                    $type = MSG_SUCCESS;
                    $message = 'Dados alterados com sucesso!';
                    $status_header = 200;
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
}