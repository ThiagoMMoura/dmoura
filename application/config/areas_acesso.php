<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Lista de configurações das permissões.
 *
 * @author Thiago Moura
 */
//      Pai   controle   pagina/função   tag
// ID = '0'    '000'         '000'       '00'
$config['nivel-area'] = array(
    'pai' => 100000000,
    'controle' => 100000,
    'pagina' => 100,
    'funcao' => 100,
    'tag' => 1
);
$config['nivel-max'] = array(
    'pai' => 9,
    'controle' => 999,
    'pagina' => 999,
    'funcao' => 999,
    'tag' => 99
);
$config['areas_acesso'] = array(
    100000000=>array('descricao'=>'Sistema','nivel'=>'pai','url'=>'sistema'),
    100100000=>array('descricao'=>'Teste','nivel'=>'controle','url'=>'sistema/teste'),
    100100100=>array('descricao'=>'Index','nivel'=>'pagina','url'=>'sistema/teste/index'),
    100100200=>array('descricao'=>'Formulário','nivel'=>'pagina','url'=>'sistema/teste/formulario'),
    100100300=>array('descricao'=>'Excluir','nivel'=>'funcao','url'=>'sistema/teste/excluir'),
    100100400=>array('descricao'=>'Salvar','nivel'=>'funcao','url'=>'sistema/teste/salvar'),
    100100203=>array('descricao'=>'Tabela','nivel'=>'tag','url'=>'sistema/teste/formulario/salvar#tabela'),
    200000000=>array('descricao'=>'Site','nivel'=>'pai','url'=>'site')
);
