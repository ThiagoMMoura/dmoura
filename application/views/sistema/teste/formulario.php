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
    )
);
$this->load->view('sistema/gerador_formulario',$data);
?>
<div class="row">
    <table>
      <thead>
        <tr>
          <th width="200">Table Header</th>
          <th>Table Header</th>
          <th width="150">Table Header</th>
          <th width="150">Table Header</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Content Goes Here</td>
          <td>This is longer content Donec id elit non mi porta gravida at eget metus.</td>
          <td>Content Goes Here</td>
          <td>Content Goes Here</td>
        </tr>
        <tr>
          <td>Content Goes Here</td>
          <td>This is longer Content Goes Here Donec id elit non mi porta gravida at eget metus.</td>
          <td>Content Goes Here</td>
          <td>Content Goes Here</td>
        </tr>
        <tr>
          <td>Content Goes Here</td>
          <td>This is longer Content Goes Here Donec id elit non mi porta gravida at eget metus.</td>
          <td>Content Goes Here</td>
          <td>Content Goes Here</td>
        </tr>
      </tbody>
    </table>
</div>
