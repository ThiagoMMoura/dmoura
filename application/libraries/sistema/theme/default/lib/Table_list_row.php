<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Table_list_row
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Table_list_row extends Element_container {
    
    /*public function __construct(Component $component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
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
    }*/
    public function getHeadHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/table_list.twig');

        $columns = '';
        foreach ($this->getElements()->all() as $element){
            $columns .= $element->getHeadHTML();
        }

        return $template->render(array(
                  'element_type' => 'table_head'
                , 'id'           => $this->getId()
                , 'class'        => $this->getClass()
                , 'columns'      => $columns
        ));
    }

    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/table_list.twig');
        
        return $template->render(array(
                  'element_type' => 'table_body'
                , 'id'           => $this->getId()
                , 'class'        => $this->getClass()
                , 'columns'      => parent::getHTML()
        ));
    }
}
