<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Cidade_model
 *
 * @author Thiago Moura
 */
class Municipio_model extends MY_Model{
    public function __construct(){
        parent::__construct('municio',array('id','uf','nome'));
    }
    
    public function municio_da_uf($uf){
        $select['select'] = 'id, nome';
        $select['where']['uf'] = $uf;
        $select['order_by'] = 'nome';
        $this->selecionar($select);
        $lista = array();
        foreach($this->registros() as $val){
            $lista[$val['id']] = $val['nome'];
        }
        return $lista;
    }
}
