<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Lista de configurações do sistema.
 *
 * @author Thiago Moura
 */
$config['template-html'] = 'sistema/template-base';
$config['head-itens-antes-todos'] = array(
    array(
        'meta'=>array(
            'name'=>'UTF-8',
            'type'=>'charset'
        )
    )
);
$config['head-itens-depois-todos'] = array();
$config['scripts-finais'] = array();
