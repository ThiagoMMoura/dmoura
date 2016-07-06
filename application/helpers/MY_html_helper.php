<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Adiciona uma tag link ao Head da página.
 * 
 * @param mixed $href
 * @param string $rel
 * @param string $type
 * @param string $title
 * @param string $media
 * @param array $attr
 * @return boolean
 */
function add_head_link($href,$rel='stylesheet',$type='text/css',$title = '', $media = '',$attr = array()){
    $CI =& get_instance();
    $itens = $CI->config->item('head-itens-antes-todos');
    if($href!=NULL){
        if(is_array($href)){
            $itens[] = array('link'=>$href);
        }else{
            $itens[] = array('link'=>array_merge(array('href'=>$href,'rel'=>$rel,'type'=>$type,'title'=>$title,'media'=>$media),$attr));
        }
        $CI->config->set_item('head-itens-antes-todos',$itens);
        return TRUE;
    }
    return FALSE;
}

/**
 * Adiciona uma tag script ao Head da página.
 * 
 * @param mixed $src
 * @param string $codigo
 * @param string $type
 * @param array $attr
 * @return boolean
 */
function add_head_script($src,$codigo='',$type='text/javascript',$attr = array()){
    $CI =& get_instance();
    $itens = $CI->config->item('head-itens-antes-todos');
    if($src!=NULL){
        if(is_array($src)){
            $itens[] = array('script'=>$src);
        }else{
            $itens[] = array('script'=>array_merge(array('src'=>$src,'codigo'=>$codigo,'type'=>$type),$attr));
        }
        $CI->config->set_item('head-itens-antes-todos',$itens);
        return TRUE;
    }
    return FALSE;
}

/**
 * Adiciona uma tag meta ao Head da página.
 * 
 * @param mixed $name
 * @param string $content
 * @param string $type
 * @param array $attr
 * @return boolean
 */
function add_head_meta($name,$content='',$type='name',$attr = array()){
    $CI =& get_instance();
    $itens = $CI->config->item('head-itens-antes-todos');
    if($name!=NULL){
        if(is_array($name)){
            $itens[] = array('meta'=>$name);
        }else{
            $itens[] = array('meta'=>array_merge(array('name'=>$name,'content'=>$content,'type'=>$type),$attr));
        }
        $CI->config->set_item('head-itens-antes-todos',$itens);
        return TRUE;
    }
    return FALSE;
}

/**
 * Altera o titulo da página.
 * 
 * @param string $titulo
 * @return boolean
 */
function add_head_title($titulo){
    $CI =& get_instance();
    $itens = $CI->config->item('head-itens-antes-todos');
    if($titulo!=NULL){
        $itens['title'] = $titulo;
        $CI->config->set_item('head-itens-antes-todos',$itens);
        return TRUE;
    }
    return FALSE;
}

/**
 * Adiciona a tag ao Head da página.
 * 
 * @param mixed $mixed
 * @param string $tag
 * @return boolean
 */
function add_head_item($mixed,$tag = 'link'){
    if($mixed!=NULL){
        if($tag==='link'){
            return add_head_link($mixed);
        }else if($tag==='script'){
            return add_head_script($mixed);
        }else if($tag==='meta'){
            return add_head_meta($mixed);
        }else if($tag==='title'){
            return add_head_title($mixed);
        }
    }
    return FALSE;
}

/**
 * Adiciona uma tag link ao fim do Head da página.
 * 
 * @param mixed $href
 * @param string $rel
 * @param string $type
 * @param string $title
 * @param string $media
 * @param array $attr
 * @return boolean
 */
function add_head_link_final($href,$rel='stylesheet',$type='text/css',$title = '', $media = '',$attr = array()){
    $CI =& get_instance();
    $itens = $CI->config->item('head-itens-depois-todos');
    if($href!=NULL){
        if(is_array($href)){
            $itens[] = array('link'=>$href);
        }else{
            $itens[] = array('link'=>array_merge(array('href'=>$href,'rel'=>$rel,'type'=>$type,'title'=>$title,'media'=>$media),$attr));
        }
        $CI->config->set_item('head-itens-depois-todos',$itens);
        return TRUE;
    }
    return FALSE;
}

/**
 * Adiciona uma tag script ao fim do Head da página.
 * 
 * @param mixed $src
 * @param string $codigo
 * @param string $type
 * @param array $attr
 * @return boolean
 */
