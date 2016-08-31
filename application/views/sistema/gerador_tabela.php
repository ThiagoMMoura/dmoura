<?php
$this->load->helper('form');
$this->load->helper('inflector');

if(!isset($selecionar)){
    $selecionar = array('type'=>'checkbox','id'=>'item','name'=>'item','value'=>'id');
}else{
    if(!array_key_exists('value', $selecionar)){
        $selecionar['value'] = 'id';
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
?>
<div class="row">
    <div class="column">
        
    </div>
</div>
<div class="row">
    <div class="column">
        <?php 
        if(count($_lista)>0){?>
            <div class="table-scroll">
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
                                $selecionar['id'] .= $item['id'];
                                $selecionar['value'] = $item[$selecionar['value']];
                                $selecionar['name'] = $selecionar['name'] . '[]';
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
            </div>
        <?php }else{
            echo alertas('', 'Nenhum resultado encontrado', ALERTA_RISCO);
        }
        ?>
    </div>
</div>

