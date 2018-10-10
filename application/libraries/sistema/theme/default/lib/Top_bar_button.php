<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Top_bar_button
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Top_bar_button extends Button{
    
    /**
     * @var string
     */
    protected $type;

    /**
     * Construtor da classe Form_button.
     * 
     * @param Component $component
     * @param Container $parent
     * @param mixed $key
     * @param mixed $previousSiblingKey
     */
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        $this->type = $component->getAttr('type');
        unset($component->getAttrs()['type']);
        parent::__construct($component, $parent, $key, $previousSiblingKey);
    }

    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/button.twig');
        
        return $template->render(array(
                  'element_type' => 'top_bar_button'
                , 'id'           => $this->getId()
                , 'title'        => $this->getTitle()
                , 'icon'         => $this->getIcon()
                , 'class'        => $this->getClass()
                , 'attributes'   => $this->getTagAttributes()
                , 'title_class'  => $this->getAttr('title_class')
        ));
    }
}
