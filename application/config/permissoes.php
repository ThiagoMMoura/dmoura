<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Lista de configurações das permissões.
 *
 * @author Thiago Moura
 */
//      Pai     controle    função/pagina   subfuncao
// ID = '0'       '000'         '000'          '00'
$config['nivel-permissao'] = array(
    'pai' => 100000000,
    'controle' => 100000,
    'pagina' => 100,
    'subfuncao' => 1
);
$config['max-nivel'] = array(
    'pai' => 9,
    'controle' => 999,
    'pagina' => 999,
    'subfuncao' => 99
);
$config['permissoes'] = array(
    100000000=>array('descricao'=>'Sistema','nivel'=>'pai','url'=>'sistema'),
    100100000=>array('descricao'=>'Teste','nivel'=>'controle','url'=>'sistema/teste'),
    100100100=>array('descricao'=>'Index','nivel'=>'pagina','url'=>'sistema/teste/index'),
    100100200=>array('descricao'=>'Formulário','nivel'=>'pagina','url'=>'sistema/teste/formulario'),
    100100201=>array('descricao'=>'Excluir','nivel'=>'subfuncao','url'=>'sistema/teste/formulario/excluir'),
    100100202=>array('descricao'=>'Salvar','nivel'=>'subfuncao','url'=>'sistema/teste/formulario/salvar'),
    100100203=>array('descricao'=>'Salvar','nivel'=>'subfuncao','url'=>'#salvar'),
    200000000=>array('descricao'=>'Site','nivel'=>'pai','url'=>'site')
);
