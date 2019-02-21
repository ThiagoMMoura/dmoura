<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Dashboard
 *
 * @author Thiago Moura
 */
class Redireciona extends CI_Controller{
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        redirect('sistema/autenticacao');
    }
}

