<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of interface Clickable_element
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
interface Clickable_element{

    /**
     * Adiciona um evento angular clicável. 
     * 
     * @param string $event
     * @return void
     */
    public function addNGClickEvent($event);

    public function setNGClick($event);
    public function getNGClick();
    
    /**
     * Adiciona um evento clicável. 
     * 
     * @param string $event
     * @return void
     */
    public function addClickEvent($event);

    public function setClickEvents($event);
    public function getClickEvents();

    /**
     * Adiciona um evento data clicável. 
     * 
     * @param string $event
     * @return void
     */
    public function addDataClickEvent($event);

    public function setDataClick($event);
    public function getDataClick();
}
