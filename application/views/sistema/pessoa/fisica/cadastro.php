<?php
defined('BASEPATH') OR exit('No direct script access allowed');
add_body_script('assets/js/viacep.js');
$this->load->helper('form');
if(isset($_callout)){
    echo '<div class="column">' . alertas($_callout['titulo'], $_callout['mensagem'], $_callout['tipo'], $_callout['fechavel']) . '</div>';
}
$data['action'] = 'sistema/pessoa/fisica/salvar';
$data['campos'] = array(
    array(
        'tag'=>'fieldset',
        'atributos'=>array('class' => 'fieldset'),
        'colunas'=>array('tamanho-s'=>12,'tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>1),
        'campos'=>array(
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('cpf'),'name'=>'cpf','type'=>'text','pattern'=>'\d{11}','placeholder' => 'Somente números','maxlength'=>'11','required'=>''),
                'colunas'=>array('tamanho-m'=>4,'tamanho-l'=>4,'class'=>'end'),
                'linha'=>array('class'=>'','numero'=>1),
                'erro'=>'O CPF é obrigatório e deve conter somente números.',
                'label'=>'CPF'
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('nome'),'name'=>'nome','placeholder' => 'Nome completo'),
                'colunas'=>array('tamanho-m'=>8,'tamanho-l'=>8,'class'=>''),
                'linha'=>array('class'=>'','numero'=>1),
                'erro'=>'O Nome Completo é obrigatório e deve conter somente letras.',
                'label'=>'Nome'
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('email'),'name'=>'email','placeholder' => 'Exemplo: email@provedor.com','type'=>'email'),
                'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
                'linha'=>array('class'=>'','numero'=>3),
                'erro'=>'Digite um email válido.',
                'label'=>'Email'
            )
            
        ),
        'legend' => 'Identificação'
    ),
    array(
        'tag'=>'fieldset',
        'atributos'=>array('class' => 'fieldset'),
        'colunas'=>array('tamanho-s'=>12,'tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>2),
        'campos'=>array(
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('nascimento'),'name'=>'nascimento','placeholder' => '00/00/0000','type'=>'date','pattern'=>'\d{2}/\d{2}/\d{4}'),
                'colunas'=>array('tamanho-m'=>6,'tamanho-l'=>3,'class'=>''),
                'linha'=>array('class'=>'','numero'=>2),
                'erro'=>'Digite uma data válida.',
                'label'=>'Data Nascimento'
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('nacionalidade'),'name'=>'nacionalidade','placeholder' => 'País de origem','type'=>'text'),
                'colunas'=>array('tamanho-m'=>6,'tamanho-l'=>3,'class'=>''),
                'linha'=>array('class'=>'','numero'=>2),
                'erro'=>'Somente letras.',
                'label'=>'Nacionalidade'
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('naturalidade'),'name'=>'naturalidade','placeholder' => 'Natural de...','type'=>'text'),
                'colunas'=>array('tamanho-m'=>6,'tamanho-l'=>3,'class'=>''),
                'linha'=>array('class'=>'','numero'=>2),
                'erro'=>'Somente letras.',
                'label'=>'Naturalidade'
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('estado_civil'),'name'=>'estado_civil','placeholder' => 'Solteiro, Casado...','type'=>'text'),
                'colunas'=>array('tamanho-m'=>6,'tamanho-l'=>3,'class'=>'end'),
                'linha'=>array('class'=>'','numero'=>2),
                'erro'=>'Somente letras.',
                'label'=>'Estado Civil'
            ),
            array(
                'tag'=>'fieldset',
                'atributos'=>array('class' => ''),
                'colunas'=>array('tamanho-s'=>12,'tamanho-m'=>12,'tamanho-l'=>6,'class'=>''),
                'linha'=>array('class'=>'','numero'=>4),
                'campos'=>array(
                    array(
                        'tag'=>'input',
                        'atributos'=>array('name' => 'sexo', 'type' => 'radio', 'value' => 'Feminino',set_checkbox('sexo', 'Feminino')=>''),
                        'label'=>array('text' => 'Feminino','for'=>'feminino','posicao'=>'depois')
                    ),
                    array(
                        'tag'=>'input',
                        'atributos'=>array('name' => 'sexo', 'type' => 'radio', 'value' => 'Masculino',set_checkbox('sexo', 'Masculino')=>''),
                        'label'=>array('text' => 'Masculino','for'=>'masculino','posicao'=>'depois')
                    )
                ),
                'legend' => 'Sexo'
            )
        ),
        'legend' => 'Dados Pessoais'
    ),
    array(
        'tag'=>'fieldset',
        'atributos'=>array('class' => 'fieldset'),
        'colunas'=>array('tamanho-s'=>12,'tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>3),
        'campos'=>array(
            array(
                'tag'=>'input',
                'atributos'=>array('name' => 'cep','id'=>'cep', 'placeholder' => 'Somente números','type' => 'text','maxlength'=>'8','value'=>set_value('cep'),'pattern'=>'number'),
                'colunas'=>array('tamanho-m'=>3,'tamanho-l'=>3,'class'=>''),
                'linha'=>array('class'=>'','numero'=>5),
                'erro'=>'O CEP deve ser válido e conter somente números.',
                'label'=>'CEP'
            ),
            array(
                'tag'=>'dropdown',
                'atributos'=>array('name' => 'estado','id'=>'uf', 'placeholder' => 'Selecione uma opção...','value'=>set_value('uf')),
                'colunas'=>array('tamanho-m'=>9,'tamanho-l'=>4,'class'=>'end'),
                'linha'=>array('class'=>'','numero'=>5),
                'label'=>'Estado',
                'options' => array_merge(array(0=>''),$estados)
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('cidade'),'id'=>'cidade','name'=>'cidade','placeholder' => 'Cidade'),
                'colunas'=>array('tamanho-m'=>6,'tamanho-l'=>5,'class'=>''),
                'linha'=>array('class'=>'','numero'=>5),
                'label'=>'Cidade'
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('bairro'),'id'=>'bairro','name'=>'bairro','placeholder' => 'Bairro'),
                'colunas'=>array('tamanho-m'=>6,'tamanho-l'=>12,'class'=>''),
                'linha'=>array('class'=>'','numero'=>5),
                'label'=>'Bairro'
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('logradouro'),'id'=>'rua','name'=>'logradouro','placeholder' => 'Rua/Av'),
                'colunas'=>array('tamanho-m'=>9,'tamanho-l'=>9,'class'=>'end'),
                'linha'=>array('class'=>'','numero'=>5),
                'label'=>'Logradouro'
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('name' => 'numero', 'placeholder' => '000','type' => 'text','pattern'=>'number','maxlength'=>'5','value'=>set_value('numero')),
                'colunas'=>array('tamanho-m'=>3,'tamanho-l'=>3,'class'=>'end'),
                'linha'=>array('class'=>'','numero'=>5),
                'label'=>'Número'
            ),
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>'','name'=>'complemento','placeholder' => 'Complemento','value'=>set_value('complemento')),
                'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
                'linha'=>array('class'=>'','numero'=>6),
                'label'=>'Complemento'
            )
        ),
        'legend' => 'Endereço'
    ),
    /*array(
        'tag'=>'dropdown',
        'atributos'=>array('name' => 'grupo', 'placeholder' => 'Selecione uma opção...'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>'end'),
        'linha'=>array('class'=>'','numero'=>7),
        'label'=>'Grupo',
        'options' => array(1=>'Grupo 1',2=>'Grupo 2',3=>'Grupo 3',4=>'Grupo 4',5=>'Grupo 5')
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('name' => 'senha', 'placeholder' => 'Senha', 'required' => '','type' => 'password'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>11,'class'=>''),
        'linha'=>array('class'=>'','numero'=>8),
        'label'=>'Senha'
    ),*/
    array(
        'tag'=>'input',
        'atributos'=>array('type'=>'reset','value'=>'Limpar','name'=>'limpar','id'=>'limpar','data-icone'=>'fi-trash','class'=>'is-button-bar-menu button','data-bar-menu-hide'=>'true'),
        'linha'=>array('class'=>'','numero'=>10),
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('type'=>'submit','value'=>'Salvar','name'=>'salvar','id'=>'salvar','data-icone'=>'fi-save','class'=>'is-button-bar-menu button'),
        'linha'=>array('class'=>'','numero'=>10),
    )
);
$data['hidden'] = array('complemento2'=>set_value('complemento2'));
$this->load->view('sistema/gerador_formulario',$data);
