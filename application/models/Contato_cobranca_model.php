<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Contato_cobranca_model
 *
 * - Colunas: id, nome, email, cep, numero, complemento, senha, nivel,
 * tel_principal, resenha, ativo
 * @author Thiago Moura
 */
class Contato_cobranca_model extends MY_Model{
    
    public function __construct(){
        parent::__construct('contato_cobranca',array('id','nome','telefone','idoperadora','parentesco','cep','numero','complemento','idpessoafisica'));
    }
    
}
