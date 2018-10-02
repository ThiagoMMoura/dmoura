<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of interface Element navigation
 *
 * @package	Application
 * @subpackage	Libraries\sistema\theme\default\lib
 * @author Thiago Moura
 */
interface Element_navigation{

    /**
     * Ajusta configurações de responsividade.
     * 
     * @param mixed $sibling
     * @return mixed
     */
    public function setPreviousSibling($sibling);

    public function setNextSibling($sibling);

    public function getPreviousSibling();

    public function getNextSibling();

    /**
     * @return Container
     */
    public function getParent();
    
    /**
     * @return bool
     */
    public function hasPreviousSibling();
    
    /**
     * @return bool
     */
    public function hasNextSibling();

}
