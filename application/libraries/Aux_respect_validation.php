<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\ValidationException;

class Aux_respect_validation{
    
    protected $CI;
    protected $validator;
    protected $data;
    protected $fields;
    protected $error_messages;
    
    public function __construct($config = array()) {
        $this->CI =& get_instance();
        $this->validator = v::create();
        $this->data = 
                isset($config['data'])
                ? $config['data']
                : [];
        $this->fields = [];
        $this->error_messages = [];
        $this->CI->load->library('respect_translation');
    }
    
    public function addRule($field,$label,Respect\Validation\Validator $rules,$mandatory=TRUE){
        $this->fields[$field] = ['field'=>$field,'label'=>$label,'rules'=>$rules,'mandatory'=>$mandatory];
        $this->validator->key($field,$rules,$mandatory);
        return $this;
    }
    
    public function isValid(){
        foreach($this->fields as $k => $field){
            try{
                if($field['label'] != NULL){
                    $field['rules']->setName($field['label']);
                }
                if(!$field['mandatory']){
                    v::optional($field['rules'])->check($this->data[$k] ?? NULL);
                }else{
                    //if($this->data[$k] != NULL){
                        $field['rules']->check($this->data[$k] ?? NULL);
                    //}else{
                        //$this->error_messages[$k] = ($field['label']?$field['label']:$k) . " deve ser preenchido.";
                    //}
                }
            }catch(ValidationException $exception) {
                $exception->setParam('translator',[$this->CI->respect_translation,'translate']);
                $this->error_messages[$k] = $exception->getMainMessage();
            }
        }
        return count($this->error_messages) == 0;
    }
    
    public function getValidator(){
        return $this->validator;
    }
    
    public function getMessages(){
        return $this->error_messages;
    }
}

