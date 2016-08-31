<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Telefone_model
 *
 * @author Thiago Moura
 */
class Telefone_model extends MY_Model{
    public function __construct(){
        parent::__construct('telefone',array('id','ddd','telefone','tipo','operadora','pessoa'));
    }
}
