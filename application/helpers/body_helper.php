<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Adiciona uma tag script ao Head da página.
 * 
 * @param mixed $src
 * @param string $codigo
 * @param string $type
 * @param array $attr
 * @return boolean
 */
function add_body_script($src,$codigo='',$type='text/javascript',$attr = array()){
    $CI =& get_instance();
    $itens = $CI->config->item('body-scripts');
    if($src!=NULL){
        if(is_array($src)){
            $itens[] = $src;
        }else{
            $itens[] = array_merge(array('src'=>$src,'codigo'=>$codigo,'type'=>$type),$attr);
        }
        $CI->config->set_item('body-scripts',$itens);
        return TRUE;
    }
    return FALSE;
}

/**
 * Imprime todo o conteudo da tag head.
 * 
 * @return string
 */
function imprime_body_scripts(){
    $CI =& get_instance();
    $head = '';
    foreach($CI->config->item('body-scripts') as $v){      
        $head .= script_tag($v);
    }
    return $head;
}