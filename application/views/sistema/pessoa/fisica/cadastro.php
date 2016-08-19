<?php
defined('BASEPATH') OR exit('No direct script access allowed');
add_body_script('assets/js/viacep.js');
$this->load->helper('form');
$data['action'] = 'sistema/pessoa/fisica/salvar';
$data['campos'] = array(
    array(
        'tag'=>'input',
        'atributos'=>array('value'=>set_value('cpf'),'name'=>'cpf','type'=>'number','placeholder' => '000.000.000-00','maxlength'=>'11'),
        'colunas'=>array('tamanho-m'=>4,'tamanho-l'=>4,'class'=>'end'),
        'linha'=>array('class'=>'','numero'=>1),
        'label'=>'CPF'
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('value'=>set_value('nome'),'name'=>'nome','placeholder' => 'Nome completo'),
        'colunas'=>array('tamanho-m'=>8,'tamanho-l'=>8,'class'=>''),
        'linha'=>array('class'=>'','numero'=>1),
        'label'=>'Nome'
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('value'=>set_value('email'),'name'=>'email','placeholder' => 'email@provedor.com','type'=>'email'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>3),
        'label'=>'Email'
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('name' => 'sexo', 'type' => 'radio', 'value' => 'Feminino'),
        'colunas'=>array('tamanho-s'=>6,'tamanho-m'=>6,'tamanho-l'=>3,'class'=>''),
        'linha'=>array('class'=>'','numero'=>4),
        'label'=>array('text' => 'Feminino','for'=>'feminino','posicao'=>'depois')
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('name' => 'sexo', 'type' => 'radio', 'value' => 'Masculino'),
        'colunas'=>array('tamanho-s'=>6,'tamanho-m'=>6,'tamanho-l'=>3,'class'=>'end'),
        'linha'=>array('class'=>'','numero'=>4),
        'label'=>array('text' => 'Masculino','for'=>'masculino','posicao'=>'depois')
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('name' => 'cep','id'=>'cep', 'placeholder' => '00000-000','type' => 'number','maxlength'=>'8','value'=>set_value('cep')),
        'colunas'=>array('tamanho-m'=>3,'tamanho-l'=>3,'class'=>''),
        'linha'=>array('class'=>'','numero'=>5),
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
        'atributos'=>array('name' => 'numero', 'placeholder' => '000','type' => 'number','maxlength'=>'5','value'=>set_value('numero')),
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
$this->load->view('sistema/gerador_formulario',$data);
