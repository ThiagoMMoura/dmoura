<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * Description of permissao
 *
 * - Colunas: id, nome, descricao, pai
 * @author Thiago Moura
 */
class Grupo_permissoes_model extends MY_Model{
    
    public function __construct(){
        parent::__construct('grupo_permissoes',array('id','nome','descricao','fixo'));
    }
}
