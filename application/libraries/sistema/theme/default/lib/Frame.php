<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Frame
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Frame extends Element implements Container{
    
    /**
     * @var Elements_collection
     */
    protected $elements;

    /**
     * @var Button
     */
    protected $button_back;
    
    /**
     * @var Elements_collection
     */
    protected $top_bar_button;

    /**
     * @var string
     */
    protected $content;
    /**
     * Construtor da classe Frame.
     * 
     * @param Component $component
     * @param Container $parent
     * @param mixed $key
     * @param mixed $previousSiblingKey
     */
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
        $this->elements = new Elements_collection($component->childs,$this);
        $this->button_back = NULL;
        $this->top_bar_button = new Elements_collection([], $this);
        $this->content = NULL;
    }

    /**
     * Função de inicialização da classe.
     *
     * @return $this
     */
    public function &initialize(){
        $this->elements->initialize();
        $this->button_back && $this->button_back->initialize();
        $this->top_bar_button->initialize();

        return parent::initialize();
    }

    public static function getFrameObject(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        return new Frame($component, $parent, $key, $previousSiblingKey);
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
     * Retorna o atributo button_back do elemento.
     *
     * @return Component
     */
    public function getButtonBack(){
    	return $this->button_back;
    }
    
    /**
     * Altera o valor do atributo button_back do campo.
     *
     * @param Component|Element $button_back
     * @return $this
     */
    public function setButtonBack($button_back){
        if ($button_back instanceof Component || $button_back instanceof Element) {
            $this->button_back = $button_back;

            if ($button_back instanceof Component) {
                $this->button_back = Element::getElementObject($button_back,$this);
            }

            $title = $this->button_back->getAttr('title');
            $this->button_back->setAttr('aria-label',$title);
            $this->button_back->setAttr('title', '');
            $this->button_back->setAttr('icon','arrow-left');
            $this->button_back->setAttr('data-attr',['modal-close'=>$this->getId()]);
        }
        
        return $this;
    }
    
    /**
     * Retorna a lista de botões da barra de botões to topo.
     *
     * @return Button
     */
    public function getTopBarButton(){
    	return $this->top_bar_button;
    }
    
    /**
     * Adiciona um botão a barra de botoões do topo.
     *
     * @param Component $top_bar_button
     * @return $this
     */
    public function addTopBarButton(Component $top_bar_button){
        if(Element::getComponentType($top_bar_button) == 'button'){
            $this->top_bar_button->push($top_bar_button);
        }

        return $this;
    }
    
    public function setContent($content){
        $this->content = $content;
    }

    public function __get($name){
        switch ($name) {
            case 'button_back':
                return $this->getButtonBack();
            case 'top_bar_buttons':
                return $this->getTopBarButton();
            default:
                return parent::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['button_back','top_bar_buttons'];

        return in_array($name, $attributes) || parent::__isset($name);
    }

    public function &getElementByKey($key) {
        return $this->getElements()->{$key};
    }

    public function &getElements() {
        return $this->elements;
    }

    public function getElementsOfType($type, $recursive = FALSE): array {
        return $this->getElements()->getElementsOfType($type,$recursive);
    }

    public function whereInstanceOfRecursive($type){
        return $this->getElements()->whereInstanceOfRecursive($type);
    }
    
    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/frame.twig');
        
        return $template->render(array(
                  'element_type'    => $this->getElementName()
                , 'id'              => $this->getId()
                , 'title'           => $this->getAttr('title')
                , 'content'         => $this->content ?? $this->elements->getHTML()
                , 'button_back'     => ($this->button_back ? $this->button_back->getHTML() : '')
                , 'top_bar_buttons' => $this->top_bar_button->getArrayHTML()
        ));
    }
}
