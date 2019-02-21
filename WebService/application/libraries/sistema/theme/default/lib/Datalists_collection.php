<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of List
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Datalists_collection extends Elements_collection{
    
    public function __construct(Elements_collection $components = NULL, Container &$parent = NULL) {
        parent::__construct($components
            ->whereInstanceOf(Datalist::class)
            ->mapWithKeys(function($element){
                log_message('debug', 'DATALIST_COLLECTION '.$element->getName());
                return [$element->getName()=>$element];
            }),
        $parent);
        
    }
    
    public function getItensLists(){
        $listsItens = [];
        
        foreach($this->all() as $list){
            $listsItens[$list->getName()] = $list->getItens();
        }
        
        return $listsItens;
    }
    
    /**
     * Obtém um array de URLs chaveado pelo nome das listas.
     * 
     * @return array
     */
    public function getURLLists(){
        $lists = [];
        
        foreach($this->all() as $list){
            if ($list->hasURL()){
                $lists[] = ['name'=>$list->getName(),'url'=>$list->getURL()];
            }
        }
        
        return $lists;
    }
    
    public function jsonSerialize(){
        return [
                  'data' => $this->getItensLists()
                , 'urls' => $this->getURLLists()
            ];
    }
}
