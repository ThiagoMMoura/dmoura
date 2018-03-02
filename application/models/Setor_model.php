<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of setor
 *
 * - Colunas: id, titulo, descricao
 * @author Thiago Moura
 */
class Setor_model extends MY_Model{
    
    public function __construct(){
        parent::__construct('setor',array('id','titulo','descricao'));
    }
    
    public function obter_id_setor(){
        $select['select'] = 'id, titulo';
        $select['order_by'] = 'titulo';
        $this->selecionar($select);
        $lista = array();
        foreach($this->registros() as $val){
            $lista[$val['id']] = $val['titulo'];
        }
        return $lista;
    }
}