<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'sistema/support/CPPH.php';
/**
 * Description of central permitting processing
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
     * @return bool
     */
    public function logado() {
        return ($this->CI->session->has_userdata('logado') && $this->CI->session->logado);
    }
    
    /**
     * Retorna <code>TRUE</code> caso o usuário possua permissão para a <code>id</code>
     * e <code>tipo</code> informados por parâmetro.
     * 
     * @param string $id
     * @param string $tipo
     * @return bool
     */
    public function permissao($id,$tipo){
        if($id!=NULL){
            $this->CI->load->model('permissao_model');
            $select = [
                'where' => ['idpermissao' => $id,'a.user_id' => $this->CI->session->userdata('id'),'acesso'=>TRUE,'tipo'=>$tipo],
                'join' => ['user_alocado_setor a','a.setor_id = permissao.idsetor']
            ];
            if($this->CI->permissao_model->selecionar($select)){
                return $this->CI->permissao_model->num_registros() > 0;
            }
            return FALSE;
        }
        return TRUE;
    }
    
    /**
     * Retorna <code>TRUE</code> caso o usuário possua permissão para a Área com
     * a <code>id</code> informada por parâmetro.
     * 
     * @param string $id
     * @return bool
     */
    public function area($id){
        return $this->permissao($id,'zone');
    }
    
    /**
     * Retorna <code>TRUE</code> caso o usuário possua permissão para a Função com
     * a <code>id</code> informada por parâmetro.
     * 
     * @param string $id
     * @return bool
     */
    public function funcao($id){
        return $this->permissao($id,'func');
    }
    
    /**
     * Retorna <code>TRUE</code> caso o usuário possua nível igual ou superior ao
     * informado por parâmetro.
     * 
     * @param string $nivel
     * @return bool
     */
    public function nivel($nivel){
        return $this->CI->session->nivel<=$nivel;
    }

}
