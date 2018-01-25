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
        parent::__construct('pessoa',array('id','nome','email','cep','numero','complemento','senha','nivel','tel_principal','resenha', 'ativo'));
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
        if(array_key_exists('nivel', $dados)){
            $select['where']['nivel <='] = $dados['nivel'];
        }
        $this->selecionar($select);
        
        return $this->num_registros()==1;
    }
	
    public function alterar_senha($dados, $id = '', $email = '', $senha_nova = ''){
        $senha_atual = $dados;
        if(is_array($dados)){
            $senha_atual = $dados['senha_atual'];
            $email = element('email',$dados);
            $id = element('id',$dados);
            $senha_nova = $dados['senha_nova'];
        }
        $por = 'id';
        if($id == NULL){
            $por = 'email';
            $id = $email;
        }
        //echo $email . '|' . $id . '|' . $senha_atual . '|' . $senha_nova;
        if($this->valida_usuario(array($por=>$id,'senha'=>$senha_atual))){
            return $this->alterar(array('senha'=>$senha_nova,'resenha'=>FALSE), array($por=>$id));
        }
        return FALSE;
    }
}
