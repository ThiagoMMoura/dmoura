<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('form');

$data['form_atributos'] = array('id'=>'form_pessoa_fisica','data-live-validate'=>'true');
$data['action'] = 'sistema/teste/salvar_telefone';
$data['campos'] = array(
    array(
        'tag'=>'input',
        'atributos'=>array('value'=>set_value('pessoa'),'name'=>'pessoa','type'=>'text','placeholder' => 'Somente números','autofocus'=>''),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>1),
        'erro'=>'O Id de pessoa é obrigatória e deve conter somente números.',
        'label'=>'Id Pessoa'
    ),
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

$data2['id_form'] = $data['form_atributos']['id'];
$data2['form_atributos'] = array('id'=>'form_telefone','data-live-validate'=>'true');
$data2['action'] = '';
$data2['campos'] = '';
$this->load->view('sistema/ferramenta/telefone',$data2);

