<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Mainmenu
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Mainmenu extends Element_container {
    
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
        /*$this->elements = new Elements_collection(
            $this->getElements()->map(function($element){
                log_message('DEBUG', self::class.' Element Tyep: '.$element->getElementType());
                return $element->getElementType() === 'mainmenu';
            })->all()
            , $this);*/
    }
    
    public static function getMainmenuObject(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        switch($component->name){
            case 'mainmenu_sub':
                return new Mainmenu_sub($component, $parent, $key, $previousSiblingKey);
            case 'mainmenu_item':
                return new Mainmenu_item($component, $parent, $key, $previousSiblingKey);
            default:
                return new Mainmenu($component, $parent, $key, $previousSiblingKey);
        }
    }
    
    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/main_menu.twig');
        
        return $template->render(array(
                  'element_type' => $this->getElementName()
                , 'id'           => $this->getId()
                , 'type'         => 'accordion'
                , 'content'      => $this->getElements()->getHTML()
        ));
    }
}
