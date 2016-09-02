<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('form');

$data['form_atributos'] = array('id'=>'form_tipo_telefone');
$data['action'] = 'sistema/basico/telefone/tipo/salvar';
$data['campos'] = array(
    array(
        'tag'=>'fieldset',
        'atributos'=>array('class' => 'fieldset'),
        'colunas'=>array('tamanho-s'=>12,'tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>1),
        'campos'=>array(
            array(
                'tag'=>'input',
                'atributos'=>array('value'=>set_value('tipo'),'name'=>'tipo','type'=>'text','placeholder' => 'Nome do Tipo','required'=>''),
                'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
                'linha'=>array('class'=>'','numero'=>1),
                'erro'=>'O campo acima é obrigatório.',
                'label'=>''
            )
        ),
        'legend' => 'Tipo Telefone'
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
$data2['_lista'] = $lista_tipos;
$data2['ocultar_campo'] = array('id');
$data2['selecionar'] = array('type'=>'radio','name'=>'tipo');
?>
<form>
    <div class="row">
        <div class="column small-12">
            <fieldset class="fieldset">
                <legend>Tipos Cadastrados</legend>
                <?php
                $this->load->view('sistema/gerador_tabela',$data2);
                ?>
            </fieldset>
        </div>
    </div>
</form>