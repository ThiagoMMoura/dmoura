<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$campos = array(
    array(
        'tag'=>'input',
        'atributos'=>array(),
        'colunas'=>array('tamanho-m'=>12,'tamanho-l'=>12,'class'=>''),
        'linha'=>array('class'=>'','numero'=>1),
        'botao'=>array(
            'tipo'=>'pesquisa',
            'tag'=>'a',
            'atributos'=>array()
            )
    )
);
echo form_open($action,$form_atributos,$hidden);
    $lin_anterior = 0;
    $lin_atual = 0;
    foreach($campos as $campo){
        $class_linha = 'row';
        $lin_anterior = $lin_atual;
        if(array_key_exists('linha', $campo)){
            if($campo['linha']['class']!=NULL){
                $class_linha .= ' ' . $campo['linha']['class'];
            }
            if(array_key_exists('numero',$campo['linha'])){
                $lin_atual = $campo['linha']['numero'];
            }else{
                $lin_atual++;
            }
        }else{
            $lin_atual++;
        }
        $class_coluna = 'small-12';
        if(array_key_exists('colunas', $campo)){
            if($campo['colunas']['tamanho-m']!=NULL){
                $class_coluna .= ' medium-' . $campo['colunas']['tamanho-m'];
            }
            if($campo['colunas']['tamanho-l']!=NULL){
                $class_coluna .= ' large-' . $campo['colunas']['tamanho-l'];
            }
            if($campo['colunas']['class']!=NULL){
                $class_coluna .= ' columns ' . $campo['colunas']['class'];
            }
        }else{
            $class_coluna .= ' columns';
        }
        if($lin_anterior!=$lin_atual){
            echo '</div>';
            echo '<div class="' . $class_linha . '">';
        } ?>
            <div class="<?php echo $class_coluna; ?>">
                <?php
                switch($campo['tag']){
                    case 'select':{
                        $campo['tag'] = 'dropdown';
                    }
                    case 'input':case 'textarea':case 'dropdown':case 'multiselect':case 'button':{
                        $data = array($campo['tag']=>$campo['atributos']);
                        $data['label'] = $campo['label'];
                        $data['extra'] = $campo['extra'];
                        $data['datalist'] = $campo['datalist'];
                        $data['options'] = $campo['options'];
                        $data['selected'] = $campo['selected'];
                        echo campo_formulario_sistema($data);
                    }
                }
                if(array_key_exists('botao', $campo)){
                    switch($campo['botao']['tag']){
                        
                    }
                }
                ?>
            </div>
    <?php } 
    if($lin_atual>0){
        echo '</div>';
    }
echo form_close();
