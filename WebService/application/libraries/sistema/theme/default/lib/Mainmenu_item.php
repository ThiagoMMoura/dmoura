<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Mainmenu_sub
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Mainmenu_item extends Element {
    
    public function getTitle(){
        return $this->getAttr('title');
    }
    
    public function getURL(){
        return base_url($this->getAttr('url'));
    }
    
    public function getIcon(){
        return $this->getAttr('icon');
    }
    
    public function isActive(){
        return $this->getURL() == current_url();
    }
    
    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/main_menu.twig');
        log_message('DEBUG', 'getHTML '.self::class);
        return $template->render(array(
                  'element_type' => $this->getElementName()
                , 'id'           => $this->getId()
                , 'type'         => 'accordion'
                , 'title'        => $this->getTitle()
                , 'url'          => $this->getURL()
                , 'icon'         => $this->getIcon()
                , 'is_active'    => $this->isActive()
        ));
    }
}
