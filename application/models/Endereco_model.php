<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Endereco_model
 *
 * @author Thiago Moura
 */
class Endereco_model extends MY_Model{
    public function __construct(){
        parent::__construct('endereco',array('cep','uf','municipio','bairro','logradouro','num_ini','num_fim','lado','complemento'));
    }
    
    public function consulta_cep($cep){
        $select['where']['cep'] = str_replace('-', '', $cep);
        $select['order_by'] = 'logradouro';
        $this->selecionar($select);
        return $this->registro();
    }
}
