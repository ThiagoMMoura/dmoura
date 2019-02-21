<?php
namespace Entity;

use Doctrine\Common\Collections\Collection;

class MetaInfo implements \JsonSerializable, \IteratorAggregate, \Arrayable{
    
    public function getIterator(): \Traversable {
        return new ArrayIterator($this->toArray());
    }

    public function jsonSerialize() {      
        return $this->toArray();
    }
    
    public function toArray(){
        $params = [];
        
        foreach (get_object_vars($this) as $param => $value){
            if (is_array($value) || $value instanceof Collection) {
                $subparams = [];
                
                foreach ($value as $subvalue) {
                    if ($subvalue instanceof Arrayable) {
                        $subparams[] = $subvalue->toArray();
                    } else {
                        $subparams[] = $subvalue;
                    }
                }
                
                $params[$param] = $subparams;
            } else if ($value instanceof Arrayable) {
                $params[$param] = $value->toArray();
            } else {
                $params[$param] = $value;
            }
        }
        
        return (array) $params;
    }

}