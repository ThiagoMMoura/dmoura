<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Dashboard
 *
 * @author Thiago Moura
 */
class Dashboard extends MY_Controller{
    public function __construct() {
        parent::__construct('sistema/dashboard','Painel de Instrumentos','atalhos');
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
        $this->vc->display('sistema/dashboard', $data);
    }
}
