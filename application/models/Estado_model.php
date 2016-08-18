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
        parent::__construct('estado',array('id','sigla','nome'));
    }
}
