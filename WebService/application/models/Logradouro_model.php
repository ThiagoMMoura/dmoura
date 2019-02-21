<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Logradouro_model
 *
 * @author Thiago Moura
 */
class Logradouro_model extends MY_Model{
    public function __construct(){
        parent::__construct('logradouro',array('id','uf','municipio','nome'));
    }
}
