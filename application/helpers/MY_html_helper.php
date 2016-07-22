<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

function criar_menu($lista_menu,$class = '',$tipo = ''){
    $menu = '<ul' . ($class!=NULL?' class="' . $class . '" ':'') . '>';
    foreach($lista_menu as $k => $v){
        if(is_array($v)){
            $menu .= '<li' . (isset($v['li-class'])?' class="' . $v['li-class'] . '" ':'') . '>';
            $titulo = isset($v['titulo'])?$v['titulo']:'';
            if(isset($v['url'])){
                $menu .= '<a href="' . $v['url'] . '">' . $titulo . '</a>';
            }else{
                $menu .= $titulo;
            }
            if(isset($v['submenu'])){
                $menu .= criar_menu($v['submenu'],(isset($v['submenu-class'])?$v['submenu-class']:''),$tipo);
            }
        }else{
            $menu .= '<li>' . $v;
        }
        $menu .= '</li>';
    }
    return $menu . '</ul>';
}
