<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Funcionario
 *
 * - Colunas: id, rg, cargo, salario, pessoa_fisica, ferias
 * @author Thiago Moura
 */
class Funcionario_model extends MY_Model{
    public function __construct(){
        parent::__construct('funcionario',array('id','rg','cargo','salario','pessoa_fisica','ferias'));
    }
}
