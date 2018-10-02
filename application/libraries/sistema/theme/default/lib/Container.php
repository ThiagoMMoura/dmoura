<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of interface Container
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
interface Container{

    /**
     * Retorna todos os components do tipo especificado.
     * 
     * @param string $type
     * @param boolean $recursive = FALSE
     * @return array
     */
    public function getElementsOfType($type,$recursive = FALSE);

    public function getElements();

    /**
     * 
     * @param mixed $key
     * @return Element|Container
     */
    public function getElementByKey($key);
    
    public function whereInstanceOfRecursive($type);
}
