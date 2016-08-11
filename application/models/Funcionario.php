<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Funcionario
 *
 * - Colunas: id, cargo, pessoa_fisica, ativo, ferias
 * @author Thiago Moura
 */
class Funcionario extends MY_Model{
    public function __construct(){
        parent::__construct('funcionario',array('id','cargo','pessoa_fisica','ativo','ferias'));
    }
}
