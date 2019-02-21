<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Form
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Form extends Element_container {
    
    /**
     * @var Fields_collection
     */
    protected $fields;
    protected $lists;
    
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL) {
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        $this->lists = new Datalists_collection($this->getElementsOfType('datalist'));
        $this->fields = new Fields_collection($this->getElementsOfType('field',TRUE));
    }
    
    public static function getFormObject(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        switch($component->name){
            case 'form_button':
                return new Form_button($component, $parent, $key, $previousSiblingKey);
            default:
                return new Form($component, $parent, $key, $previousSiblingKey);
        }
    }

    public function getLists(){
        return $this->lists;
    }
    
    /*public function getFormFields(){
        return $this->fields->getFormFields();
    }*/

    public function getDataSetFields(){
        return json_encode($this->fields->getDatasetFields());
    }
    
    public function getEditURL(){
        return $this->getAttr('edit');
    }

//    public function getFirstIndex(){
//        return json_encode([
//            'form'=>$this->fields->getFormFirstIndex()
//            , 'modal'=>$this->fields->getDatasetFirstIndex()
//        ]);
//    }

    public function getConvertType(){
        return json_encode([
            'fields'=>$this->fields->getFormConvertTypes()
            , 'datasets'=>$this->fields->getDatasetConvertTypes()
        ]);
    }
    
    public function getTopBarButtons(){
        $buttons = [];
        
        foreach($this->getElements()->whereInstanceOf(Form_button::class) as $button){
            $buttons[] = ['id' => 'generic-' . $button->getId(),'for' => $button->getId(),'title' => $button->getTitle(),'icon' => $button->getIcon()];
        }
        
        return $buttons;
    }
    
    public function __get($name) {
        switch($name){
            case 'name':
                return $this->getName();
            case 'lists':
                return $this->getLists();
            case 'fields':
                return $this->getFormFields();
            case 'datasets':
                return $this->getDataSetFields();
            case 'convert_type':
                return $this->getConvertType();
            case 'first_index':
                return $this->getFirstIndex();
            case 'attr':
                return $this->getAttr();
            case 'top_buttons':
                return json_encode($this->getTopBarButtons());
            default:
                return NULL;
        }
    }
    
    public function __isset($name){
        $attributes = ['name','lists','fields','datasets','convert_type','first_index','attr','top_buttons'];
        
        return in_array($name, $attributes);
    }

    public function __toString(){
        return json_encode($this->getIterator());
    }

    public function jsonSerialize(){
        return [
            'fields' => $this->getFormFields()
            , 'dataset' => $this->getDataSetFields()
            , 'convert_type' => $this->getConvertType()
            , 'first_index' => $this->getFirstIndex()
            , 'lists' => $this->getLists()
        ];
    }

    public function getIterator(): \Traversable{
        return new ArrayIterator($this->getFormFields());
    }
    
    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/form.twig');
        
        return $template->render(array(
                  'element_type' => $this->getElementName()
                , 'id'           => $this->getId()
                , 'name'         => $this->getName()
                , 'action'       => $this->getAttr('action')
                , 'edit_action'  => $this->getEditURL()
                , 'datalists'    => $this->getLists()
                , 'content'      => parent::getHTML()
        ));
    }
}
