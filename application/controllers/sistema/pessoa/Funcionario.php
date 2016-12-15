<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Funcionario
 *
 * @author Thiago Moura
 */
class Funcionario extends MY_Controller{
	public function __construct() {
        parent::__construct('sistema/pessoa/funcionario',TRUE);
    }
	
	public function cadastro(){
		$data = [
			'titulo' => 'Cadastro Funcionário'
		];
		$this->twig->display('sistema/pessoa/funcionario/cadastro', $data);
	}
}
