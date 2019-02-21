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
    
    protected function getData($field){
        return $this->data[$field] ?? NULL;
    }

    public function addRule($field,$label,Respect\Validation\Validator $rules,$mandatory=TRUE){
        $this->fields[$field] = ['field'=>$field,'label'=>$label,'rules'=>$rules,'mandatory'=>$mandatory,'is_array'=>FALSE];
        $this->validator->key($field,$rules,$mandatory);

        return $this;
    }
    
    public function addRulesArray(Array $rules_array) {
        foreach ($rules_array as $rule) {
            $this->addRule($rule['field'], $rule['label'], $rule['rules'],$rule['mandatory'] ?? TRUE);
        }
        
        return $this;
    }
    
    public function addRulesSet($field,$label,Respect\Validation\Validator $rules, Array $rules_array,$mandatory=TRUE) {
        $aux_respects = [];
        foreach (($this->data[$field] ?? []) as $k => $v) {
            $aux_respect = new Aux_respect_validation(['data'=>$v]);
            $aux_respect->addRulesArray($rules_array);
            $aux_respects[$k] = $aux_respect;
        }
        
        $this->fields[$field] = ['field'=>$field,'label'=>$label,'rules'=>$rules,'mandatory'=>$mandatory,'is_array'=>TRUE,'aux_respect_validation' => $aux_respects];

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

                $this->addError($k,$exception->getMainMessage());
            }

            if ($field['is_array']) {
                if (is_array($field['aux_respect_validation'])) {
                    $aux_errors = [];
                    
                    foreach ($field['aux_respect_validation'] as $k2 => $aux) {
                        if (!$aux->isValid()){
                            $aux_errors[$k2] = $aux->getMessages();
                        }
                    }
                    
                    $this->addError('_' . $k, $aux_errors);
                } else if (!$field['aux_respect_validation']->isValid()) {
                    $this->addError('_' . $k, $field['aux_respect_validation']->getMessages());
                }
            }
        }

        return count($this->error_messages) == 0;
    }

    public function matchFields($field1,$label1,$field2,$label2){
        if (($this->getData($field1) != NULL || $this->getData($field2) != NULL) && $this->getData($field1) !== $this->getData($field2)) {
            $this->addError($field2,"O campo $label2 deve ser igual ao campo $label1.");
        }

        return $this;
    }
    
    public function getValidator(){
        return $this->validator;
    }
    
    public function getMessages(){
        return $this->error_messages;
    }

    public function addError($label,$message) {
        $this->error_messages[$label] = $message;
    }
    
    public function hasError($label) {
        return key_exists($label, $this->getMessages());
    }
}

