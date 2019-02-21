<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CPPH{
    
    /**
     * Obtém a referência da Central de Processamento de Permissões.
     * 
     * @return \Controle_acesso
     */
    public static function &getCPP(){
        return get_instance()->cpp;
    }
    
    /**
     * Retorna <code>TRUE</code> caso o usuário possua permissão para a Área com
     * a <code>id</code> informada por parâmetro.
     * 
     * @param string $id
     * @return bool
     */
    public static function area($id){
        return self::getCPP()->area($id);
    }
    
    /**
     * Retorna <code>TRUE</code> caso o usuário possua permissão para a Função com
     * a <code>id</code> informada por parâmetro.
     * 
     * @param string $id
     * @return bool
     */
    public static function funcao($id){
        return self::getCPP()->funcao($id);
    }
    
    /**
     * Retorna <code>TRUE</code> caso o usuário possua nível igual ou superior ao
     * informado por parâmetro.
     * 
     * @param string $nivel
     * @return bool
     */
    public static function nivel($nivel){
        return self::getCPP()->nivel($nivel);
    }
}