<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of permissoes
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Controle_acesso {
    public function __construct() {
        $this->config->load('permissoes');
    }
    
}
