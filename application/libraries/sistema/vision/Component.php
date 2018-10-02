<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Classe que define a estrutura de um Component.
 *
 * @param string $sName
 * @param array $sAttr
 * @param array $aChilds
 * @param string $sParentId
 */
class Component implements JsonSerializable{
    private $CI;

    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var array
     */
    protected $attributes;
    
    /**
     * @var Components_collection
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

    public function __construct($sName, Array $aAttr, Array $aChilds, $sParentId, $content = NULL){
        $this->CI =& get_instance();
        $this->name = $sName;
        $this->attributes = $aAttr;
        $this->childs = new Components_collection($aChilds);
        $this->parent_id = $sParentId;
        $this->content = $content;
    }

    public static function wrap($wrapped){
        if (is_array($wrapped) && !empty($wrapped['name'])) {
            return new Component(
                  $wrapped['name']
                , $wrapped['attrs'] ?? []
                , $wrapped['childs'] ?? []
                , $wrapped['parent_id'] ?? NULL
                , $wrapped['content'] ?? []
            );
        } else if ($wrapped instanceof DOMComponent) {
            return new Component(
                      $wrapped->getName()
                    , $wrapped->getAttrs()
                    , $wrapped->getChilds()
                    , $wrapped->getParentId()
                    , $wrapped->getTextContent()
                );
        } else if ($wrapped instanceof self) {
            return $wrapped;
        }
        
        return NULL;
    }
    
    /**
     * Função para adicionar component filho.
     *
     * @param Component $oChild
     * @return void
     */
    public function addChild(Component $oChild){
        $this->childs->addItem($oChild);
    }

    /**
     * Função retorna os atributos do componente.
     * 
     * @return array
     */
    public function getAttrs(){
        return $this->attributes;
    }

    /**
     * Verifica se atributo existe no componente.
     * 
     * @param string $name
     * @return bool
     */
    public function hasAttr($name){
        return key_exists($name, $this->getAttrs());
    }

    /**
     * Obtém o valor do atributo, caso não exista retorna NULL.
     * 
     * @param string $name
     * @return mixed
     */
    public function getAttr($name){
        return $this->getAttrs()[$name] ?? NULL;
    }
    
    /**
     * Função para alterar valor ou criar novo atributo.
     *
     * @param string $sAttr
     * @param mixed $mValue
     * @return void
     */
    public function setAttr($sAttr,$mValue){
        $this->attributes[$sAttr] = $mValue;
    }
    
    /**
     * Função retorna o nome do componente.
     * 
     * @return string
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * Função altera o nome do componente.
     * 
     * @param string $name
     * @return string
     */
    public function setName($name){
        $this->name = $name;
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
     * Função para alterar a id do pai do componente.
     * 
     * @param string $parent_id
     */
    public function setParentId($parent_id){
        $this->parent_id = $parent_id;
    }
    
    /**
     * Obtém o texto no conteúdo da tag.
     * 
     * @return string
     */
    public function getTextContent(){
        return $this->content;
    }
    
    public function queryComponent($name,$condition = 'equal'){
        $aChildList = [];
        foreach($this->getChilds() as $oChild){
            if($oChild instanceof Component){
                $oChildItem = NULL;
                switch($condition){
                    case 'equal':
                        if($oChild->name == $name){
                            $oChildItem = $oChild;
                        }
                        break;
                    case 'start':
                        if(strpos($oChild->name,$name) === 0){
                            $oChildItem = $oChild;
                        }
                        break;
                    case 'end':
                        if($this->endsWith($oChild->name, $name)){
                            $oChildItem = $oChild;
                        }
                        break;
                    case 'contain':
                        if(substr_count($oChild->name,$name)){
                            $oChildItem = $oChild;
                        }
                        break;
                }

                if($oChildItem != NULL){
                    $aChildList[] = $oChildItem;
                }else{
                    foreach ($oChild->queryComponent($name,$condition) as $oSubChild) {
                        $aChildList[] = $oSubChild;
                    }
                }
            }
        }
        return $aChildList;
    }

    /**
     * Está função verifica se a string <b>$haystack</b> termina com a string <b>$needle</b>, retornado TRUE em caso
     * de sucesso ou FALSE caso contrário.
     * 
     * @param string $haystack
     * @param string $needle
     * @return boolean
     */
    function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 || 
        (substr($haystack, -$length) === $needle);
    }

   public function __get($name){
        
        switch($name){
            case 'tag':
            case 'name':
            case 'tagname':
            case 'tagName':
            case 'tag_name':
            case 'tag-name':
                return $this->name;
            case 'attr':
                return $this->attributes;
            case 'childs':
                return $this->childs;
            case 'parentId':
            case 'parent-id':
            case 'parent_id':
            case 'parentid':
                return $this->parent_id;
            case 'vc':
                return $this->CI->vc;
            default:
                return NULL;
        }
    }

    public function __isset($name){
        $atributes = ['tag','name','tagname','tagName','tag_name','tag-name',
            'attr','childs','parentId','parent-id','parent_id','parentid','vc'
            ];
        
        return in_array($name, $atributes);
    }

    public function __toString() {
        return json_encode($this);
    }
    
    public function jsonSerialize(){
        return [
            'name' => $this->getName(),
            'attr' => $this->getAttrs(),
            'childs' => $this->getChilds(),
            'parent_id' => $this->getParentId(),
            'content' => $this->getTextContent()
        ];
    }
}