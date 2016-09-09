<?php 
$data['_lista'] = array($lista_cep);
$data['index_registro'] = 'cep';
$data['ocultar_campo'] = array('senha','id');
$this->load->view('sistema/gerador_tabela',$data);

