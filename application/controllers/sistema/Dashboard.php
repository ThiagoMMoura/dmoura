<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Dashboard
 *
 * @author Thiago Moura
 */
class Dashboard extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/dashboard','atalhos');
    }
    
    public function atalhos(){
        $data = [
            'titulo' => 'Painel de Instrumentos',
            'basepath' => BASEPATH,
            'self' => SELF,
            'fcpath' => FCPATH,
            'sysdir' => SYSDIR,
            'apppath' => APPPATH,
            'viewpath' => VIEWPATH
        ];
        $this->twig->display($this->config->item('theme') . 'dashboard', $data);
    }
}
