<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Field
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Field_date extends Field {
    
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
        $this->field_type = 'date';
        $this->setConvertType('date');
    }

}
