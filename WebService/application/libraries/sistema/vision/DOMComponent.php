<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe que define a estrutura de um DOMComponent.
 *
 */
class DOMComponent{

    /**
     * @var DOMNode
     */
    protected $dom;
    
    /**
     * @var array
     */
    protected $attributes;
    
    /**
     * @var array
     */
    protected $childs;
    
    /**
     * @var string
     */
    protected $parent_id;
    
    /**
     * @var string
     */
    protected $content;
    
    /**
     * @var bool
     */
    private $has_id;

    /**
     * Construtor da classe DOMComponent.
     * 
     * @param DOMNode $dom
     * @param string $parent_id
     * @throws Exception
     */
    public function __construct(DOMNode $dom, $parent_id){
        $this->dom = $dom;
        $this->parent_id = $parent_id;
        $this->attributes = [];
        $this->childs = [];

        foreach(DOMComponent::getComponentProperties($this->getName()) as $attr => $value){
            $aProp = DOMComponent::getAttrProperties($this->getName(),$attr);
            
            if(isset($aProp['required']) && $aProp['required'] && !$dom->hasAttribute($attr)){
                throw new Exception( "Atributo '".$attr."' obrigatório para tag '".$this->getName()."'' na linha ".$dom->getLineNo().".\nArquivo: "/*.$this->sCurrentFile*/);
            }
        }
        
        // Parse dos atributos do Component
        foreach($this->dom->attributes as $attr){
            $this->attributes[$attr->name] = DOMComponent::parseAttr($this->getName(),$attr->name,$attr->value);
        }
        
        $this->generateId();
        
        // Conversão dos childs em DOMComponent
        foreach($this->dom->childNodes as $child){
            if ($child->nodeType === XML_TEXT_NODE) {
                $this->content = $child->wholeText;
            }else if($child instanceof DOMElement){
                $this->childs[] = new DOMComponent($child,$this->getId() ?? $this->getParentId());
            }
        }
    }

    /**
     * Obtém a Id do componente.
     * 0
     * @return string
     */
    public function getId(){
        $this->generateId();

        return $this->attributes['id'] ?? NULL;
    }
    
    /**
     * Gera a Id para o componente.
     * 
     * @return void
     */
    protected function generateId(){
        if (!$this->has_id) {
            $id = $this->getAttrs()['id'] ?? ($this->getAttrs()['name'] ?? NULL);
            
            $id && ($this->attributes['id'] = $this->parent_id . "-$id");
            
            $this->has_id = TRUE;
        }
    }

    /**
     * Função retorna os atrbiutos do componente.
     * 
     * @return array
     */
    public function getAttrs(){
        return $this->attributes;
    }
    
    /**
     * Função retorna o nome do componente.
     * 
     * @return string
     */
    public function getName(){
        return $this->dom->nodeName;
    }
    
    /**
     * Função retorna os filhos do componente.
     * 
     * @return Components_collection
     */
    public function getChilds(){
        return $this->childs;
    }
    
    /**
     * Função retorna a id do pai do componente.
     * 
     * @return string
     */
    public function getParentId(){
        return $this->parent_id;
    }
    
    /**
     * Obtém o texto no conteúdo da tag.
     * 
     * @return string
     */
    public function getTextContent(){
        return $this->content;
    }
    
    /**
     * Converte o valor de um atributo conforme o tipo de dados.
     * 
     * @param string $component
     * @param string $attr
     * @param string $value
     * @return mixed
     */
    public static function parseAttr($component,$attr,$value){
        $type = DOMComponent::getTypeAttr($component,$attr);

        switch ($type) {
            case 'json':
                $value = json_decode($value);
                if(json_last_error()!==JSON_ERROR_NONE){
                    log_message('ERROR',json_last_error_msg());
                }
                break;
        }

        return $value;
    }

    /**
     * Obtém o tipo de dado de um atributo especifico de um componente especifico.
     * 
     * @param string $component
     * @param string $attr
     * @return string
     */
    public static function getTypeAttr($component,$attr){
        return DOMComponent::getAttrProperties($component,$attr)['type'] ?? 'text';
    }

    /**
     * Retorna as proriedades de um atributos especifico de um Component.
     *
     * @param string $sElementName
     * @param string $sAttrName
     * @return array
     */
    public static function getAttrProperties($sElementName,$sAttrName){
        if(isset(DOMComponent::getComponentProperties($sElementName)[$sAttrName])){
            return DOMComponent::getComponentProperties($sElementName)[$sAttrName];
        }
        return [];
    }

    /**
     * Retorna as propriedade do component.
     *
     * @param string $sElementName
     * @return array
     */
    public static function getComponentProperties($sElementName){
        $aAttributeProperties = [
            'field_dataset_add' => [
                'labels' => ['type'=>'json']
            ],
            'field_text' => [
                'size' => ['type'=>'json']
            ],
            'field_select_list' => [
                'size' => ['type'=>'json']
            ],
            'field_date' => [
                'size' => ['type'=>'json']
            ],
            'field_password' => [
                'size' => ['type'=>'json']
            ],
            'field_pass' => [
                'size' => ['type'=>'json']
            ],
            'field_psw' => [
                'size' => ['type'=>'json']
            ],
            'button' => [
                'id' => ['required'=>TRUE]
            ],
            'button_save' => [
                'id' => ['required'=>TRUE]
            ],
            'button_new' => [
                'id' => ['required'=>TRUE]
            ],
            'button_save_close' => [
                'id' => ['required'=>TRUE]
            ],
            'button_close' => [
                'id' => ['required'=>TRUE]
            ]
        ];
        
        if(isset($aAttributeProperties[$sElementName])){
            return $aAttributeProperties[$sElementName];
        }
        
        return [];
    }

    public function __get($name){
        switch($name){
            case 'name':
                return $this->name;
            case 'attrs':
                return $this->attributes;
            case 'childs':
                return $this->childs;
            case 'parent_id':
                return $this->parent_id;
            default:
                return NULL;
        }
    }

    public function __isset($name){
        $atributes = ['name','attrs','childs','parent_id'];
        
        return in_array($name, $atributes);
    }

    public function __toString() {
        return json_encode($this->jsonSerialize());
    }
    
    public function jsonSerialize(){
        return [
            'name' => $this->getName(),
            'attrs' => $this->getAttrs(),
            'childs' => $this->getChilds(),
            'parent_id' => $this->getParentId(),
            'content' => $this->getTextContent()
        ];
    }
}