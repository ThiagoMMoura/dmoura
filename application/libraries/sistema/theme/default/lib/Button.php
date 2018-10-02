<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Button
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Button extends Element implements Clickable_element{
    
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
    }

    /**
     * Função de inicialização da classe.
     *
     * @return $this
     */
    public function &initialize(){
        if ($this->hasAttr('hide')){
            $this->addStyle('display:none !important;');
        }

        if ($this->isDisabled()){
            //$this->setAttributeToTag("disabled",'');
        }

        if ($this->hasAttr('ng') && $this->getAttr('ng') != NULL) {
            foreach ($this->getAttr('ng') as $name => $value) {
                $this->setAttributeToTag("ng-{$name}",$value);
            }
        }
        
        if ($this->hasAttr('data-attr') && $this->getAttr('data-attr') != NULL) {
            foreach ($this->getAttr('data-attr') as $name => $value) {
                $this->setAttributeToTag("data-{$name}",$value);
            }
        }

        foreach ($this->getAttr() as $name => $value) {
            if (in_array($name, ['aria-label','data-close','data-open','onclick','id','style'])) {
                $this->setAttributeToTag($name,$value);
            }
        }

        return parent::initialize();
    }

    public static function getButtonObject(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        switch($component->name){
            case 'button_link':
                return new Button_link($component, $parent, $key, $previousSiblingKey);
            default:
                return new Button($component, $parent, $key, $previousSiblingKey);
        }
    }

    /**
     * Desativa ou ativa o component passando FALSE por parâmetro.
     *
     * @param bool $disable
     * @return void
     */
    public function disable(bool $disable = TRUE){
        $this->setAttr('disabled', $disable);
    }

    /**
     * Retorna TRUE caso o campo esteja desativado, caso contrário retorna FALSE.
     *
     * @return bool
     */
    public function isDisabled(){
        return (bool) $this->getAttr('disabled');
    }

    /**
     * Retorna o atributo title do elemento.
     *
     * @return string
     */
    public function getTitle(){
    	return $this->getAttr('title');
    }
    
    /**
     * Altera o valor do atributo title do campo.
     *
     * @param string $title
     * @return static
     */
    public function setTitle($title){
        $this->setAttr('title',$title);

        return $this;
    }
    
    /**
     * Retorna o atributo icon do elemento.
     *
     * @return string
     */
    public function getIcon(){
    	return $this->getAttr('icon');
    }
    
    /**
     * Altera o valor do atributo icon do campo.
     *
     * @param string $icon
     * @return static
     */
    protected function setIcon($icon){
        $this->setAttr('icon',$icon);

        return $this;
    }

    /**
     * Adiciona um evento angular clicável. 
     * 
     * @param string $event
     * @return void
     */
    public function addNGClickEvent($event){
        if (Arr::has($this->getAttr(),'ng.click')) {
            $event = substr($this->getNGClick(),-1) != ';' ? ";$event" : $event;
        }

        $this->setNGClick($this->getNGClick() . $event);
    }

    public function setNGClick($event){
        $ng = $this->getAttr('ng') ?? [];
        $this->setAttr('ng', Arr::set($ng, 'click', $event));
    }

    public function getNGClick(){
        return Arr::has($this->getAttr(),'ng.click')
                ? $this->getAttr('ng')['click']
                : NULL;
    }

    /**
     * Adiciona um evento clicável. 
     * 
     * @param string $event
     * @return void
     */
    public function addClickEvent($event){
        if ($this->hasAttr('onclick')) {
            $event = substr($this->getClickEvents(),-1) != ';' ? ";$event" : $event;
        }

        $this->setClickEvents($this->getClickEvents() . $event);
    }

    public function setClickEvents($event){
        $this->setAttr('onclick', $event);
    }

    public function getClickEvents(){
        return $this->getAttr('onclick');
    }

    /**
     * Adiciona um evento data clicável. 
     * 
     * @param string $event
     * @return void
     */
    public function addDataClickEvent($event){
        if (Arr::has($this->getAttr(),'data-attr.click')) {
            $event = substr($this->getDataClick(),-1) != ';' ? ";$event" : $event;
        }

        $this->setDataClick($this->getDataClick() . $event);
    }

    public function setDataClick($event){
        $data_attr = $this->getAttr('data-attr') ?? [];
        $this->setAttr('data-attr', Arr::set($data_attr, 'click', $event));
    }

    public function getDataClick(){
        return Arr::has($this->getAttr(),'data-attr.click')
                ? $this->getAttr('data-attr')['click']
                : NULL;
    }

    public function __get($name){
        switch ($name) {
            case 'icon':
                return $this->getIcon();
            default:
                return parent::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['icon'];

        return in_array($name, $attributes) || parent::__isset($name);
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
                  'element_type' => $this->getElementName()
                , 'title'        => $this->getTitle()
                , 'icon'         => $this->getIcon()
                , 'class'        => $this->getClass()
                , 'attributes'   => $this->getTagAttributes()
                , 'title_class'  => $this->getAttr('title_class')
                , 'for_button'   => $this->getAttr('for')
        ));
    }

}
