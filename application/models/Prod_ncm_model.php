<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Prod_ncm_model
 *
 * @author Thiago Moura
 */
class Prod_ncm_model extends MY_Model{
    public function __construct(){
        parent::__construct('prod_ncm',array('id','cod','descricao','ncm'));
    }
    
    public function has_recorded($cod,$ncm){
        $select = array();
        $select['where']['cod'] = $cod;
        $select['where']['ncm'] = $ncm;
        $this->selecionar($select);
        return $this->registro();
    }
}
