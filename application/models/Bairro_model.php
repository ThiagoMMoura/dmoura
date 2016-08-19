<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Bairro_model
 *
 * @author Thiago Moura
 */
class Bairro_model extends MY_Model{
    public function __construct(){
        parent::__construct('bairro',array('id','uf','municipio','nome'));
    }
}
