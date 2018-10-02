<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Container
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Element_container extends Element implements Container{
    
    /**
     * @var Elements_collection
     */
    protected $elements;
    
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        //log_message('DEBUG','Construct Element Container.');
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
        $this->elements = new Elements_collection($component->childs,$this);
    }

    /**
     * Função de inicialização da classe.
     *
     * @return static
     */
    public function &initialize(){
        $this->elements->initialize();
        return parent::initialize();
    }

    /**
     * Retorna todos os components do tipo especificado.
     * 
     * @param string $type
     * @param boolean $recursive = FALSE
     * @return array
     */
    public function getElementsOfType($type,$recursive = FALSE){
        return $this->getElements()->getElementsOfType($type,$recursive);
    }

    /**
     * Retorna todos os elementos filhos do container.
     * 
     * @return Elements_collection
     */
    public function &getElements(){
        return $this->elements;
    }
    
    /**
     * Retorna o elemento referenciado pela chave passada por parâmetro
     * 
     * @param mixed $key
     * @return Element|Container
     */
    public function &getElementByKey($key){
        return $this->getElements()->{$key};
    }

    public function whereInstanceOfRecursive($type){
        return $this->getElements()->whereInstanceOfRecursive($type);
    }
            
    public function __get($name){
        switch ($name) {
            case 'elements':
                return $this->getElements();
            default:
                return parent::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['elements'];
        
        return in_array($name,$attributes) || parent::__isset($name);
    }

    public function getIterator(): \Traversable {
        return new ArrayIterator($this->getElements());
    }

    public function jsonSerialize() {
        return array_merge(parent::jsonSerialize(),[
              'elements' => $this->getElements()
        ]);
    }

    /**
     * Retorna um string com o HTML de todos os elementos do container.
     *
     * @return string
     */
    public function getHTML(){
        return $this->getElements()->getHTML();
    }

}
