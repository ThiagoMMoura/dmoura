<?php
$this->load->helper('form');
$this->load->helper('inflector');
if(!isset($index_registro)){
    $index_registro = 'id';
}
if(!isset($selecionar)){
    $selecionar = array('type'=>'checkbox','id'=>'item','name'=>'item','value'=>$index_registro);
}else{
    if(!array_key_exists('value', $selecionar)){
        $selecionar['value'] = $index_registro;
    }
    if(!array_key_exists('name', $selecionar)){
        $selecionar['name'] = 'item';
    }
    if(!array_key_exists('id', $selecionar)){
        $selecionar['id'] = 'item';
    }
    if(!array_key_exists('type', $selecionar)){
        $selecionar['type'] = 'checkbox';
    }
}
if(!isset($selecionavel)){
    $selecionavel = TRUE;
}
$_sel_id = $selecionar['id'];
$_sel_name = $selecionar['name'];
$_sel_value = $selecionar['value'];
if(!isset($table_scroll)){
    $table_scroll = TRUE;
}
?>
<div class="row">
    <div class="column">
        
    </div>
</div>
<div class="row">
    <div class="column <?php echo $table_scroll?'table-scroll':'small-12';?>">
        <?php 
        if(count($_lista)>0){?>
                <table>
                    <thead>
                        <tr>
                            <?php
                            if($selecionavel){
                                echo '<th><i class="fi-checkbox"></i></th>';
                            }
                            foreach($_lista[0] as $k => $v){
                                if(array_search($k, $ocultar_campo)===FALSE){
                                    echo '<th>' . humanize($k) . '</th>';
                                }
                            }?>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php foreach($_lista as $item){
                            echo '<tr>';
                            if($selecionavel){
                                $selecionar['id'] = $_sel_id . $item[$index_registro];
                                $selecionar['value'] = $item[$_sel_value];
                                $selecionar['name'] = $_sel_name;
                                if($selecionar['type']!=='radio'){
                                    $selecionar['name'] .= '[' . $item[$index_registro] . ']';
                                }
                                echo '<td>' . campo_formulario_sistema($selecionar) . '</td>';
                            }
                            foreach($item as $k => $v){
                                if(array_search($k, $ocultar_campo)===FALSE){
                                    echo '<td>' . $v . '</td>';
                                }
                            }
                            echo '</tr>';
                        }?>
                    </tbody>
                </table>
        <?php }else{
            echo alertas('', 'Nenhum resultado encontrado', ALERTA_RISCO);
        }
        ?>
    </div>
</div>

