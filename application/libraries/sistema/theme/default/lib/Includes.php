<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Element
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Includes extends Element {

    public static function getElementObject(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        switch(self::getComponentType($component)){
            case 'include':
                return new Includes($component, $parent, $key, $previousSiblingKey);
        }
    }

    /**
     * Função para alterar valor do atributo url.
     *
     * @param mixed $url
     * @return void
     */
    public function setURL($url){
        $this->setAttr('url',$url);
    }
    
    /**
     * Retorna o atributo style do elemento.
     *
     * @return string
     */
    public function getURL(){
        return $this->getAttr('url');
    }

    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load($this->getURL());
        
        return $template->render();
    }

}
