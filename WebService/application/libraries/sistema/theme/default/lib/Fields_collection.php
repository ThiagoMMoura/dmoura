<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Fields Collection
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Fields_collection extends Elements_collection{

    protected $count;
    
    public function __construct(Elements_collection $components = NULL, Container &$parent = NULL) {
        log_message('DEBUG','Construct Fields collection. ' . $components->count());
        parent::__construct($components
            ->whereInstanceOf(Field::class)
            ->mapWithKeys(function($field){
                //log_message('DEBUG','Field: ' . $field->getName() . " => " . $field);
                return [$field->getName()=>$field];
            })
        ,$parent);
        //log_message('DEBUG','Fields collection count: ' . $this->count());
    }

    public function getFormFields(){
        return Collection::make($this)->map(function($field,$key){
            return $field->getValue() instanceof Collection
                    ? []
                    : $field->getValue();
        });
    }
    
    public function getFormFirstIndex(){
        return Collection::make($this)->filter(function($field,$key){
                    log_message('DEBUG', get_class($field) . ' ' . $field->getName());
                return ($field instanceof Field_select_list) && $field->hasFirstIndex();
            })->map(function($field,$key){
                return $field->getFirstIndex();
            })->all();
    }

    public function getFormConvertTypes(){
        $list = [];
        foreach ($this->all() as $key => $field) {
            if($field->hasConvertType()){
                $list[$key] = $field->getConvertType();
            }
        }
        return $list;
    }
    
    public function getDatasetFields(){
        $form_fields = [];
        foreach ($this->all() as $key => $field) {
            if($field instanceof Field_dataset_add){
                $form_fields[$key] = $field->getValue();
            }
        }
        return $form_fields;
    }
    
    public function getDatasetFirstIndex(){
        $list = [];
        foreach ($this->all() as $key => $field) {
            if($field instanceof Field_dataset_add && $field->hasDatasetFirstIndex()){
                $list[$key] = $field->getDatasetFirstIndex();
            }
        }
        return $list;
    }

    public function getDatasetConvertTypes(){
        $list = [];
        foreach ($this->all() as $key => $field) {
            if($field instanceof Field_dataset_add && $field->hasDatasetConvertTypes()){
                $list[$key] = $field->getDatasetConvertTypes();
            }
        }
        return $list;
    }
    
    public function __get($name){
        return $this->get($name);
    }
    
    public function __isset($name){
        return $this->has($name);
    }

    public function __toString(){
        return json_encode($this->getFormFields());
    }
}
