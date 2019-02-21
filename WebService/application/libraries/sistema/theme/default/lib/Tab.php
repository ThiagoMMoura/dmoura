<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Tab
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Tab extends Element_container {
    
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        log_message('DEBUG','Construct Tab.');
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        $this->elements->transform(function($element,$key){
            return $element->getAttr('is-active')
                    ? $element->addClass('is-active')
                    : $element;
        });
    }

    public static function getTabObject(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        switch($component->name){
            case 'tab_panel':
                return new Tab_panel($component, $parent, $key, $previousSiblingKey);
            default:
                return new Tab($component, $parent, $key, $previousSiblingKey);
        }
    }
    
    public function getTabButtons(){
        $buttons = '';
        foreach ($this->getElements() as $tab){
            if ($tab instanceof Tab_panel) {
                $buttons .= $tab->getTabButton();
            }
        }
        return $buttons;
    }
    
    public function __get($name){
        switch ($name) {
            case 'tab_buttons':
                return $this->getTabButtons();
            default:
                return Container::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['tab_buttons'];
        
        return in_array($name,$attributes) || Container::__isset($name);
    }
    
    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/tab.twig');
        
        return $template->render(array(
                  'element_type' => $this->getElementName()
                , 'id'           => $this->getId()
                , 'tab_buttons'  => $this->getTabButtons()
                , 'tabs_content' => parent::getHTML()
        ));
    }
}
