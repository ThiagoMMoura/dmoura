<?php
defined('BASEPATH') OR exit('No direct script access allowed');

define('MAX_RESPONSIVE_SIZE',12);

/**
 * Description of Field
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Field extends Element{
    
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var string
     */
    protected $field_type;
    
    /**
     * @var string
     */
    protected $convert_type;

    /**
     * @var string
     */
    protected $form_name;

    /**
     * @var array
     */
    protected $sizeCount;
    
    /**
     * @var string
     */
    protected $error_name;
    
    /**
     * Construtor da classe Field.
     * 
     * @param Component $component
     * @param Container $parent
     * @param mixed $key
     * @param mixed $previousSiblingKey
     */
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
    	$this->value = $this->getAttr('value') ?? '';
        $this->convert_type = NULL;
        $this->field_type = 'text';
        $this->form_name = $this->getAttr('form_name') ?? $this->getAttr('form-name');
        $this->sizeCount = ['small'=>MAX_RESPONSIVE_SIZE,'medium'=>MAX_RESPONSIVE_SIZE,'large'=>MAX_RESPONSIVE_SIZE];
        $this->setEqualizer(TRUE);
        $this->error_name = $this->getAttr('error_name') ?? ($this->getAttr('error-name') ?? $this->getName());
    }

    /**
     * Função de inicialização da classe.
     *
     * @return $this
     */
    public function &initialize(){
        if($this->hasEqualizer()){
            $this->setAttributeToTag('data-equalizer-watch', '');
        }
        
        $this->applyFieldSettings();

        return parent::initialize();
    }

    public static function getFieldObject(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        switch($component->name){
            case 'field_checkbox':
                return new Field_checkbox($component, $parent, $key, $previousSiblingKey);
            case 'field_date':
                return new Field_date($component, $parent, $key, $previousSiblingKey);
            case 'field_select_list':
                return new Field_select_list($component, $parent, $key, $previousSiblingKey);
            case 'field_mult_select_list':
                return new Field_mult_select_list($component, $parent, $key, $previousSiblingKey);
            case 'field_textarea':
                return new Field_textarea($component, $parent, $key, $previousSiblingKey);
            case 'field_dataset_add':
                return new Field_dataset_add($component, $parent, $key, $previousSiblingKey);
            default:
                return new Field($component, $parent, $key, $previousSiblingKey);
        }
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
     * Retorna o atributo value do elemento.
     *
     * @return mixed
     */
    public function getValue(){
    	return $this->value;
    }
    
    /**
     * Altera o valor do atributo value do campo.
     *
     * @param mixed $value
     * @return static
     */
    protected function setValue($value){
        $this->value = $value;
        return $this;
    }

    /**
     * Retorna o tipo de entrada do elemento.
     *
     * @return string
     */
    public function getFieldType(){
    	return $this->field_type;
    }
    
    /**
     * Retorna o nome do form do campo.
     *
     * @return string
     */
    public function getFormName(){
        return $this->form_name ?? 'form';
    }

    /**
     * Altera o form_name do campo.
     *
     * @return static;
     */
    public function setFormName($form_name){
        $this->form_name = $form_name;
        return $this;
    }

    /**
     * Retorna o error_name do campo.
     *
     * @return string
     */
    public function getErrorName(){
        return $this->error_name;
    }

    /**
     * Altera o error_name do campo.
     *
     * @return static;
     */
    public function setErrorName($error_name){
        $this->error_name = $error_name;
        return $this;
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

    /**
     * Retorna TRUE caso equalizer esteja ativo, FALSE caso contrário.
     *
     * @return boll
     */
    public function hasEqualizer(){
    	return (bool) $this->getAttr('equalizer');
    }
    
    /**
     * Ativa ou desativa o equalizer.
     *
     * @param bool $value
     * @return static
     */
    protected function setEqualizer($value){
        $this->setAttr('equalizer',$value);
        
        return $this;
    }
    
    public function getConvertType(){
        return $this->convert_type;
    }
    
    public function hasConvertType(){
        return $this->convert_type != NULL;
    }
    
    protected function setConvertType($convert_type){
        $this->convert_type = $convert_type;
    }

    public function __get($name){
        switch ($name) {
            case 'value':
                return $this->getValue();
            case 'convert_type':
            case 'convert_field':
                return $this->getConvertType();
            default:
                return parent::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['value','convert_type','convert_field'];

        return in_array($name, $attributes) || parent::__isset($name);
    }

    public function getIterator(): \Traversable {
        return new ArrayIterator();
    }

    public function jsonSerialize() {
        return $this->getValue();
    }

    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/field.twig');
        
        return $template->render(array(
                  'element_type' => $this->getElementName()
                , 'type'         => $this->getFieldType()
                , 'id'           => $this->getId()
                , 'name'         => $this->getName()
                , 'title'        => $this->getAttr('title')
                , 'class'        => $this->getClass()
                , 'form_name'    => $this->getFormName()
                , 'error_name'   => $this->getErrorName()
                , 'attributes'   => $this->getTagAttributes()
                , 'disabled'     => $this->hasDisabled()
        ));
    }
}
