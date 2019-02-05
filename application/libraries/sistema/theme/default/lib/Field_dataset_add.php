<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Field DataSet Add
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Field_dataset_add extends Field implements Container{

    /**
     * @var /Frame 
     */
    protected $frame;
    
    /**
     * @var /Fields_collection
     */
    private $fields;
    
    private $dataset_first_index;
    private $dataset_convert_types;
    
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
        $frame_component_attrs = ['id'=>'modal-' . $this->getId(),'title'=>'Adicionar ' . $this->getAttr('title')];
        $frame_component = new Component('frame', $frame_component_attrs, $component->getChilds()->all(),$this->getId());
        
        $this->frame = new Frame($frame_component,$this);
        $this->fields = new Fields_collection($this->getElementsOfType('field',TRUE));
        $this->dataset_first_index = $this->fields->getFormFirstIndex();
        $this->dataset_convert_types = $this->fields->getFormConvertTypes();
        
        $this->setValue($this->fields->getFormFields());
        
        $this->setEqualizer(FALSE);
    }

    /**
     * Função de inicialização da classe.
     *
     * @return static
     */
    public function &initialize(){
        $button_back = new Component('button',['title'=>'Fechar Formulário','ng'=>['click' => "modalReset('" . $this->getName() . "')"]],[],$this->getId());
        $this->frame->setButtonBack($button_back);

        $form_name = 'field_data_local';
        $name = $this->getName();
        $error_name = $this->getErrorName();
        $this->frame->getElements()->transform(function($element) use ($form_name,$name,$error_name){
            return Field_dataset_add::setAllFormName($element,$form_name,$name,$error_name);
        });

        $this->frame->addTopBarButton(new Component('button',['title'=>'Confirmar','ng'=>['click'=>"modalAdd('".$this->getName()."');modalReset('".$this->getName()."')"],'data-attr'=>['modal-close'=>$this->frame->getId()]],[],$this->getId()));

        $this->frame->initialize();

        return parent::initialize();
    }

    public static function setAllFormName($element,$form_name,$name,$error_name){
        if ($element instanceof Field) {
            $element->setEqualizer(FALSE);
            $element->setErrorName("_{$error_name}['+dataset_index+'][" . ($element->getErrorName() ?? $element->getName()) . "]");
            $element->setFormName($form_name);
        } else if ($element instanceof Container && !($element instanceof Field_dataset_add)) {
            $element->getElements()->transform(function($element) use ($form_name,$name,$error_name){
                return Field_dataset_add::setAllFormName($element,$form_name,$name,$error_name);
            });
        }

        return $element;
    }

    public function getDatasetFirstIndex(){
        return $this->dataset_first_index;
    }
    
    public function hasDataSetFirstIndex(){
        return $this->dataset_first_index != NULL;
    }

    public function getDatasetConvertTypes(){
        return $this->dataset_convert_types;
    }
    
    public function hasDataSetConvertTypes(){
        return $this->dataset_convert_types != NULL;
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

    public function &getElements(){
        return $this->frame->getElements();
    }
    
    public function whereInstanceOfRecursive($type){
        return $this->getElements()->whereInstanceOfRecursive($type);
    }

    public function __get($name){
        switch ($name) {
            case 'datasetFirstIndex':
            case 'dataset-first-index':
                return $this->getDatasetFirstIndex();
            default:
                return parent::__get($name);
        }
    }

    public function __isset($name){
        $attributes = ['datasetFirstIndex','dataset-first-index'];
        
        return in_array($name,$attributes) || parent::__isset($name);
    }

    public function &getElementByKey($key) {
        return $this->getElements()->{$key};
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
                , 'class'        => $this->getClass()
                , 'form_name'    => $this->getFormName()
                , 'error_name'   => $this->getErrorName()
                , 'attributes'   => $this->getTagAttributes()
                , 'disabled'     => $this->hasDisabled()
                , 'labels'       => $this->getAttr('labels')
                , 'frame_id'     => $this->frame->getId()
                , 'frame'        => $this->frame->getHTML()
        ));
    }
}
