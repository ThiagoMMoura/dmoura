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
//        $this->_add_data('estados',$this->estado_model->obter_uf_estado());
//        $this->_add_data('tipos_telefone',$this->tipo_telefone_model->obter_id_tipo());
//        $this->_add_data('operadoras_telefone',$this->operadora_telefone_model->obter_id_operadora());
//        $this->_view("Cadastro Pessoa Física",'cadastro',parent::RELATIVO_CONTROLE);
		$data = [
			'titulo' => 'Cadastro Pessoa Física',
			'estados' => $this->estado_model->obter_uf_estado(),
			'tipos_telefone' => $this->tipo_telefone_model->obter_id_tipo(),
			'operadoras_telefone' => $this->operadora_telefone_model->obter_id_operadora()
		];
		$this->twig->display('sistema/pessoa/fisica/cadastro', $data);
    }
    
    public function salvar(){
//        if($this->input->post('salvar')===NULL){
//            redirect('sistema/pessoa/fisica/cadastro');
//        }
        $this->load->library('form_validation');
        $this->load->model('pessoa_model');
        $this->load->model('pessoa_fisica_model');
        
        $this->form_validation->set_rules('cpf', 'CPF',array(
                'trim','required','is_natural','exact_length[11]','is_unique[pessoa_fisica.cpf]',
                array('is_valid_cpf',array($this->pessoa_fisica_model,'cpf_valido'))
            ),
            array('is_valid_cpf' => 'O CPF digitado não é válido.')
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
        $this->form_validation->set_rules('uf', 'Estado', 'trim');
        $this->form_validation->set_rules('municipio', 'Municipio', 'trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'trim');
        $this->form_validation->set_rules('numero', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim');
        $this->form_validation->set_rules('complemento2', 'Complemento2', 'trim');
        $this->form_validation->set_rules('id_tel[]', 'Id Telefone', 'trim');
        $this->form_validation->set_rules('ddd[]', 'DDD', 'trim');
        $this->form_validation->set_rules('numero_telefone[]', 'Número Telefone', 'trim');
        $this->form_validation->set_rules('operadora[]', 'Operadora', 'trim');
        $this->form_validation->set_rules('tipo_telefone[]', 'Tipo Telefone', 'trim');

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
            
            //Declara variavéis para alerta, com valores padrões em caso de erro
            $call['tipo'] = ALERTA_ERRO;
            $call['titulo'] = '';
            $call['mensagem'] = 'Falha ao salvar dados!';
            $call['fechavel'] = TRUE;
            $json['estatus'] = 'falha';
            
            //Prepara dados para gravação na tabela pessoa
            $senha = random_string();//Gera uma senha aleatória
            $pessoa_dados = $this->input->post();
            $pessoa_dados['grupo'] = $this->config->item('grupo_padrao_cliente');
            $pessoa_dados['tipo'] = Pessoa_model::CLIENTE;
            $pessoa_dados['senha'] = hash($this->config->item('hash-senha'),$senha);
            $pessoa_dados['resenha'] = 1;
            $pessoa_dados['ativo'] = 1;
            if($this->pessoa_model->inserir($pessoa_dados)){
                
                //Prepara dados para gravação na tabela pessoa_fisica
                $fisica_dados = $this->input->post();
                $fisica_dados['nascimento'] = $fisica_dados['nascimento']['ano'] . '-' . $fisica_dados['nascimento']['mes'] . '-' . $fisica_dados['nascimento']['dia'];
                $fisica_dados['pessoa'] = $this->pessoa_model->id_inserido();
                if($this->pessoa_fisica_model->inserir($fisica_dados)){
                    
                    //Cadastra números de telefone para a pessoa
                    $this->load->model('telefone_model');
                    $telefones = array();
                    //Converte os dados dos telefones em um array legivel pela função salvar_telefones
                    foreach($this->input->post('id_tel') as $k => $id_tel){
                        $telefones[] = array(
                            'ddd' => $this->input->post('ddd')[$k],
                            'telefone' => $this->input->post('numero_telefone')[$k],
                            'operadora' => $this->input->post('operadora')[$k],
                            'tipo' =>$this->input->post('tipo_telefone')[$k]
                        );
                    }
                    //Salva telefones e altera o telefone principal na tabela pessoa
                    $tel_principal = $this->telefone_model->salvar_telefones($telefones,$fisica_dados['pessoa']);
                    if(!empty($tel_principal)){
                        $this->pessoa_model->alterar(array('tel_principal'=>$tel_principal[0]),'id = ' . $fisica_dados['pessoa']);
                    }
                    
                    //Altera variavéis do alerta para mensagem de sucesso
                    $json['estatus'] = 'sucesso';
                    $call['tipo'] = ALERTA_SUCESSO;
                    $call['mensagem'] = 'Cadastro efetuado com sucesso!';
                    
                    //Envia email com a senha caso tenha algum email cadastrado
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
                    //Deleta o registro na tabela pessoa caso falhe o insert na tabela pessoa_fisica
                    $this->pessoa_model->deletar($fisica_dados['pessoa']);
                }
            }
            if($this->input->is_ajax_request()){
                $json['callout'] = $call;
				$json['fieldserror'] = $this->form_validation->error_array();
                echo json_encode($json);
            }else{
                $this->_add_data('_callout',$call);
                if($call['tipo'] === ALERTA_SUCESSO){
                    $this->editar();
                }else{
                    $this->cadastro();
                }
            }
        }
    }
    
    public function busca(){
        $this->load->model('pessoa_fisica_model');
        
        $selecionar['select'] = array('pessoa_fisica.id','cpf','nome','email','DATE_FORMAT(nascimento,"%d/%m/%Y") AS nascimento','cep','CONCAT("(",`t`.`ddd`,") ",`t`.`telefone`) AS telefone');
        $selecionar['join'] = array(array('pessoa p','p.id = pessoa_fisica.pessoa'),array('telefone t','p.tel_principal = t.id'));
        if($this->pessoa_fisica_model->selecionar($selecionar)){
            $this->_add_data('lista_pessoas',$this->pessoa_fisica_model->registros());
        }
        $this->_view("Busca Pessoa Física",'busca',parent::RELATIVO_CONTROLE);
    }
    
    public function editar($valor = '',$is_cpf = TRUE){
        $this->load->model('pessoa_fisica_model');
        $this->load->model('estado_model');
        $this->load->model('tipo_telefone_model');
        $this->load->model('operadora_telefone_model');
        $this->_add_data('estados',$this->estado_model->obter_uf_estado());
        $this->_add_data('tipos_telefone',$this->tipo_telefone_model->obter_id_tipo());
        $this->_add_data('operadoras_telefone',$this->operadora_telefone_model->obter_id_operadora());
        if($valor==NULL){
            if($this->input->post('cpf')!=NULL){
                $valor = $this->input->post('cpf');
            }else{
                $valor = $this->input->post('id');
                $is_cpf = FALSE;
            }
        }
        if($valor!=NULL){
            $selecionar['select'] = array('p.id AS id_pessoa','cpf','p.nome AS nome',
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

            if($is_cpf){
                $selecionar['where']['cpf'] = $valor;
            }else{
                $selecionar['where']['pessoa_fisica.id'] = $valor;
            }
            $selecionar['where']['p.ativo'] = 1;
            if($this->pessoa_fisica_model->selecionar($selecionar) && $this->pessoa_fisica_model->num_registros()===1){
                $this->_add_data($this->pessoa_fisica_model->registro());
                if($this->input->method()==='post'){
                    $_POST = array_merge_recursive($_POST,$this->pessoa_fisica_model->registro());
                }
                $nascimento = explode('-',$this->pessoa_fisica_model->campo('nascimento'));
                $this->_add_data('nascimento',array('ano'=>$nascimento[0],'mes'=>$nascimento[1],'dia'=>$nascimento[2]));

                $this->load->model('telefone_model');
                $tel_sel = array();
                $tel_sel['where']['pessoa'] = $this->pessoa_fisica_model->campo('id_pessoa');
                if($this->telefone_model->selecionar($tel_sel)){
                    $telefones['id_tel'] = array();
                    $telefones['ddd'] = array();
                    $telefones['numero_telefone'] = array();
                    $telefones['tipo_telefone'] = array();
                    $telefones['operadora'] = array();
                    while($this->telefone_model->possui_proximo()){
                        $telefones['id_tel'][] = $this->telefone_model->campo('id');
                        $telefones['ddd'][] = $this->telefone_model->campo('ddd');
                        $telefones['numero_telefone'][] = $this->telefone_model->campo('telefone');
                        $telefones['tipo_telefone'][] = $this->telefone_model->campo('tipo');
                        $telefones['operadora'][] = $this->telefone_model->campo('operadora');
                    }
                    $this->_add_data($telefones);
                }
                $this->_view("Editar Pessoa Física",'editar',parent::RELATIVO_CONTROLE);            
            }else{
                $call['tipo'] = ALERTA_ERRO;
                $call['titulo'] = '';
                $call['mensagem'] = 'Nenhum registro encontrado';
                if($is_cpf){
                    $call['mensagem'] = 'O CPF ' . $valor . ' não foi encontrado.';
                }
                $call['fechavel'] = FALSE;
                $this->_add_data('_callout',$call);
                $this->_view("Editar Pessoa Física",'callout',parent::RELATIVO_PAI);
            }
        }else{
            $data = array('id_pessoa'=>'0','cpf'=>'','nome'=>'','email'=>'',
                'nascimento'=>array('ano'=>'1999','mes'=>'01','dia'=>'01'),'nacionalidade'=>'',
                'naturalidade'=>'','estado_civil'=>'','sexo'=>'','cep'=>'',
                'uf'=>'','municipio'=>'','bairro'=>'','logradouro'=>'','numero'=>'',
                'complemento'=>'','resenha'=>'','ativo'=>'','complemento2'=>'');
            $this->_add_data($data);
            $this->_view("Editar Pessoa Física",'editar',parent::RELATIVO_CONTROLE);
        }
    }
    
    public function atualizar(){
        if($this->input->post('atualizar')===NULL){
            redirect('sistema/pessoa/fisica/busca');
        }
        $this->load->library('form_validation');
        $this->load->model('pessoa_model');
        $this->load->model('pessoa_fisica_model');
        
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
        $this->form_validation->set_rules('nascimento[dia]', 'Dia Nascimento', 'trim|required|exact_length[2]');
        $this->form_validation->set_rules('nascimento[mes]', 'Mes Nascimento', 'trim|required|exact_length[2]');
        $this->form_validation->set_rules('nascimento[ano]', 'Ano Nascimento', 'trim|required|exact_length[4]');
        $this->form_validation->set_rules('nacionalidade', 'Nacionalidade', 'trim');
        $this->form_validation->set_rules('naturalidade', 'Naturalidade', 'trim');
        $this->form_validation->set_rules('estado_civil', 'Estado Civil', 'trim');
        $this->form_validation->set_rules('sexo', 'Sexo', 'trim|required|in_list[Masculino,Feminino]');
        $this->form_validation->set_rules('cep', 'CEP', 'trim|required|is_natural');
        $this->form_validation->set_rules('uf', 'Unidade Federal', 'trim');
        $this->form_validation->set_rules('municipio', 'Municipio', 'trim');
        $this->form_validation->set_rules('bairro', 'Bairro', 'trim');
        $this->form_validation->set_rules('logradouro', 'Logradouro', 'trim');
        $this->form_validation->set_rules('numero', 'Número', 'trim|is_natural');
        $this->form_validation->set_rules('complemento', 'Complemento', 'trim');
        $this->form_validation->set_rules('complemento2', 'Complemento2', 'trim');
        $this->form_validation->set_rules('id_tel[]', 'Id Telefone', 'trim');
        $this->form_validation->set_rules('ddd[]', 'DDD', 'trim');
        $this->form_validation->set_rules('numero_telefone[]', 'Número Telefone', 'trim');
        $this->form_validation->set_rules('operadora[]', 'Operadora', 'trim');
        $this->form_validation->set_rules('tipo_telefone[]', 'Tipo Telefone', 'trim');

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
            
            //Declara variavéis para alerta, com valores padrões em caso de erro
            $call['tipo'] = ALERTA_ERRO;
            $call['titulo'] = '';
            $call['mensagem'] = 'Falha ao salvar dados!';
            $call['fechavel'] = TRUE;
            $json['estatus'] = 'falha';
            
            $id_pessoa = 0;
            $selecionar['select'] = array('pessoa');
            $selecionar['join'] = array(array('pessoa p','p.id = pessoa_fisica.pessoa'));
            $selecionar['where'] = 'cpf = ' . $this->input->post('cpf');
            if($this->pessoa_fisica_model->selecionar($selecionar)){
                if($this->pessoa_fisica_model->num_registros() === 1){
                    $id_pessoa = $this->pessoa_fisica_model->campo('pessoa');
                }
            }
            if($id_pessoa > 0){
                //Prepara dados para gravação na tabela pessoa_fisica
                $fisica_dados = $this->input->post(array('nascimento','sexo','nacionalidade','naturalidade','estado_civil'));
                $fisica_dados['nascimento'] = $fisica_dados['nascimento']['ano'] . '-' . $fisica_dados['nascimento']['mes'] . '-' . $fisica_dados['nascimento']['dia'];
                //$fisica_dados['pessoa'] = $this->input->post('id_pessoa');
                //Altera os dados na tabela Pessoa Fisica
                $this->pessoa_fisica_model->alterar($fisica_dados,array('cpf'=>$this->input->post('cpf')));

                //Cadastra números de telefone para a pessoa
                $this->load->model('telefone_model');
                $telefones_old = array();
                $telefones_new = array();
                //Converte os dados dos telefones em um array legivel pela função salvar_telefones
                foreach($this->input->post('id_tel') as $k => $id_tel){
                    $telefone = array(
                        'ddd' => $this->input->post('ddd')[$k],
                        'telefone' => $this->input->post('numero_telefone')[$k],
                        'operadora' => $this->input->post('operadora')[$k],
                        'tipo' =>$this->input->post('tipo_telefone')[$k]
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
                $pessoa_dados = $this->input->post(array('nome','email','cep','numero','complemento','resenha'));
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
                if($this->pessoa_model->alterar($pessoa_dados,array('id'=>$id_pessoa)) && 
                        $pessoa_dados['email']!=NULL && $pessoa_dados['resenha']==1){
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
                
                //Altera variavéis do alerta para mensagem de sucesso
                $json['estatus'] = 'sucesso';
                $call['tipo'] = ALERTA_SUCESSO;
                $call['mensagem'] = 'Dados alterados com sucesso!';
            }

            //RETORNO PARA CLIENTE
            if($this->input->is_ajax_request()){
                $json['callout'] = $call;
                echo json_encode($json);
            }else{
                $this->_add_data('_callout',$call);
                $this->editar();
            }
        }
    }
}
