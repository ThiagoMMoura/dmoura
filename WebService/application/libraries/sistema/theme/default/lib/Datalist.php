<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Datalist
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Datalist extends Element{
    
    /*public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL) {
        parent::__construct($component, $parent, $key, $previousSiblingKey);
    }
    
    public function &initialize(){
        return parent::initialize();
    }
    
    public function getSetLists(){
        return $this->set_lists;
    }*/
    
    /**
     * Obtem todos os itens da lista
     * 
     * @return object
     */
    public function getItens(){ 
        $itens = [];
        log_message('debug','getItens');
        foreach($this->getComponent()->getChilds() as $child){
            $itens[$child->attr['value-id']] = $child->getTextContent();
        }
        
        return (object) $itens;
    }
    
    /**
     * Obtém a URL da lista.
     * 
     * @return string
     */
    public function getURL(){
        return base_url($this->getAttr('url'));
    }
    
    /**
     * Retorna TRUE se a lista possui URL.
     * 
     * @return bool
     */
    public function hasURL(){
        return $this->hasAttr('url');
    }

    public function __toString(){
        return json_encode($this);
    }

    public function jsonSerialize(){
        return $this->getItens();
    }
}
