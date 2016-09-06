<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of Pessoa_model
 *
 * - Colunas: id, nome, email, cep, numero, complemento, senha, grupo, tipo,
 * tel_principal, resenha, ativo
 * @author Thiago Moura
 */
class Pessoa_model extends MY_Model{
    
    const WEBMASTER = 0;
    const ADMINISTRADOR = 1;
    const FUNCIONARIO = 2;
    const FORNECEDOR = 3;
    const CLIENTE = 4;
    const CLIENTE_JURIDICO= 5;
    
    public function __construct(){
        parent::__construct('pessoa',array('id','nome','email','cep','numero','complemento','senha','grupo','tipo','tel_principal','resenha', 'ativo'));
    }
    
    public function valida_usuario($dados){
        $select = array();
        if(array_key_exists('id', $dados)){
            $select['where']['id'] = $dados['id'];
        }else{
            $select['where']['email'] = $dados['email'];
        }
        $select['where']['senha'] = $dados['senha'];
        $select['where']['ativo'] = TRUE;
        $this->selecionar($select);
        
        return $this->num_registros()==1;
    }
}
