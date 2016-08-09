<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Lista de configurações das permissões.
 *
 * @author Thiago Moura
 */
//      Pai   controle   metodo
// ID = '0'    '000'      '000'
$config['nivel-area']['url'] = array(
    'pai' => 1000000,
    'controle' => 1000,
    'metodo' => 100
);
$config['nivel-max']['url'] = array(
    'pai' => 9,
    'controle' => 999,
    'metodo' => 999
);
$config['areas_acesso']['url'] = array(
    1000000=>array('descricao'=>'Sistema','nivel'=>'pai','url'=>'sistema'),
    1001000=>array('descricao'=>'Teste','nivel'=>'controle','url'=>'sistema/teste'),
    1001001=>array('descricao'=>'Index','nivel'=>'metodo','url'=>'sistema/teste/index'),
    1001002=>array('descricao'=>'Formulário','nivel'=>'metodo','url'=>'sistema/teste/formulario'),
    1001003=>array('descricao'=>'Excluir','nivel'=>'metodo','url'=>'sistema/teste/excluir'),
    1001004=>array('descricao'=>'Salvar','nivel'=>'metodo','url'=>'sistema/teste/salvar'),
    2000000=>array('descricao'=>'Site','nivel'=>'pai','url'=>'site')
);
$config['nivel-area']['objeto'] = array(
    'pai' => 100000,
    'objeto' => 1
);
$config['nivel-max']['objeto'] = array(
    'pai' => 9,
    'objeto' => 99999
);
$config['areas_acesso']['objeto'] = array(
    100000=>array('descricao'=>'Sistema','nivel'=>'pai','idt'=>'sistema')
);