function add_head_script_final($src,$codigo='',$type='text/javascript',$attr = array()){
    $CI =& get_instance();
    $itens = $CI->config->item('head-itens-depois-todos');
    if($src!=NULL){
        if(is_array($src)){
            $itens[] = array('script'=>$src);
        }else{
            $itens[] = array('script'=>array_merge(array('src'=>$src,'codigo'=>$codigo,'type'=>$type),$attr));
        }
        $CI->config->set_item('head-itens-depois-todos',$itens);
        return TRUE;
    }
    return FALSE;
}

/**
 * Adiciona uma tag meta ao fim do Head da página.
 * 
 * @param mixed $name
 * @param string $content
 * @param string $type
 * @param array $attr
 * @return boolean
 */
function add_head_meta_final($name,$content='',$type='name',$attr = array()){
    $CI =& get_instance();
    $itens = $CI->config->item('head-itens-depois-todos');
    if($name!=NULL){
        if(is_array($name)){
            $itens[] = array('meta'=>$name);
        }else{
            $itens[] = array('meta'=>array_merge(array('name'=>$name,'content'=>$content,'type'=>$type),$attr));
        }
        $CI->config->set_item('head-itens-depois-todos',$itens);
        return TRUE;
    }
    return FALSE;
}

/**
 * Adiciona a tag ao fim do Head da página.
 * 
 * @param mixed $mixed
 * @param string $tag
 * @return boolean
 */
function add_head_item_final($mixed,$tag = 'link'){
    if($mixed!=NULL){
        if($tag==='link'){
            return add_head_link_final($mixed);
        }else if($tag==='script'){
            return add_head_script_final($mixed);
        }else if($tag==='meta'){
            return add_head_meta_final($mixed);
        }
    }
    return FALSE;
}

/**
 * Imprime todo o conteudo da tag head.
 * 
 * @return string
 */
function imprime_conteudo_head(){
    $CI =& get_instance();
    $head = '';
    foreach($CI->config->item('head-itens-antes-todos') as $v){
        if(array_key_exists('script', $v)){          
            $head .= script_tag($v['script']);
        }else if(array_key_exists('link', $v)){          
            $head .= link_tag($v['link']);
        }else if(array_key_exists('meta', $v)){
            $head .= meta($v['meta']);
        }else if(array_key_exists('title', $v)){
            $head .= '<title>' . $v['title'] . '</title>';
        }
    }
    foreach($CI->config->item('head-itens-depois-todos') as $v){
        if(array_key_exists('script', $v)){          
            $head .= script_tag($v['script']);
        }else if(array_key_exists('link', $v)){          
            $head .= link_tag($v['link']);
        }else if(array_key_exists('meta', $v)){
            $head .= meta($v['meta']);
        }
    }
    return $head;
}

/**
 * Script
 * 
 * Gera uma tag script para um arquivo JavaScript.
 * 
 * @param mixed $src
 * @param string $codigo
 * @param string $type
 * @param bool $index_page
 * @return string
 */
function script_tag($src = '', $codigo = '', $type = 'text/javascript', $index_page = FALSE){
	$CI =& get_instance();
	$script = '<script ';

	if (is_array($src))
	{
		foreach ($src as $k => $v)
		{
			if ($k === 'src' && ! preg_match('#^([a-z]+:)?//#i', $v))
			{
				if ($index_page === TRUE)
				{
					$script .= 'src="'.$CI->config->site_url($v).'" ';
				}
				else
				{
					$script .= 'src="'.$CI->config->slash_item('base_url').$v.'" ';
				}
			}
			elseif($k === 'codigo')
			{
				$codigo = $v;
			}
			else
			{
				$script .= $k.'="'.$v.'" ';
			}
		}
	}
	else
	{
		if (preg_match('#^([a-z]+:)?//#i', $src))
		{
			$script .= 'src="'.$src.'" ';
		}
		elseif ($index_page === TRUE)
		{
			$script .= 'src="'.$CI->config->site_url($src).'" ';
		}
		else
		{
			$script .= 'src="'.$CI->config->slash_item('base_url').$src.'" ';
		}

		$script .= 'type="'.$type.'" ';

	}

	return $script.">".$codigo."</script>\n";
}

