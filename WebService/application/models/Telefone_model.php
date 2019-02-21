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
        parent::__construct('telefone',array('id','telefone','idtipo','idoperadora','idpessoa'));
    }
    
    public function salvar_telefones($dados,$pessoa = FALSE){
        $id_primeiro = array();
        foreach ($dados as $tel){
            $add = array('telefone'=>'','idtipo'=>'','idoperadora'=>'0','idpessoa'=>$pessoa);
            if(array_key_exists('telefone', $tel)){
                $add['telefone'] = $tel['telefone'];
            }
            if(array_key_exists('idtipo', $tel)){
                $add['idtipo'] = $tel['idtipo'];
            }
            if(array_key_exists('idoperadora', $tel)){
                $add['idoperadora'] = $tel['idoperadora'];
            }
            if($pessoa===FALSE && array_key_exists('idpessoa', $tel)){
                $add['idpessoa'] = $tel['idpessoa'];
            }
            if($add['telefone']>9999999 && $add['telefone']<100000000000 && $add['idtipo']!=NULL && $add['idpessoa']!=NULL){
                if($this->inserir($add)){
                    $id_primeiro[] = $this->id_inserido();
                }
            }
        }
        return $id_primeiro;
    }
    
    public function alterar_telefones($dados,$pessoa = FALSE){
        $id_primeiro = array();
        foreach ($dados as $tel){
            $add = array('telefone'=>'','idtipo'=>'','idoperadora'=>'0','idpessoa'=>$pessoa);
            if(array_key_exists('telefone', $tel)){
                $add['telefone'] = $tel['telefone'];
            }
            if(array_key_exists('idtipo', $tel)){
                $add['idtipo'] = $tel['idtipo'];
            }
            if(array_key_exists('idoperadora', $tel)){
                $add['idoperadora'] = $tel['idoperadora'];
            }
            if($pessoa===FALSE && array_key_exists('idpessoa', $tel)){
                $add['idpessoa'] = $tel['idpessoa'];
            }
            if($tel['id']>0 && $add['telefone']>9999999 && $add['telefone']<100000000000 && $add['idtipo']!=NULL && $add['idpessoa']!=NULL){
                if($this->alterar($add,array('id'=>$tel['id']))){
                    $id_primeiro[] = $this->id_inserido();
                }
            }
        }
        return $id_primeiro;
    }
}
