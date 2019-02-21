<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Element
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Element implements JsonSerializable, IteratorAggregate, Element_navigation {
    protected $CI;
    /**
     * @var Component
     */
    protected $component;
    
    /**
     * @var Container
     */
    protected $parent;
    
    /**
     * @var array
     */
    protected $siblings;
    
    /**
     * @var mixed
     */
    protected $key;
    
    /**
     * @var array
     */
    protected $tag_atributes;
    
    /**
     * 
     * @param Component $component
     * @param Element $parent
     * @param mixed $key
     * @param mixed $previousSiblingKey
     */
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        $this->CI =& get_instance();
        log_message('INFO', "(" . static::class . '->' . $component->name . ") Uso de memória: ".(memory_get_usage()/1024/1024).' Megabytes (' . memory_get_usage() . ') Total: ' . (memory_get_peak_usage(TRUE)/1024/1024));
        //log_message('DEBUG','Declare: ' . static::class);
        $this->component =& $component;
        //log_message('DEBUG','Parent for ' . static::class . ': ' . $parent);
        $this->parent =& $parent;
        $this->key = $key;
        $this->siblings = ['previous'=>$previousSiblingKey,'next'=>NULL];
        $this->tag_atributes = [];
        //log_message('DEBUG','Previous Sibling: ' . $previousSiblingKey);
    }

    public function &initialize(){
        log_message('DEBUG', 'Elemento a inicializar: ' . $this->getElementName());
        if($this->hasPreviousSibling()){
            $this->getPreviousSibling()->setNextSibling($this->key);
        }
        return $this;
    }

    public static function getElementObject(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        switch(self::getComponentType($component)){
            case 'field':
                return Field::getFieldObject($component, $parent, $key, $previousSiblingKey);
            case 'tab':
                return Tab::getTabObject($component, $parent, $key, $previousSiblingKey);
            case 'button':
                return Button::getButtonObject($component, $parent, $key, $previousSiblingKey);
            case 'frame':
                return Frame::getFrameObject($component, $parent, $key, $previousSiblingKey);
            case 'form':
                return Form::getFormObject($component, $parent, $key, $previousSiblingKey);
            case 'datalist':
                return new Datalist($component, $parent, $key, $previousSiblingKey);
            case 'include':
                return new Includes($component, $parent, $key, $previousSiblingKey);
            case 'main':
                if ($component->getName() === 'main_menu') {
                    return Mainmenu::getMainmenuObject($component, $parent, $key, $previousSiblingKey);
                }
            case 'mainmenu':
                return Mainmenu::getMainmenuObject($component, $parent, $key, $previousSiblingKey);
            case 'tl':
            case 'table':
                return Table_list::getTableListObject($component, $parent, $key, $previousSiblingKey);
            default:
                return new Element($component, $parent, $key, $previousSiblingKey);
        }
    }
    
    /**
     * A função retorna o component relacionado ao elemento.
     *
     * @return Component
     */
    public function getComponent(){
        return $this->component;
    }

    /**
     * A função retorna o nome do elemento. No XML o nome do elemento é o nome da Tag.
     *
     * @return string
     */
    public function getElementName(){
        return $this->component->name;
    }

    /**
     * A função retorna o valor do atributo name do elemento.
     * 
     * @return string
     */
    public function getName(){
        return $this->getAttr('name');
    }

    /**
     * A função retorna o valor do atributo id do elemento.
     * 
     * @return string
     */
    public function getId(){
        return $this->getAttr('id');
    }

    /**
     * A função retorna o valor de um atributo do elemento ou se o parâmetro da função
     * não for preenchido é retorna um array com todos os atributos deste elemento.
     *
     * @param string $name
     * @return mixed
     */
    public function getAttr($name = NULL){
        if($name != NULL){
            return $this->component->getAttr($name);
        }
        return $this->component->getAttrs();
    }

    /**
     * Função para alterar valor ou criar novo atributo.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setAttr($name,$value = NULL){
        $this->component->setAttr($name,$value);
    }
    
    /**
     * Retorna TRUE caso o elemento possua o atributo, FALSE caso contrário.
     *
     * @return bool
     */
    public function hasAttr($name){
        return $this->component->hasAttr($name);
    }

    /**
     * Retorna o atributo class do elemento.
     *
     * @return string
     */
    public function getClass(){
        return $this->getAttr('class');
    }

    /**
     * Função para adicionar uma classe ao atributo class do elemento.
     * 
     * @param string $class
     * @return $this
     */
    public function addClass($class){
        $this->setAttr('class', $this->getAttr('class') . ' ' . $class);
        
        return $this;
    }
    
    /**
     * Retorna o atributo style do elemento.
     *
     * @return string
     */
    public function getStyle(){
        return $this->getAttr('style');
    }

    /**
     * Função para adicionar um estilo css ao atributo style do elemento.
     * 
     * @param string $style
     * @return $this
     */
    public function addStyle($style){
        $this->setAttr('style', $this->getAttr('style') . $style);
        
        return $this;
    }
    
    /**
     * Retornar os atributos da tag em formato html.
     * 
     * @return string
     */
    public function getTagAttributes(){
        $attrs = '';
        
        foreach($this->tag_atributes as $attr => $value){
            $attrs .= ' ' . $attr . '="' . $value . '"';
        }
        log_message('DEBUG','Tag ' . $attrs);
        return $attrs;
    }
    
    /**
     * Adiciona atributos a tag deste elemento.
     * 
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setAttributeToTag($name,$value){
        $this->tag_atributes[$name] = $value;
    }
    
    /**
     * Está função retorna o tipo do Elemento.
     * 
     * @return string
     */
    public function getElementType(){
        return self::getComponentType($this->component);
    }

    /**
     * Está função retorna o tipo do Componente.
     * 
     * @param Component $component
     * @return string
     */
    public static function getComponentType(Component $component){
        return explode('_', $component->name)[0];
    }

    public function setPreviousSibling($sibling){
        $this->siblings['previous'] = $sibling;
        return $this;
    }

    public function setNextSibling($sibling){
        $this->siblings['next'] = $sibling;
        return $this;
    }

    public function &getPreviousSibling(){
        if ($this->hasPreviousSibling()) {
            return $this->parent->getElementByKey($this->siblings['previous']);
        }

        return NULL;
    }

    public function &getNextSibling(){
        if ($this->hasNextSibling()) {
            return $this->parent->getElementByKey($this->siblings['next']);
        }

        return NULL;
    }
    
    public function hasNextSibling() {
        return $this->siblings['next'] !== NULL;
    }

    public function hasPreviousSibling() {
        return $this->siblings['previous'] !== NULL;
    }

    public function getParent(){
        return $this->parent;
    }
    
    public function setParent(Element &$parent){
        $this->parent =& $parent;
    }

    public function toJson(){
        return json_encode($this);
    }

    public function __get($name){
        switch ($name) {
            case 'element_name':
                return $this->getElementName();
            case 'element_type':
                return $this->getElementType();
            default:
                return $this->getAttr($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['element_name','element_type'];
        
        return in_array($name,$attributes) || $this->hasAttr($name);
    }

    public function getIterator(): \Traversable {
        return new ArrayIterator();
    }

    public function jsonSerialize() {
        return [
              'name' => $this->getElementName()
            , 'type' => $this->getElementType()
            , 'attributes' => $this->getAttr()
        ];
    }

    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        return '';
    }

    public function __toString(){
        return $this->toJson();
    }

}
