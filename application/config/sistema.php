<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Lista de configurações do sistema.
 *
 * @author Thiago Moura
 */
$config['template-html'] = 'sistema/template-base';
$config['head-itens-antes-todos'] = array(
    array('meta'=>array('name'=>'x-ua-compatible','type'=>'http-equiv','content'=>'ie=edge')),
    array('meta'=>array('name'=>'viewport','type'=>'name','content'=>'width=device-width, initial-scale=1.0')),
    array('link'=>array('rel'=>'stylesheet','href'=>'assets/framework/')),
    array('link'=>array('rel'=>'stylesheet','href'=>'assets/css/sistema.css'))
);
$config['head-itens-depois-todos'] = array();
$config['body-scripts'] = array();
