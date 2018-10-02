<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Field
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Field_select_list extends Field {
    
    private $first_index;
    
    protected $frame;
    
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
        $this->first_index = NULL;
        
        if($this->value == ':first'){
            //$this->first_index = $this->getAttr('list') ?? $this->getName();
        }
        
        $frame_component_attrs = ['id'=>'modal-' . $this->getId(),'title'=>'Selecionar ' . $this->getAttr('title')];
        $frame_component = new Component('frame', $frame_component_attrs, [],$this->getId());
        
        $this->frame = new Frame($frame_component);
    }
    
    /**
     * Função de inicialização da classe.
     *
     * @return static
     */
    public function &initialize(){
        $button_back = new Component('button',['title'=>'Fechar Seleção'],[],$this->getId());
        $this->frame->setButtonBack($button_back);

        $this->frame->initialize();

        return parent::initialize();
    }

    public function getFirstIndex(){
        return $this->first_index;
    }
    
    public function hasFirstIndex(){
        return $this->first_index != NULL;
    }
    
    public function getList(){
        return $this->getAttr('list') ?? $this->getName();
    }
    
    public function __get($name){
        switch ($name) {
            case 'first_index':
                return $this->getFirstIndex();
            default:
                return parent::__get($name);
        }
    }

    public function __isset($name){
        $attributes = ['first_index'];

        return in_array($name, $attributes) || parent::__isset($name);
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
                , 'id'           => $this->getId()
                , 'name'         => $this->getName()
                , 'title'        => $this->getAttr('title')
                , 'description'  => $this->getAttr('description')
                , 'default_value'=> $this->getValue()
                , 'class'        => $this->getClass()
                , 'form_name'    => $this->getFormName()
                , 'error_name'   => $this->getErrorName()
                , 'attributes'   => $this->getTagAttributes()
                , 'disabled'     => $this->hasDisabled()
                , 'list'         => $this->getList()
                , 'frame_id'     => $this->frame->getId()
                , 'frame'        => $this->frame->getHTML()
        ));
    }
}
