<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Email_contato_model
 *
 * - Colunas: id, nome, email, cep, numero, complemento, senha, nivel,
 * tel_principal, resenha, ativo
 * @author Thiago Moura
 */
class Email_contato_model extends MY_Model{
    
    public function __construct(){
        parent::__construct('email_contato',array('id','email','descricao','idpessoa'));
    }
    
}