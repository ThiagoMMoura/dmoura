<?php $this->load->helper('form');
if(!isset($id_form)){
    $id_form = 'form_telefone';
}
$campo['operadora'] = array(
    'dropdown'=>array('form'=>$id_form,'name'=>'plus_operadora','id'=>'operadora'),
    'label'=>'Operadora',
    'options' => array_merge(array(0=>''),$operadoras_telefone),
    'selected' => ''
);
$campo['tipo'] = array(
    'dropdown'=>array('form'=>$id_form,'name'=>'plus_tipo_telefone','id'=>'tipo_telefone'),
    'label'=>'Tipo',
    'options' => $tipos_telefone,
    'selected' => ''
);
add_body_script('assets/js/plus_telefone.js');
?>
<div class="row">
    <div class="column small-12">
        <table id="lista-telefones" data-lista-telefone>
            <thead>
                <tr>
                    <th>DDD</th>
                    <th>Número</th>
                    <th>Tipo</th>
                    <th>Operadora</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <tr data-telefones-add="0"><td colspan="5">Nenhum telefone adicionado.</td></tr>
            </tbody>
        </table>
        <input type="button" id="plus-telefone" name="plus-telefone" class="button is-button-bar-menu" data-open="telefone_modal" value="Telefone" data-icone="fi-plus">
    </div>
</div>
<div class="reveal" id="telefone_modal" data-reveal data-reset-on-close="false">
    <fieldset class="fieldset" data-telefone-campo="input" data-telefone-lista="table" data-plus-telefone data-telefone-modal="telefone_modal">
        <legend>Telefone</legend>
        <div class="row">
            <div class="column medium-2">
                <label>
                    DDD
                    <input type="text" form="<?php echo $id_form;?>" name="plus_ddd" id="ddd">
                </label>
            </div>
            <div class="column medium-3">
                <label>
                    Número
                    <input type="text" form="<?php echo $id_form;?>" name="plus_numero_telefone" id="numero">
                </label>
            </div>
            <div class="column medium-4">
                <?php echo campo_formulario_sistema($campo['tipo']);?>
            </div>
            <div class="column medium-3">
                <?php echo campo_formulario_sistema($campo['operadora']);?>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <div class="float-right">
                    <button type="button" class="success button" data-add-telefone><i class="fi-plus"></i><span>Adicionar</span></button>
                    <button type="button" class="warning button" data-close><i class="fi-x"></i><span>Cancelar</span></button>
                </div>
            </div>
        </div>
    </fieldset>
    <button class="close-button" data-close aria-label="Fechar janela" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>