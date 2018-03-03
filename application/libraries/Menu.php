<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Gerador de Menu
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Menu {
    
    private $CI;
    private $root;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->root = [
            'sv_menu' => array()
        ];
    }
    
    private function loadXML($nome){
        $document = new DOMDocument( '1.0', 'UTF-8' );
        $document->preserveWhiteSpace = false;
        $document->load( $nome );
        return $document;
    }
    
    public function parser($file){
        $xmlDOM = $this->loadXML(VIEWPATH . $file . ".xml");
        
        $menus = $xmlDOM->getElementsByTagName('menu')->item(0);
        $this->root['sv_menu'] = $this->menu($menus);
        
        return $this->root;
    }
    
    public function menu(DOMElement $dom){
        return new TMenu($dom);
    }
}

class MenuTag{
    const MAX_UMA = 0;
    const UMA = 1;
    const MUITAS = 2;
    const UMA_MUITAS = 3;
    
    private $CI;
    public $atributos = [];
    public $tags = [];
    public $tagName;
    
    public function __construct($tagName) {
        $this->CI =& get_instance();
        $this->tagName = $tagName;
    }
    
    protected function addAttr(DOMElement $dom,$name,$type="texto",$fixed=FALSE){
        $value = $dom->getAttribute($name);
        switch($type){
            case 'boleano':
                $value = $value == 'TRUE';
                break;
            case 'url':
                $value = base_url($value);
                break;
            case 'json':
                $value = json_decode($value);
        }
        if($value!=NULL){
            $this->setAttr($name, $value);
        }elseif($fixed){
            trigger_error("O atributo '".$name."' não foi definido para a tag ".$this->tagName.".", E_USER_ERROR);
            die();
        }
    }
    
    protected function addTag(DOMElement $dom,$name,$fixed=self::MUITAS){
        $elements = [];
        foreach($dom->childNodes as $child){
            if($child->nodeType === XML_ELEMENT_NODE && $child->tagName === $name){
                $elements[] = $child;
            }
        }
        switch ($fixed){
            case self::UMA:
                if(count($elements)===0){
                    trigger_error("A Tag '".$name."' não foi definida dentro da tag ".$this->tagName.".", E_USER_ERROR);
                    die();
                }
            case self::MAX_UMA:
                if(count($elements)>0){
                    if($this->CI->controle_acesso->area($elements[0]->getAttribute('access-area'))){
                        $this->tags[$name] = $this->newTag($elements[0], $name);
                    }
                }
                break;
            case self::UMA_MUITAS:
                if(count($elements)===0){
                    trigger_error("A Tag '".$name."' não foi definida dentro da tag ".$this->tagName.".", E_USER_ERROR);
                    die();
                }
            case self::MUITAS:
                if(count($elements)>0){
                    $this->tags[$name] = [];
                    foreach($elements as $v){
                        if($this->CI->controle_acesso->area($v->getAttribute('access-area'))){
                            $this->tags[$name][] = $this->newTag($v, $name);
                        }
                    }
                }
                break;
            
        }
        return $elements;
    }
    
    private function newTag(DOMElement $dom,$name){
        switch($name){
            case 'item':
                return new Item($dom,$this->attr('id'));
        }
    }
    
    protected function setAttr($name,$value){
        return $this->atributos[$name] = $value;
    }
    
    public function attr($name){
        if(key_exists($name, $this->atributos)){
            return $this->atributos[$name];
        }
        return NULL;
    }
    
    public function tag($name){
        if(key_exists($name, $this->tags)){
            return $this->tags[$name];
        }
        return NULL;
    }
}

class TMenu extends MenuTag{
    public function __construct(DOMElement $dom) {
        parent::__construct('Menu');
        $this->addAttr($dom, 'id', 'texto',TRUE);
        $this->addAttr($dom, 'icon', 'texto');
        $this->addAttr($dom, 'title', 'texto');
        $this->addTag($dom,'item',self::UMA_MUITAS);
    }
}

class Item extends MenuTag{
    public function __construct(DOMElement $dom) {
        parent::__construct('Item');
        $this->addAttr($dom, 'id', 'texto',TRUE);
        $this->addAttr($dom, 'type', 'texto',TRUE);
        $this->addAttr($dom, 'title', 'texto',TRUE);
        $this->addAttr($dom, 'url', 'url');
        $this->addAttr($dom, 'icon', 'texto');
        $this->addTag($dom,'item',self::MUITAS);
    }
}