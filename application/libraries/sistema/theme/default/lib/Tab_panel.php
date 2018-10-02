<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Tab
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Tab_panel extends Element_container {
    
    /*public function __construct(Component $component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
    }*/
    
    public function getTabButton(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/tab.twig');
        
        return $template->render(array(
                  'element_type' => 'tab_button'
                , 'id'           => $this->getId()
                , 'class'        => $this->getClass()
                , 'title'        => $this->getAttr('title')
        ));
    }

    public function __get($name){
        switch ($name) {
            case 'tab_button':
                return $this->getTabButton();
            default:
                return parent::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['tab_button'];
        
        return in_array($name,$attributes) || parent::__isset($name);
    }

    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/tab.twig');
        
        return $template->render(array(
                  'element_type' => $this->getElementName()
                , 'id'           => $this->getId()
                , 'class'        => $this->getClass()
                , 'content'      => parent::getHTML()
        ));
    }
}
