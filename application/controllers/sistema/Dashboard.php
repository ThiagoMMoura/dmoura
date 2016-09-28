<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Dashboard
 *
 * @author Thiago Moura
 */
class Dashboard extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/dashboard',TRUE);
    }
    
    public function index(){
        $this->_view("Painel de Instrumentos",'atalhos',parent::RELATIVO_CONTROLE);
    }
}
