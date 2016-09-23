<?php 
add_body_script('assets/js/sistema/pessoa/fisica/busca.js');
foreach($lista_pessoas as $k => $row){
    foreach($row as $i => $col){
        if($i==='cep'){
            $lista_pessoas[$k]['cep'] = anchor('sistema/endereco/cep/consulta/' . $col,$col,'target="_blank"');
        }
    }
}
$data['_lista'] = $lista_pessoas;
$data['ocultar_campo'] = array('senha','id');
?>
<form action="<?php echo base_url('sistema/pessoa/fisica/editar');?>" method="post">
    <input type="hidden" value="0" name="id" id="id-pessoa">
    <?php $this->load->view('sistema/gerador_tabela',$data); ?>
    <input type="submit" class="button is-button-bar-menu" value="Editar" name="editar" id="editar" data-icone="fi-pen">
</form>