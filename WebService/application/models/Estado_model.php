<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Estado_model
 *
 * @author Thiago Moura
 */
class Estado_model extends MY_Model{
    public function __construct(){
        parent::__construct('estado',array('uf','nome'));
    }
    
    public function obter_uf_estado(){
        $select['select'] = 'uf, nome';
        $select['order_by'] = 'nome';
        $this->selecionar($select);
        $lista = array();
        foreach($this->registros() as $val){
            $lista[$val['uf']] = $val['nome'];
        }
        return $lista;
    }
}
