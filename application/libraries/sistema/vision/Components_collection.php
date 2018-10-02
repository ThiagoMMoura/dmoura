<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Components Collection
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Components_collection extends Collection{
    
    public function __construct($components = []) {
        if ($components instanceof static) {
            parent::__construct($components);
        } else if (is_array($components)) {
            parent::__construct();
            
            foreach($components as $item){
                $this->push($item);
            }
        }
    }
    
    public function offsetSet($key,$item){
        $component = Component::wrap($item);
        if ($component != NULL) {
            parent::offsetSet($key, $component);
        } else {
            log_message('ERROR', print_r($component,TRUE));
        }
    }
    
    public function __get($name){
        return $this->filter(function($item) use ($name){
            return $item->getAttr('id') == $name;
        });
    }
    
    public function __isset($name){
        return $this->__get($name)->isNotEmpty();
    }

    public function __toString(){
        return json_encode($this->all());
    }
}
