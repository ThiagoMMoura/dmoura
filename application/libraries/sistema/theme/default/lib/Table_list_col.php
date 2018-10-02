<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//define('MAX_RESPONSIVE_SIZE',12);

/**
 * Description of Table_list_col
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Table_list_col extends Element{
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * Construtor da classe Table_list_col.
     * 
     * @param Component $component
     * @param Container $parent
     * @param mixed $key
     * @param mixed $previousSiblingKey
     */
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
        $this->type = $this->getAttr('data-type') ?? 'text';
        //$this->sizeCount = ['small'=>MAX_RESPONSIVE_SIZE,'medium'=>MAX_RESPONSIVE_SIZE,'large'=>MAX_RESPONSIVE_SIZE];
        //$this->setEqualizer(TRUE);
    }

    /**
     * Função de inicialização da classe.
     *
     * @return $this
     */
    public function &initialize(){      
        //$this->applyFieldSettings();

        return parent::initialize();
    }

    /**
     * Aplica as configurações do campo.
     * 
     * @return void
     */
    public function applyFieldSettings(){
        //log_message('DEBUG', 'FIELD hasPreviousSibling: ' . ($this->hasPreviousSibling()?1:0));
        if($this->hasPreviousSibling() && $this->getPreviousSibling() instanceof Field){
            $this->sizeCount = $this->getPreviousSibling()->sizeCount;
        }
        
        $this->applySizeClass()
                ->applyLeftBorderClass()
                ->applyDisabledClass();

        //$this->sizeCount = array_merge($this->sizeCount, $this->getSizes());
    }

    /**
     * Aplica classe do tamanho das colunas responsivas do elemento.
     * 
     * @return static
     */
    protected function applySizeClass(){
        foreach($this->getSizes() as $name => $size){
            $this->addClass($name . '-' . $size);
        }

        $this->addClass('column');

        return $this;
    }

    /**
     * Aplica classe da borda esquerda no campo se necessário e retorna o objeto Field.
     * 
     * @return static
     */
    protected function applyLeftBorderClass(){
        foreach($this->sizeCount as $size_name => $old_size){
            $field_size = $this->getSize($size_name);
            //log_message('DEBUG','SIZE ' . $this->getName() . " $size_name: $field_size | OLD: $old_size");
            if(($field_size + $old_size) <= MAX_RESPONSIVE_SIZE){
                $this->addClass('border-left-' . $size_name);
                $field_size += $old_size;
            }

            $this->sizeCount[$size_name] = $field_size;
        }
        //log_message('DEBUG','COUNT ' . $this->getName() . ': ' . json_encode($this->sizeCount) . ' | CLASS: ' . $this->getAttr('class'));
        return $this;
    }
    
    /**
     * Aplica classe disabled se necessário.
     *
     * @return static
     */
    protected function applyDisabledClass(){
        if ($this->hasDisabled()){
            $this->addClass('disabled');
        }

        return $this;
    }

    /**
     * Retorna TRUE caso o campo esteja desativado, caso contrário retorna FALSE.
     *
     * @return bool
     */
    public function hasDisabled(){
        return (bool) $this->getAttr('disabled');
    }

    /**
     * Retorna o atributo type do elemento.
     *
     * @return mixed
     */
    public function getDataType(){
    	return $this->type;
    }
    
    /**
     * Altera o valor do atributo type do campo.
     *
     * @param mixed $type
     * @return static
     */
    public function setDataType($type) {
        $this->type = $type;
        return $this;
    }

    public function getTitle() {
        return $this->getAttr('title');
    }

    public function isOrdered() {
        return !($this->hasAttr('not-ordered') && $this->getAttr('not-ordered'));
    }
    
    public function getOrder() {
        return $this->isOrdered() ? ($this->getAttr('order') ?? 'ASC') : NULL;
    }

    /**
     * Retorna o atributo size do elemento.
     *
     * @return mixed
     */
    public function getSize($size_name){
        return $this->getSizes()[$size_name] ?? MAX_RESPONSIVE_SIZE;
    }

    /**
     * Retorna um array com os size do elemento.
     *
     * @return array
     */
    public function getSizes(){
        return (array) $this->getAttr('size');
    }

    public function getHeadClass(){
        return '';
    }

    public function getHeadTagAttributes(){
        return '';
    }

    public function __get($name){
        switch ($name) {
            case 'order':
                return $this->getOrder();
            case 'title':
                return $this->getTitle();
            case 'data_type':
                return $this->getDataType();
            default:
                return parent::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['order','title','data_type'];

        return in_array($name, $attributes) || parent::__isset($name);
    }

    public function getHeadHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/table_list.twig');
        
        return $template->render(array(
                  'element_type' => 'table_head_col'
                , 'id'           => $this->getId()
                , 'title'        => $this->getTitle()
                , 'order'        => $this->getOrder()
                , 'class'        => $this->getHeadClass()
                , 'attributes'   => $this->getHeadTagAttributes()
        ));
    }

    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/table_list.twig');
        
        return $template->render(array(
                  'element_type' => 'table_body_col'
                , 'type'         => $this->getDataType()
                , 'id'           => $this->getId()
                , 'name'         => $this->getName()
                , 'url'          => $this->getAttr('url')
                , 'class'        => $this->getClass()
                , 'attributes'   => $this->getTagAttributes()
        ));
    }
}
