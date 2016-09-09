<?php $this->load->helper('form');
if(!isset($id_form)){
    $id_form = 'form_telefone';
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
    if(!array_key_exists('id', $form_atributos)){
        $form_atributos['id'] = $id_form;
    }
}else{
    $form_atributos .= ' data-abide';
    if(strstr($form_atributos,' id="')===FALSE &&
            (strstr($form_atributos,'id="')!==FALSE && strlen(strstr($form_atributos,'id="',TRUE))>0)){
        $form_atributos .= ' id="' . $id_form . '"';
    }
}
$campo['operadora'] = array(
    'dropdown'=>array('name'=>'operadora'),
    'label'=>array('text'=>'Operadora','posicao'=>'antes','attributes'=>array('class'=>'show-for-small-only')),
    'options' => array_merge(array(0=>''),$operadoras_telefone),
    'selected' => ''
);
$campo['tipo'] = array(
    'dropdown'=>array('name'=>'tipo_telefone','required'=>'','title'=>'Obrigatório a seleção de uma opção.'),
    'label'=>array('text'=>'Tipo','posicao'=>'antes','attributes'=>array('class'=>'show-for-small-only')),
    'options' => $tipos_telefone,
    'selected' => ''
);
add_body_script('assets/js/plus_telefone.js');
$telefones = set_value('id_tel');
$lista = array();
if($telefones!=NULL){
    foreach($telefones as $tel){
        $lista[] = array('id_tel' => $tel,
            'ddd' => set_value('ddd[]'),
            'numero_telefone' => set_value('numero_telefone[]'),
            'operadora' => set_value('operadora[]'),
            'tipo_telefone' => set_value('tipo_telefone[]')
        );
    }
}
echo form_open($action,$form_atributos,$hidden);
?>
<div class="row">
    <div class="column small-12">
        <fieldset class="fieldset">
            <legend>Telefone</legend>
            <div data-plus-tel-lista data-hide-on-empty="true" data-pre-values='<?php echo json_encode($lista);?>'>
                <div class="row hide-for-small-only">
                    <div class="column medium-2">
                        <label>DDD</label>       
                    </div>
                    <div class="column medium-3">
                        <label>Número</label>
                    </div>
                    <div class="column medium-3">
                        <label>Tipo</label>
                    </div>
                    <div class="column medium-3">
                        <label>Operadora</label>
                    </div>
                    <div class="column medium-1">
                        <label>Excluir</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="column small-12">
                    <a class="hide-for-small-only success hollow button" data-plus-telefone><i class="fi-plus"></i><span>Adicionar Telefone</span></a>
                    <a class="show-for-small-only success expanded button" data-plus-telefone><i class="fi-plus"></i><span>Adicionar Telefone</span></a>
                </div>
            </div>
            <input type="button" id="plus-telefone" name="plus-telefone" class="button is-button-bar-menu" value="Telefone" data-icone="fi-plus" data-plus-telefone>
        </fieldset>
    </div>
</div>
<div data-plus-tel-form class="hide" data-tel-id-form="<?php echo $id_form;?>">
    <div class="row" data-plus-tel-item="0">
        <input type="hidden" name="id_tel">
        <div class="column medium-2">
            <label class="show-for-small-only">DDD</label>
            <input type="text" name="ddd" pattern="\d{2}" class="text-right" title="O DDD deve conter 2 digitos">
        </div>
        <div class="column medium-3">
            <label class="show-for-small-only">Número</label>
            <input type="text" name="numero_telefone" pattern="\d{8,11}" required title="Digite somente números, com 8 à 11 digitos" class="text-right">
            <span class="form-error">O telefone deve conter no minimo 8 digitos.</span>
        </div>
        <div class="column medium-3">
            <?php echo campo_formulario_sistema($campo['tipo']);?>
        </div>
        <div class="column medium-3">
            <?php echo campo_formulario_sistema($campo['operadora']);?>
        </div>
        <div class="column medium-1">
            <button type="button" class="hide-for-small-only alert hollow button" data-excluir-tel><i class="fi-trash"></i></button>
            <button type="button" class="show-for-small-only alert expanded button" data-excluir-tel><i class="fi-trash"></i><span>Excluir</span></button>
        </div>
        <hr class="show-for-small-only">
    </div>
</div>
<?php echo form_close();