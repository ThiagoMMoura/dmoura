<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Cidade_model
 *
 * @author Thiago Moura
 */
class Cidade_model extends MY_Model{
    public function __construct(){
        parent::__construct('cidade',array('id','estado','nome'));
    }
    
    public function cidades_do_estado($id){
        $select['select'] = 'id, nome';
        $select['where']['estado'] = $id;
        $select['order_by'] = 'nome';
        $this->selecionar($select);
        $lista = array();
        foreach($this->registros() as $val){
            $lista[$val['id']] = $val['nome'];
        }
        return $lista;
    }
}
