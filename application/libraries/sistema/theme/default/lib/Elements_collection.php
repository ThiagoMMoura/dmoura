<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Elements Collection
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Elements_collection extends Collection{
    
    protected $parent;
    public function __construct($components = NULL, Container &$parent = NULL) {
        //log_message('DEBUG','Construct Elements collection.');
        $siblingKey = NULL;
        $this->parent =& $parent;
        parent::__construct(Collection::make($components)
            ->filter(function($element){
                return $element instanceof Component || $element instanceof Element;
            })
            ->transform(function($element,$key) use (&$siblingKey){
                if($element instanceof Component){
                    //log_message('DEBUG','(Elements_collection) Previous Sibling: ' . ($siblingKey??'NULL'));
                    $previousKey = $siblingKey;
                    //log_message('DEBUG','(Elements_collection) Previous key: ' . ($previousKey??'NULL'));
                    $siblingKey = $key;
                    //log_message('DEBUG','(Elements_collection) Previous Sibling 2: ' . ($siblingKey??'NULL'));
                    //log_message('DEBUG','(Elements_collection) Previous key 2: ' . ($previousKey??'NULL'));
                    //log_message('DEBUG','(Elements_collection) Parent: ' . ($this->parent??'NULL'));
                    return Element::getElementObject($element,$this->parent,$key,$previousKey) ?? new Element($element, $this->parent, $key, $previousKey);
                }

                return $element;
            })
        );
        
    }
    
    /**
     * Função de inicialização da classe.
     *
     * @return static
     */
    public function &initialize(Container &$parent = NULL){
        if($parent !== NULL){
            $this->parent =& $parent;
        }

        $this->transform(function($element){
            return $element->initialize();
        });
        return $this;
    }

    /**
     * Retorna todos os components do tipo especificado.
     * 
     * @param string $type
     * @param boolean $recursive = FALSE
     * @return array
     */
    public function getElementsOfType($type,$recursive = FALSE){
        return $this->pipe(function($elements) use ($type,$recursive){
            $newElements = new static();

            foreach($elements as $element){
                if($element->getElementType() == $type){
                    $newElements->push($element);
                }

                if($recursive && $element instanceof Container && ! ($element instanceof Field)){
                    foreach($element->getElementsOfType($type,$recursive) as $subele){
                        $newElements->push($subele);
                    }
                }
            }
            
            return $newElements;
        });
    }
    
    public function whereInstanceOfRecursive($type){
        $elements = new static();
        
        foreach($this->all() as $element){
            if ($element instanceof $type) {
                $elements->push($element);
            }
            
            if ($element instanceof Container && !($element instanceof Field)) {
                foreach ($element->getElements()->whereInstanceOfRecursive($type) as $subelem) {
                    $elements->push($subelem);
                }
            }
        }
        
        return $elements;
    }

    public function offsetSet($key,$item){
        if ($item != NULL) {
            $previousKey = Arr::lastKey($this->all());
            
            if ($item instanceof Element) {
                parent::offsetSet($key, $item);
            } else if ($item instanceof Component) {
                if ($key == NULL) {
                    parent::offsetSet($key, $item);
                    $key = Arr::lastKey($this->all());
                }
                
                $element = Element::getElementObject($item,$this->parent,$key,$previousKey);
                
                parent::offsetSet($key, $element);
            }
        }
    }
    
    /**
     * Retorna uma string com o HTML de todos os elementos concatenados.
     *
     * @return string
     */
    public function getHTML(){
        $html = '';

        foreach($this->all() as $element){
            $html .= $element->getHTML();
        }

        return $html;
    }
    
    /**
     * Retorna um array com HTMLs de todos os elementos.
     * 
     * @return array
     */
    public function getArrayHTML(){
        $html = [];

        foreach($this->all() as $element){
            $html[] = $element->getHTML();
        }

        return $html;
    }
    
    public function __get($name){
        return $this->get($name);
    }
    
    public function __isset($name){
        return $this->has($name);
    }
}
