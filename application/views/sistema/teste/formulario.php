<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$data['action'] = 'sistema/teste/formulario';
$data['campos'] = array(
    array(
        'tag'=>'input',
        'atributos'=>array('value'=>'Thiago','name'=>'nome'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>1),
        'label'=>'Nome'
    ),
    array(
        'tag'=>'input',
        'atributos'=>array('value'=>24,'name'=>'idade'),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>2),
        'label'=>'Idade'
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
    )
);
$this->load->view('sistema/gerador_formulario',$data);

