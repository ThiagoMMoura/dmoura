<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of permissao
 *
 * - Colunas: id, idsetor, idpermissao, acesso, tipo
 * @author Thiago Moura
 */
class Permissao_model extends MY_Model{
    
    public function __construct(){
        parent::__construct('permissao',array('id','idsetor','idpermissao','acesso','tipo'));
    }
    
}