<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Pessoa_model
 *
 * - Colunas: id, nome, email, cep, numero, complemento, senha, nivel,
 * tel_principal, resenha, ativo
 * @author Thiago Moura
 */
class Pessoa_model extends MY_Model{
    
    public function __construct(){
        parent::__construct('pessoa',array('id','nome','apelido','ativo','iduser'));
    }
    
}
