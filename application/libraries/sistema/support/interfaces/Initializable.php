<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Initializable
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
interface Initializable
{
    /**
     * Função de inicialização da classe.
     *
     * @return static
     */
    public function initialize();
}
