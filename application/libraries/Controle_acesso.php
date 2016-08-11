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
    
    private $CI;
    private $_area_restrita = FALSE;
    private $_permissoes = array();
    private $_pai = '';
    private $_controle = '';
    private $_metodo = '';
    
    public function __construct($data) {
        $this->CI =& get_instance();
        if(array_key_exists('area_restrita', $data)){
            $this->_area_restrita = $data['area_restrita'];
        }
        $this->CI->config->load('areas_acesso');
        $this->_pai = $data['pai'];
        $this->_controle = $data['controle'];
        $this->_metodo = $data['metodo'];
        
        if($this->_area_restrita && $this->logado()){
            $this->CI->load->model('permissao_model');
            $select['select'] = 'area, liberado';
            $select['where'] = 'grupo = ' . $this->CI->session->nivel;
            $select['order_by'] = 'area';
            $this->CI->permissao_model->selecionar($select);
            $this->_permissoes = $this->CI->permissao_model->registros();
        }
    }
    
    /**
     * Retorna <b>TRUE</b> se o usuário estiver logado, <b>FALSE</b> caso contrário.
     * 
     * @return boolean
     */
    public function logado() {
        return ($this->CI->session->has_userdata('logado') && $this->CI->session->logado);
    }
    
    public function obter_id_url($url){
        foreach($this->CI->config->item('areas_acesso')['url'] as $k => $area){
            if($area['url']===$url){
                return $k;
            }
        }
        return FALSE;
    }
    
    public function obter_id_objeto($idt){
        foreach($this->CI->config->item('areas_acesso')['objeto'] as $k => $objeto){
            if($objeto['idt']===$idt){
                return $k;
            }
        }
        return FALSE;
    }
    
    public function obter_url_id($id){
        if(array_key_exists($id, $this->CI->config->item('areas_acesso')['url'])){
            return $this->CI->config->item('areas_acesso')['url'][$id];
        }
        return FALSE;
    }
    
    public function obter_objeto_id($id){
        if(array_key_exists($id, $this->CI->config->item('areas_acesso')['objeto'])){
            return $this->CI->config->item('areas_acesso')['objeto'][$id];
        }
        return FALSE;
    }
    
    private function _liberado($id){
        foreach($this->_permissoes as $permissao){
            if($permissao['area']===$id){
                return $permissao['liberado'];
            }
        }
    }
    
    public function acesso_permitido($id){
        if($this->_area_restrita && $id!=NULL){
            if($this->logado()){
                return $this->_liberado($id);
            }
            return FALSE;
        }
        return TRUE;
    }
    
    public function pai($pai = ''){
        if($pai==NULL){
            $pai = $this->_pai;
        }
        
        return $this->acesso_permitido($this->obter_id_url($pai));
    }
    
    public function controle($controle = ''){
        if($controle==NULL){
            $controle = $this->_controle;
        }
        return $this->acesso_permitido($this->obter_id_url($controle));
    }
    
    public function metodo($url = '',$add_controle = TRUE){
        if($url==NULL){
            $url = $this->_controle . '/' . $this->_metodo;
        }else if($add_controle){
            $url = $this->_controle . '/' . $url;
        }
        
        return $this->acesso_permitido($this->obter_id_url($url));
    }
    
    public function objeto_permitido($id){
        if($id!=NULL){
            if($this->logado()){
                return $this->_liberado($id);
            }
            return FALSE;
        }
        return TRUE;
    }
    
    public function exibe_objeto($idt){
        return $this->objeto_permitido($this->obter_id_objeto($idt));
    }
}
