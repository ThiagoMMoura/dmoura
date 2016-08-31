<?php 
$data['_lista'] = $lista_pessoas;
$data['ocultar_campo'] = array('senha','id');
$this->load->view('sistema/gerador_tabela',$data);

