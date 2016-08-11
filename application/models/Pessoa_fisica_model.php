<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Pessoa_fisica_model
 * 
 * - Colunas: id, cpf, rg, nascimento, sexo, nacionalidade, naturalidade, estado_civil, pessoa
 * @author Thiago Moura
 */
class Pessoa_fisica_model {
    public function __construct(){
        parent::__construct('pessoa_fisica',array('id','cpf','rg','nascimento','sexo','nacionalidade','naturalidade','estado_civil','pessoa'));
    }
}
