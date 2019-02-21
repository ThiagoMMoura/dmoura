<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of alocado
 *
 * - Colunas: id, iduser, idsetor
 * @author Thiago Moura
 */
class Alocado_model extends MY_Model{
    
    public function __construct(){
        parent::__construct('alocado',array('id','iduser','idsetor'));
    }
}