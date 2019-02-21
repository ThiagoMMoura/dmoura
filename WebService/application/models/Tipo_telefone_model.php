<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Tipo_telefone_model
 *
 * @author Thiago Moura
 */
class Tipo_telefone_model extends MY_Model{
    public function __construct(){
        parent::__construct('tipo_telefone',array('id','tipo'));
    }
    
    public function obter_id_tipo(){
        $select['select'] = 'id, tipo';
        $select['order_by'] = 'tipo';
        $this->selecionar($select);
        $lista = array();
        foreach($this->registros() as $val){
            $lista[$val['id']] = $val['tipo'];
        }
        return $lista;
    }
}
