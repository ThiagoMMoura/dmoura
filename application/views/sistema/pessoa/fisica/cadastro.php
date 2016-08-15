<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['action'] = 'sistema/pessoa/fisica/salvar';
$data['campos'] = array(
    array(
        'tag'=>'input',
        'atributos'=>array('value'=>'','name'=>'nome'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>1),
        'label'=>'Nome'
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('value'=>'','name'=>'cpf','type'=>'number'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>2),
        'label'=>'CPF'
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('value'=>24,'name'=>'email'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>2),
        'label'=>'Email'
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('name' => 'sexo', 'type' => 'radio', 'value' => 'Feminino'),
        'colunas'=>array('tamanho-m'=>6,'tamanho-l'=>3,'class'=>''),
        'linha'=>array('class'=>'','numero'=>3),
        'label'=>array('text' => 'Feminino','for'=>'feminino','posicao'=>'depois')
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('name' => 'sexo', 'type' => 'radio', 'value' => 'Masculino'),
        'colunas'=>array('tamanho-m'=>6,'tamanho-l'=>3,'class'=>'end'),
        'linha'=>array('class'=>'','numero'=>3),
        'label'=>array('text' => 'Masculino','for'=>'masculino','posicao'=>'depois')
    ),
    array(
        'tag'=>'dropdown',
        'atributos'=>array('name' => 'idnivel', 'placeholder' => 'Selecione um nível...'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>4),
        'label'=>'Nível',
        'options' => array('Nivel 1','Nivel 2','Nivel 3','Nivel 4','Nivel 5','Nivel 6')
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('name' => 'senha', 'placeholder' => 'Senha', 'required' => '','type' => 'password'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>5),
        'label'=>'Senha'
    ),
    array(
        'tag'=>'textarea',
        'atributos'=>array('name'=>'descricao','placeholder'=>'Descreva o conteúdo do album...','rows'=>5,'cols'=>300),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>6),
        'label'=>'Descrição'
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('type'=>'reset','value'=>'Limpar','name'=>'limpar','id'=>'limpar','data-icone'=>'fi-trash','class'=>'is-button-bar-menu button','data-bar-menu-hide'=>'true'),
        'linha'=>array('class'=>'','numero'=>7),
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('type'=>'submit','value'=>'Salvar','name'=>'salvar','id'=>'salvar','data-icone'=>'fi-save','class'=>'is-button-bar-menu button'),
        'linha'=>array('class'=>'','numero'=>7),
    )
);
$this->load->view('sistema/gerador_formulario',$data);
