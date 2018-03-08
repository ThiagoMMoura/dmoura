<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Endereco_pessoa_model
 *
 * - Colunas: id, nome, email, cep, numero, complemento, senha, nivel,
 * tel_principal, resenha, ativo
 * @author Thiago Moura
 */
class Endereco_pessoa_model extends MY_Model{
    
    public function __construct(){
        parent::__construct('endereco_pessoa',array('id','destinatario','cep','tipo','numero','complemento','referencia','principal','idpessoa'));
    }
    
}