<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Lista de configurações do sistema.
 *
 * @author Thiago Moura
 */
$config['template-html'] = 'sistema/html_base';
$config['head-itens-antes-todos'] = array(
    array('meta'=>array('name'=>'x-ua-compatible','type'=>'http-equiv','content'=>'ie=edge')),
    array('meta'=>array('name'=>'viewport','type'=>'name','content'=>'width=device-width, initial-scale=1.0')),
    array('link'=>array('rel'=>'stylesheet','href'=>'assets/framework/css/foundation.min.css')),
    array('link'=>array('rel'=>'stylesheet','href'=>'assets/css/sistema.css')),
    array('script'=>array('src'=>'assets/framework/js/vendor/jquery.js'))
);
$config['head-itens-depois-todos'] = array();
$config['body-scripts'] = array(
    array('src'=>'assets/framework/js/vendor/what-input.js'),
    array('src'=>'assets/framework/js/vendor/foundation.min.js'),
    array('codigo'=>'$(document).foundation();')
);
$config['prefixo-id-menu'] = 'mn-';
$config['menu-principal'] = array(
    //'titulo-menu' => array('titulo'=>'MENU','li-class'=>'menu-text text-center'),
    'cadastro-pessoas' => array(
        'titulo' => 'Cadastro de Pessoas',
        'url' => '#cadastro-pessoas',
        'submenu' => array(
            'cadastro-funcionario' => array('titulo'=>'Cadastro de Funcionários','url'=>'#'),
            'cadastro-cliente' => array('titulo'=>'Cadastro de Clientes','url'=>'#'),
            'cadastro-empresa' => array('titulo'=>'Cadastro de Empresas','url'=>'#')
        )
    ),
    'cadastro-produto' => array('titulo'=>'Cadastro de produtos','url'=>'#')
);
$config['estilos_menu'] = array(
    'padrao' => array(),//ul-class,ul-atributos,li-class,li-atributos
    'drilldown' => array('ul-class'=>'vertical menu','ul-atributos'=>'data-drilldown')
);
$config['estilos_submenu'] = array(
    'padrao' => array(),
    'drilldown' => array('ul-class'=>'vertical menu fundo-azul-claro')
);