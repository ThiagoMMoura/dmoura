<?php $this->load->helper('form');
if(!isset($id_form)){
    $id_form = 'form_telefone';
}
$campo['operadora'] = array(
    'dropdown'=>array('name'=>'operadora'),
    'label'=>'',
    'options' => array_merge(array(0=>''),$operadoras_telefone),
    'selected' => ''
);
$campo['tipo'] = array(
    'dropdown'=>array('name'=>'tipo_telefone','required'=>'','title'=>'Obrigatório a seleção de uma opção.'),
   // 'label'=>'Tipo',
    'options' => $tipos_telefone,
    'selected' => ''
);
add_body_script('assets/js/plus_telefone.js');
?>
<!--div class="row">
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
</div-->
<div class="row">
    <div class="column small-12">
        <fieldset class="fieldset">
            <legend>Telefone</legend>
            <div data-plus-tel-lista>
                <div class="row">
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
                    <a class="link" data-plus-telefone><i class="fi-plus"></i><span>Adicionar Telefone</span></a>
                </div>
            </div>
            <input type="button" id="plus-telefone" name="plus-telefone" class="button is-button-bar-menu" value="Telefone" data-icone="fi-plus" data-plus-telefone>
        </fieldset>
    </div>
</div>
<div data-plus-tel-form class="hide" data-tel-id-form="<?php echo $id_form;?>">
    <div class="row" data-plus-tel-item="0">
        <input type="text" name="id_tel" class="hide">
        <div class="column medium-2">
            <input type="text" name="ddd" pattern="\d{0,2}" class="text-right">
        </div>
        <div class="column medium-3">
            <label>
                <input type="text" name="numero_telefone" pattern="\d{8,11}" required title="Digite somente números, com 8 à 11 digitos" class="text-right">
                <span class="form-error">O telefone deve conter no minimo 8 digitos.</span>
            </label>
        </div>
        <div class="column medium-3">
            <?php echo campo_formulario_sistema($campo['tipo']);?>
        </div>
        <div class="column medium-3">
            <?php echo campo_formulario_sistema($campo['operadora']);?>
        </div>
        <div class="column medium-1">
            <button type="button" class="button" data-excluir-tel><i class="fi-trash"></i></button>
        </div>
    </div>
</div>
