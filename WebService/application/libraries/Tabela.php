<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Gerador de Tabela
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Tabela {
    
    private $CI;
    private $root;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->root = [
            'sv_table' => array(),
            'sv_script' => array(),
            'sv_link' => array()
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
        
        $table = $xmlDOM->getElementsByTagName('table')->item(0);
        $this->root['sv_table'] = $this->table($table);
        
        $scripts = $xmlDOM->getElementsByTagName('script');
        foreach($scripts as $script){
            $this->root['sv_script'][] = $this->script($script);
        }
        $links = $xmlDOM->getElementsByTagName('link');
        foreach($links as $link){
            $this->root['sv_link'][] = $this->link($link);
        }
        
        return $this->root;
    }
    
    private function table(DOMElement $dom){
        return new Table($dom);
    }
    
    private function script(DOMElement $dom){
        return [
            'type' => $dom->getAttribute('type'),
            'src' => $dom->getAttribute('src')
        ];
    }
    
    private function link(DOMElement $dom){
        return [
            'type' => $dom->getAttribute('type'),
            'rel' => $dom->getAttribute('rel'),
            'href' => $dom->getAttribute('href'),
            'charset' => $dom->getAttribute('charset')
        ];
    }
}

class Tag{
    const MAX_UMA = 0;
    const UMA = 1;
    const MUITAS = 2;
    const UMA_MUITAS = 3;
    
    public $atributos = [];
    public $tags = [];
    public $tagName;
    
    public function __construct($tagName) {
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
                    $this->tags[$name] = $this->newTag($elements[0], $name);
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
                    foreach($elements as $k => $v){
                        $this->tags[$name][$k] = $this->newTag($v, $name);
                    }
                }
                break;
            
        }
        return $elements;
    }
    
    private function newTag(DOMElement $dom,$name){
        switch($name){
            case 'head':
                return new Head($dom,$this->attr('id'));
            case 'body':
                return new Body($dom,$this->attr('id'));
            case 'hcol':
                return new HCol($dom,$this->attr('id'));
            case 'bcol':
                return new BCol($dom,$this->attr('id'));
            case 'button':
                return new Button($dom,$this->attr('id'));
            case 'buttongroup':
                return new ButtonGroup($dom,$this->attr('id'));
            case 'url':
                return base_url($dom->nodeValue);
            case 'size':
            case 'select':
            case 'title':
            case 'subtitle':
            case 'description':
            case 'content':
                return $dom->nodeValue;
            case 'searchtext':
                return new SearchText($dom,$this->attr('id'));
            case 'searchfield':
                return new SearchField($dom,$this->attr('id'));
            case 'search':
                return new Search($dom,$this->attr('id'));
            case 'option':
                return new Option($dom);
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

class Table extends Tag{
    public $id;
    public $identifier;
    public function __construct(DOMElement $dom) {
        parent::__construct('Table');
        $this->addAttr($dom,'id',"texto",TRUE);
        $this->addAttr($dom,'name',"texto",TRUE);
        $this->addAttr($dom,'model',"texto",TRUE);
        $this->addAttr($dom,'url',"url",TRUE);
        $this->addAttr($dom,'join',"json");
        $this->addAttr($dom,'selector',"texto",TRUE);
        $this->addAttr($dom,'order',"boleano");
        $this->addAttr($dom,'sortcol',"texto");
        $this->id = $this->attr('id');
        $this->addTag($dom,'head',self::UMA);
        $this->addTag($dom,'body',self::UMA);
        $this->addTag($dom,'title',self::MAX_UMA);
        $this->addTag($dom,'buttongroup',self::MAX_UMA);
        $this->addTag($dom,'search',self::MAX_UMA);
        $this->identifier = $this->tag('head')->identifier;
    }
    
}

class Head extends Tag{
    public $identifier;
    public function __construct(DOMElement $dom,$id) {
        parent::__construct('Head');
        $this->setAttr('id', $id.'-head');
        $this->addTag($dom,'hcol',self::UMA_MUITAS);
        foreach($this->tag('hcol') as $h){
            if($h->isIdentifier){
                $this->identifier = $h;
                break;
            }
        }
    }
}

class Body extends Tag{
    
    public function __construct(DOMElement $dom,$id) {
        parent::__construct('Body');
        $this->setAttr('id', $id.'-body');
        $this->addTag($dom,'bcol',self::UMA_MUITAS);
    }
}

class HCol extends Tag{
    public $isIdentifier;
    public function __construct(DOMElement $dom,$id) {
        parent::__construct('HCol');
        $this->addAttr($dom,'id',"texto",TRUE);
        $this->addAttr($dom,'name',"texto",TRUE);
        $this->addAttr($dom,'type',"texto",TRUE);
        $this->addAttr($dom,'orderby',"texto");
        $this->addAttr($dom,'merge',"boleano");
        $this->addTag($dom,'title',self::UMA);
        $this->addTag($dom,'size',self::MAX_UMA);
        $this->addTag($dom,'select',self::UMA);
        $this->setAttr('id', $id. '-' . $this->attr('id'));
        $this->isIdentifier = $this->attr('type')==='identificador';
    }
}

class BCol extends Tag{
    public function __construct(DOMElement $dom,$id) {
        parent::__construct('BCol');
        $this->addAttr($dom,'id',"texto");
        $this->addAttr($dom,'name',"texto");
        $this->addAttr($dom,'type',"texto",TRUE);
        $this->addTag($dom,'title',self::MAX_UMA);
        $this->addTag($dom,'url',self::MAX_UMA);
        $this->addTag($dom,'content',self::MAX_UMA);
        $this->setAttr('id', $id. '-' . $this->attr('id'));
    }
}

class Button extends Tag{
    public function __construct(DOMElement $dom,$id) {
        parent::__construct('Button');
        $this->addAttr($dom, 'id','texto',TRUE);
        $this->addAttr($dom, 'type','texto',TRUE);
        $this->addAttr($dom, 'name');
        $this->addTag($dom,'title',self::MAX_UMA);
        $this->addTag($dom,'icon',self::MAX_UMA);
        $this->addTag($dom,'url',self::MAX_UMA);
        $this->setAttr('id', $id. '-' . $this->attr('id'));
    }
}

class ButtonGroup extends Tag{
    public function __construct(DOMElement $dom,$id) {
        parent::__construct('ButtonGroup');
        $this->setAttr('id',$id . '-buttongroup');
        $this->addTag($dom,'button',self::UMA_MUITAS);
    }
}

class Search extends Tag{
    public function __construct(DOMElement $dom,$id) {
        parent::__construct('Search');
        $this->setAttr('id',$id . '-search');
        $this->addTag($dom,'searchtext',self::UMA);
        $this->addTag($dom,'searchfield',self::UMA);
    }
}

class SearchText extends Tag{
    public function __construct(DOMElement $dom,$id) {
        parent::__construct('SearchField');
        $this->setAttr('id',$id . '-search-text');
        $this->addAttr($dom, 'id','texto');
    }
}

class SearchField extends Tag{
    public function __construct(DOMElement $dom,$id) {
        parent::__construct('SearchField');
        $this->setAttr('id',$id . '-search-field');
        $this->addAttr($dom, 'id','texto');
        $this->addTag($dom, 'option', self::MUITAS);
    }
}

class Option extends Tag{
    public function __construct(DOMElement $dom) {
        parent::__construct('Option');
        $this->addAttr($dom, 'id','texto',TRUE);
        $this->addAttr($dom, 'selected','boleano');
        $this->setAttr('value',$dom->nodeValue);
    }
}