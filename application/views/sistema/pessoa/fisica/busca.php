<?php 
foreach($lista_pessoas as $k => $row){
    foreach($row as $i => $col){
        if($i==='cep'){
            $lista_pessoas[$k]['cep'] = anchor('sistema/endereco/cep/consulta/' . $col,$col,'target="_blank"');
        }
    }
}
$data['_lista'] = $lista_pessoas;
$data['ocultar_campo'] = array('senha','id');
$this->load->view('sistema/gerador_tabela',$data);

