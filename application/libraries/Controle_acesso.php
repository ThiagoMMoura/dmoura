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
    private $sistema;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->sistema = $this->CI->config->item('sistema');
        //$this->CI->config->load('areas_acesso');
        
        if($this->sistema){
            if(!$this->logado()){
                redirect($this->CI->config->item('login-required'));
            }elseif(!$this->nivel(NIVEL_OPERARIO)){
                redirect($this->CI->config->item('level-required'));
            }
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
    
    public function area($area){
        $this->load->model('permissao_model');
        $select = [
            'where' => ['idpermissao' => $area,'a.iduser' => $this->session->userdata('id')],
            'join' => ['alocado a','a.idsetor = permissao.idsetor']
        ];
        if($this->permissao_model->selecionar($select)){
            log_message('DEBUG', var_dump($this->permissao_model->registros()));
        }
        return TRUE;
    }
    
    public function funcao($area){
        return $this->area($area);
    }
    
    public function nivel($nivel){
        return $this->CI->session->nivel<=$nivel;
    }

}
