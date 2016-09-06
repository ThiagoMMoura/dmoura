<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Telefone_model
 *
 * @author Thiago Moura
 */
class Telefone_model extends MY_Model{
    public function __construct(){
        parent::__construct('telefone',array('id','ddd','telefone','tipo','operadora','pessoa'));
    }
    
    public function salvar_telefones($dados,$pessoa = FALSE){
        $id_primeiro = 0;
        foreach ($dados as $tel){
            $add = array('ddd'=>'','telefone'=>'','tipo'=>'','operadora'=>'0','pessoa'=>$pessoa);
            if(array_key_exists('ddd', $tel)){
                $add['ddd'] = $tel['ddd'];
            }
            if(array_key_exists('telefone', $tel)){
                $add['telefone'] = $tel['telefone'];
            }
            if(array_key_exists('tipo', $tel)){
                $add['tipo'] = $tel['tipo'];
            }
            if(array_key_exists('operadora', $tel)){
                $add['operadora'] = $tel['operadora'];
            }
            if($pessoa===FALSE && array_key_exists('pessoa', $tel)){
                $add['pessoa'] = $tel['pessoa'];
            }
            log_message('DEBUG', "TEL: " . print_r($tel,TRUE));
            if($add['telefone']>9999999 && $add['telefone']<100000000000 && $add['tipo']!=NULL && $add['pessoa']!=NULL){
                log_message('DEBUG', "ADD: " . print_r($add,TRUE));
                if($this->inserir($add) && $id_primeiro === 0){
                    $id_primeiro = $this->id_inserido();
                }
            }
        }
        return $id_primeiro;
    }
}
