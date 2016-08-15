<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Pessoa_fisica_model
 * 
 * - Colunas: id, cpf, nascimento, sexo, nacionalidade, naturalidade, estado_civil, pessoa
 * @author Thiago Moura
 */
class Pessoa_fisica_model extends MY_Model{
    public function __construct(){
        parent::__construct('pessoa_fisica',array('id','cpf','nascimento','sexo','nacionalidade','naturalidade','estado_civil','pessoa'));
    }
}
