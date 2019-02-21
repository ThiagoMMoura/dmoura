<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArraySerializationConfig{
    
    const MODE_NORMAL = 1;
    const MODE_ASSOCIATIVE = 1;
    const MODE_NO_ASSOCIATIVE = 2;
    const MODE_ONLY_FIRST_VALUE = 3;
    
    private $mode;
    private $default_config;
    private $filter_items;
    private $elements_config;
    
    public function __construct($filter_items = NULL, ArraySerializationConfig $default_config = NULL, $mode = self::MODE_NORMAL) {
        $this->mode = $mode;
        $this->default_config = $default_config;
        $this->filter_items = $filter_items;
        $this->elements_config = [];
    }
    
    public function addElementConfig($key, $filter_items = NULL, ArraySerializationConfig $default_config = NULL, $mode = self::MODE_NORMAL) {
        return $this->elements_config[$key] = new self($mode, $filter_items, $default_config);
    }
    
    public function setElementConfig($key, ArraySerializationConfig $config) {
        return $this->elements_config[$key] = $config;
    }

    public function getElementConfig($key) : ArraySerializationConfig {
        return $this->elements_config[$key] ?? ($this->elements_config[$key] = $this->getDefaultConfig());
    }
    
    public function setElementDefaultConfig($key, ArraySerializationConfig $config) {
        $this->getElementConfig($key)->setDefaultConfig($config);
    }

    public function setElementMode($key,$mode) {
        $this->getElementConfig($key)->setMode($mode);

        return $this;
    }
    
    public function getElementMode($key) {
        return $this->getElementConfig($key)
                ? $this->getElementConfig($key)->getMode()
                : $this->getDefaultConfig()->getMode();
    }
    
    public function setElementFilter($key, $filter) {
        if ($this->getElementConfig($key)) {
            return $this->getElementConfig($key)->setFilter($filter);
        }

        return $this->setElementConfig($key,$this->getDefaultConfig())->setFilter($filter);
    }

    public function getElementFilter($key) {
        return $this->getElementConfig($key)
                ? $this->getElementConfig($key)->getFilter()
                : $this->getDefaultConfig()->getFilter();
    }

    public function setDefaultConfig(ArraySerializationConfig $default_config) {
        $this->default_config = $default_config;

        return $this;
    }

    public function getDefaultConfig(){
        return $this->default_config ?? ($this->default_config = new ArraySerializationConfig());
    }
    
    public function setFilter($filter) {
        return $this->filter_items = $filter;
    }

    public function getFilter() {
        return $this->filter_items;
    }

    public function hasFilter() {
        return (bool) $this->filter_items;
    }

    public function isFiltered($item) {
        return !$this->filter_items || in_array($item, $this->filter_items); 
    }

    public function setMode($mode) {
        $this->mode = $mode;
    }

    public function getMode() {
        return $this->mode;
    }
}