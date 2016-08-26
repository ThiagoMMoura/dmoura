<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Lista de configurações do sistema.
 *
 * @author Thiago Moura
 */
$config['home'] = 'sistema/teste';
$config['template-html'] = 'sistema/html_base';
$config['head-itens-antes-todos'] = array(
    array('meta'=>array('name'=>'x-ua-compatible','type'=>'http-equiv','content'=>'ie=edge')),
    array('meta'=>array('name'=>'viewport','type'=>'name','content'=>'width=device-width, initial-scale=1.0')),
    array('link'=>array('rel'=>'stylesheet','href'=>'assets/framework/css/foundation.min.css')),
    array('link'=>array('rel'=>'stylesheet','href'=>'assets/framework/foundation-icons.css')),
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
    'pessoa' => array(
        'titulo' => 'Pessoas',
        'url' => '#pessoa',
        'submenu' => array(
            'cadastro-funcionario' => array('titulo'=>'Cadastro de Funcionários','url'=>'#'),
            'fisica-cadastro' => array('titulo'=>'Cadastro Pessoa Fisica','url'=>'sistema/pessoa/fisica/cadastro'),
            'cadastro-empresa' => array('titulo'=>'Cadastro de Empresas','url'=>'#')
        )
    ),
    'cadastro-produto' => array('titulo'=>'Cadastro de produtos','url'=>'#')
);
$config['estilos_menu'] = array(
    'padrao' => array(),//ul-class,ul-atributos,li-class,li-atributos
    'drilldown' => array('ul-class'=>'vertical menu','ul-atributos'=>array('data-back-button'=>"<li class='js-drilldown-back'><a>Voltar</a></li>",'data-drilldown'=>''))
);
$config['estilos_submenu'] = array(
    'padrao' => array(),
    'drilldown' => array('ul-class'=>'vertical menu')
);
$config['alerta'] = array(
    'classes' => 'callout',
    'tipo' => array(
        ALERTA_SECUNDARIO => 'secondary',
        ALERTA_INFO => 'primary',
        ALERTA_SUCESSO => 'success',
        ALERTA_RISCO => 'warning',
        ALERTA_ERRO => 'alert',
        ALERTA_SISTEMA => 'primary'
    ),
    'fechavel' => array(
        'atributo' => 'data-closable',
        'botao' => '<button class="close-button" aria-label="Fechar alerta" type="button" data-close>
                        <span aria-hidden="true">&times;</span>
                    </button>'
    ),
    'titulo' => array('abre'=>'<h5>','fecha'=>'</h5>'),
    'mensagem' => array('abre'=>'<p>','fecha'=>'</p>')
);