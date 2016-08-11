<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Pessoa_model
 *
 * - Colunas: id, nome, email, cep, numero, complemento, senha, grupo, tipo
 * @author Thiago Moura
 */
class Pessoa_model {
    
    public function __construct(){
        parent::__construct('pessoa',array('id','nome','email','cep','numero','complemento','senha','grupo','tipo'));
    }
}
