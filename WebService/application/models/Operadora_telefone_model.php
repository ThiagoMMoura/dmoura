<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Operadora_telefone_model
 *
 * @author Thiago Moura
 */
class Operadora_telefone_model extends MY_Model{
    public function __construct(){
        parent::__construct('operadora_telefone',array('id','operadora'));
    }
    
    public function obter_id_operadora(){
        $select['select'] = 'id, operadora';
        $select['order_by'] = 'operadora';
        $this->selecionar($select);
        $lista = array();
        foreach($this->registros() as $val){
            $lista[$val['id']] = $val['operadora'];
        }
        return $lista;
    }
}
