<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of permissoes
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Controle_acesso {
    
    private $_area_restrita = FALSE;
    private $_permissoes = array();
    private $_controle = '';
    private $_pagina = '';
    
    public function __construct($data) {
        if(array_key_exists('area_restrita', $data)){
            $this->_area_restrita = $data['area_restrita'];
        }
        $this->config->load('areas_acesso');
        $this->_controle = $data['controle'];
        
        if($this->_area_restrita && $this->logado()){
            //carrega permissões do usuário no banco
        }
    }
    
    /**
     * Retorna <b>TRUE</b> se o usuário estiver logado, <b>FALSE</b> caso contrário.
     * 
     * @return boolean
     */
    public function logado() {
        return ($this->session->has_userdata('logado') && $this->session->logado);
    }
    
    public function obter_id_area($url){
        foreach($this->config->item('areas_acesso') as $k => $area){
            if($area['url']===$url){
                return $k;
            }
        }
        return FALSE;
    }
    
    public function obter_area($id){
        if(array_key_exists($id, $this->config->item('areas_acesso'))){
            return $this->config->item('areas_acesso')[$id];
        }
        return FALSE;
    }
    
    public function tem_permissao_vizualizar($tag = '', $add_url = TRUE){
        if($tag==NULL){
            return TRUE;
        }else if($add_url){
            $tag = $this->_controle . '/' . $this->_pagina . '#' . $tag;
        }
        
        return $this->tem_permissao_acesso($this->obter_id_area($tag));
    }
    
    public function tem_permissao_acesso_funcao($funcao = '',$add_controle = TRUE){
        if($funcao==NULL){
            return TRUE;
        }else if($add_controle){
            $funcao = $this->_controle . '/' . $funcao;
        }
        
        return $this->tem_permissao_acesso($this->obter_id_area($funcao));
    }
    
    public function tem_permissao_acesso_pagina($pagina = '',$add_controle = TRUE){
        if($pagina==NULL){
            $pagina = $this->_controle . '/' . $this->_pagina;
        }else if($add_controle){
            $pagina = $this->_controle . '/' . $pagina;
        }
        
        return $this->tem_permissao_acesso($this->obter_id_area($pagina));
    }
    
    public function tem_permissao_acesso_controle($controle = ''){
        if($controle==NULL){
            $controle = $this->_controle;
        }
        return $this->tem_permissao_acesso($this->obter_id_area($controle));
    }
    
    public function tem_permissao_acesso($id){
        if($this->_area_restrita && $id!=NULL){
            if($this->logado()){
                foreach($this->_permissoes as $permissao){
                    if($permissao['id_area']===$id){
                        return $permissao['liberado'];
                    }
                }
            }
            return FALSE;
        }
        return TRUE;
    }
    
}
