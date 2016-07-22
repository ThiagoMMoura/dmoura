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

/**
 * Imprime menu com estilo pré configurado.
 * 
 * @param array $lista_menu
 * @param string $estilo
 * @param bool $submenu
 * @return string
 */
function imprimir_menu($lista_menu,$estilo = 'padrao',$submenu = FALSE){
    $CI =& get_instance();
    $estilos = $submenu?$CI->config->item('estilos_submenu'):$CI->config->item('estilos_menu');
    if(isset($estilos[$estilo])){
        $estilos = $estilos[$estilo];
    }
    $menu = '<ul';
    if(isset($estilos['ul-class'])){
        $menu .= imprimir_atributos('class',$estilos['ul-class']);
    }
    if(isset($estilos['ul-atributos'])){
        $menu .= imprimir_atributos($estilos['ul-atributos']);
    }
    $menu .= '>';
    foreach($lista_menu as $v){
        $class = '';
        if(isset($estilos['li-class'])){
            $class .= $estilos['li-class'];
        }
        $menu .= '<li';
        if(isset($estilos['li-atributos'])){
            $menu .= imprimir_atributos($estilos['li-atributos']);
        }
        if(is_array($v)){
            if(isset($v['li-class'])){
                $class .= ' ' . $v['li-class'];
            }
            $menu .= ($class!=NULL?' class="' . $class . '">':'>');
            $titulo = isset($v['titulo'])?$v['titulo']:'';
            if(isset($v['icone'])){
                $titulo = '<i class="' . $v['icone'] . '"></i> <span>' . $titulo . '</span>';
            }
            if(isset($v['url'])){
                $menu .= '<a href="' . $v['url'] . '">' . $titulo . '</a>';
            }else{
                $menu .= $titulo;
            }
            if(isset($v['submenu'])){
                $menu .= imprimir_menu($v['submenu'],$estilo,TRUE);
            }
        }else{
            $menu .= ($class!=NULL?' class="' . $class . '">':'>') . $v;
        }
        $menu .= '</li>';
    }
    return $menu . '</ul>';
}

/**
 * Imprime atributos de tags html.
 * 
 * @param mixed $atributo
 * @param string $valor
 * @return string
 */
function imprimir_atributos($atributo,$valor = ''){
    $imprime = '';
    if(is_array($atributo)){
        foreach($atributo as $attr => $v){
            $imprime .= ' ' . $attr . '="' . $v . '"';
        }
    }else{
        $imprime = ' ' . $atributo . '="' . $valor . '"';
    }
    return $imprime;
}
