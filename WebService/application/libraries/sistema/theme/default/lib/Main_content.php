<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Form
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Main_content extends Elements_collection {
    
    protected $form;
    //protected $elements;
    protected $fields;
    protected $lists;
    protected $top_buttons;
    
    public function __construct(&$entity) {
        parent::__construct($entity);

        $this->form = new Form($entity['form']);
        $this->form->initialize();
        //$this->lists = new Data_lists($entity['lists']);
        //$this->fields = new Fields_collection($this->form->getElementsOfType('field',TRUE));
    }
    
    public function getAttr($name = NULL){
        return $this->form->getAttr($name);
    }

    public function getName(){
        return $this->getAttr('name');
    }

    public function getLists(){
        return $this->form->getLists();
    }
    
    public function getFormFields(){
        return $this->form->getFormFields();
    }

    public function getDataSetFields(){
        return $this->form->getDataSetFields();
    }

    public function getFirstIndex(){
        return $this->form->getFirstIndex();
    }

    public function getConvertType(){
        return $this->form->getConvertType();
    }
    
    public function getTopBarButtons(){
        return $this->form->getTopBarButtons();
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
                return parent::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['lists','fields','datasets','convert_type','first_index','top_buttons'];
        
        return in_array($name, $attributes) || parent::__isset($name);
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

    public function getIterator(){
        return new ArrayIterator($this->getFormFields());
    }
    
    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        return $this->form->getHTML();
    }
}
