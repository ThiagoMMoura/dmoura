<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('form');
if(!isset($campos)){
    $campos = array();
}
if(!isset($action)){
    $action = 'sistema';
}
if(!isset($form_atributos)){
    $form_atributos = '';
}
if(!isset($hidden)){
    $hidden = '';
}

if(is_array($form_atributos)){
    $form_atributos['data-abide'] = '';
    //$form_atributos['novalidate'] = '';
}else{
    $form_atributos .= ' data-abide';
}
//$campos = array(
//    array(
//        'tag'=>'input',
//        'atributos'=>array(),
//        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
//        'linha'=>array('class'=>'','numero'=>1),
//        'botao'=>array(
//            'tipo'=>'pesquisa',
//            'tag'=>'a',
//            'atributos'=>array()
//        )
//    )
//);
if(isset($_callout)){
    echo '<div class="row form-page"><div class="column small-12">';
    echo alertas($_callout['titulo'], $_callout['mensagem'], $_callout['tipo'], $_callout['fechavel']);
    echo '</div></div>';
}

echo form_open($action,$form_atributos,$hidden);
    ?>
    <div class="row">
        <div class="small-12 column">
            <?php echo validation_errors('<div class="alert callout">','</div>');?>
            <div data-abide-error class="alert callout" style="display: none;">
                <p><i class="fi-alert"></i> Existem alguns erros no seu formulário.</p>
            </div>
        </div>
    </div>
    <?php
    gerar_campo($campos);
echo form_close();

function gerar_campo($campos,$c_linha=TRUE,$c_coluna=TRUE){
    $lin_anterior = 0;
    $lin_atual = 0;
    foreach($campos as $campo){
        
        if($c_linha && array_key_exists('linha', $campo)){
            $class_linha = 'row';
            $lin_anterior = $lin_atual;

            if($campo['linha']['class']!=NULL){
                $class_linha .= ' ' . $campo['linha']['class'];
            }
            if(array_key_exists('numero',$campo['linha'])){
                $lin_atual = $campo['linha']['numero'];
            }else{
                $lin_atual++;
            }
            if($lin_anterior!=$lin_atual){
                if($lin_anterior>0){
                    echo '</div>';
                }
                echo '<div class="' . $class_linha . '">';
            }
        }else{
            $c_linha = FALSE;
        }
        if($c_coluna && array_key_exists('colunas', $campo)){
            $class_coluna = 'column';
            if(array_key_exists('tamanho-s', $campo['colunas']) && $campo['colunas']['tamanho-s']!=NULL){
                $class_coluna .= ' small-' . $campo['colunas']['tamanho-s'];
            }else{
                $class_coluna .= ' small-12';
            }
            if(array_key_exists('tamanho-m', $campo['colunas']) && $campo['colunas']['tamanho-m']!=NULL){
                $class_coluna .= ' medium-' . $campo['colunas']['tamanho-m'];
            }
            if(array_key_exists('tamanho-l', $campo['colunas']) && $campo['colunas']['tamanho-l']!=NULL){
                $class_coluna .= ' large-' . $campo['colunas']['tamanho-l'];
            }
            if(array_key_exists('class', $campo['colunas']) && $campo['colunas']['class']!=NULL){
                $class_coluna .= ' ' . $campo['colunas']['class'];
            }
            echo '<div class="' . $class_coluna . '">';
        }else{
            $c_coluna = FALSE;
        }
        switch($campo['tag']){
            case 'select':{
                $campo['tag'] = 'dropdown';
            }
            case 'input':case 'textarea':case 'dropdown':case 'multiselect':case 'button':{
                $data = array($campo['tag']=>$campo['atributos']);
                $data['label'] = array_key_exists('label', $campo)?$campo['label']:'';
                $data['extra'] = array_key_exists('extra', $campo)?$campo['extra']:'';
                $data['datalist'] = array_key_exists('datalist', $campo)?$campo['datalist']:'';
                $data['options'] = array_key_exists('options', $campo)?$campo['options']:'';
                $data['selected'] = array_key_exists('selected', $campo)?$campo['selected']:'';
                $data['erro'] = array_key_exists('erro', $campo)?$campo['erro']:'';
                
                echo campo_formulario_sistema($data);
                break;
            }
            case 'fieldset':{
                $atributos = '';
                if(array_key_exists('atributos', $campo)){
                    if(is_array($campo['atributos'])){
                        foreach($campo['atributos'] as $attr => $val){
                            $atributos .= ' ' . $attr . '="' . $val . '"';
                        }
                    }
                }
                echo "<fieldset{$atributos}>";
                if(array_key_exists('legend', $campo)){
                    echo "<legend>{$campo['legend']}</legend>";
                }
                echo gerar_campo($campo['campos']);
                echo '</fieldset>';
            }
        }
        if(array_key_exists('botao', $campo)){
            switch($campo['botao']['tag']){

            }
        }
        if($c_coluna){
            echo "</div>";
        }
    } 
    if($c_linha && $lin_atual>0){
        echo '</div>';
    }
}