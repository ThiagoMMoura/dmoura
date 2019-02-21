<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of user
 *
 * - Colunas: id, alias, email, senha, nivel
 * @author Thiago Moura
 */
class User_model extends MY_Model{
    
    public function __construct(){
        parent::__construct('user',array('id','alias','email','senha','nivel'));
    }
    
    public function valida_usuario($dados){
        $select = array();
        if(array_key_exists('alias', $dados)){
            $select['where']['alias'] = $dados['alias'];
        }else{
            $select['where']['email'] = $dados['email'];
        }
        $select['where']['senha'] = $dados['senha'];
//        $select['where']['ativo'] = TRUE;
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