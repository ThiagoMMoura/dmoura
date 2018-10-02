<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include "sistema/vision/DOMComponent.php";
include "sistema/vision/Component.php";
include "sistema/vision/Components_collection.php";
include config_item('theme') . 'Theme_control.php';

/**
 * Classe controladora dos dados da Visão.
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Vision_control{

    private $CI;
    
    protected $twig;
    
    protected $theme_control;
    
    protected $page_components;

    public function __construct(){
        $this->CI =& get_instance();
        
        $this->page_components = [];

        $this->CI->load->library('twig');
        $this->twig = $this->CI->twig->getTwig();
        
        /*$filter = new Twig_SimpleFilter('cast_to_array', function ($stdClassObject) {
            // Just typecast it to an array
            $response = (array)$stdClassObject;

            return $response;
        });*/
        
        
        
        //$this->twig->addFilter($filter);
        
        $base_url_func = new Twig_SimpleFunction('base_url',function($str = ''){
            return base_url($str);
        });
        $this->twig->addFunction($base_url_func);
        $this->twig->addGlobal('app',$this->CI);
        
        
    }

    public function display($path,$data = []){
        $this->xmlParser2($path);

        $this->theme_control = new Theme_control($this->twig,$this->getMain(),$this->getMainmenu());

        $this->CI->output->set_output($this->theme_control->display($data));
    }
    
    /**
     * Analisador do XML para converter em array de dados.
     *
     * @param string $sFileName
     * @return object Vision_control
     */
    public function xmlParser($sFileName){
        $this->sCurrentFile = VIEWPATH . $sFileName . ".xml";
        $xmlDOM = $this->loadXML($this->sCurrentFile);
        $oElement = $xmlDOM->documentElement;

        foreach($oElement->childNodes as $oModule){
            if($oModule->nodeName === 'module'){
                if($oModule->getAttribute('name') != NULL){
                    $sModuleName = $oModule->getAttribute('name');
                    $sModuleVersion = $oModule->getAttribute('version');
                    $this->aModules[$sModuleName] = new Module($sModuleName, $sModuleVersion);
                    foreach ($oModule->childNodes as $oEntity) {
                        if($oEntity->nodeName == 'entity'){
                            if($oEntity->getAttribute('name') != NULL){
                                $sEntityName = $oEntity->getAttribute('name');

                                $this->aModules[$sModuleName]->addEntity($this->domelementToEntity($oEntity,$sModuleName.'-'.$sEntityName),$sEntityName);
                            }else{
                                throw new Exception( 'Atributo name não definido para tag: '.$oEntity->nodeName.' na linha: '.$oEntity->getLineNo());
                            }
                        }else if($oEntity->nodeName != '#text'){
                            throw new Exception( 'Tag inválida: '.$oEntity->nodeName.' na linha: '.$oEntity->getLineNo());
                        }
                    }
                }else{
                    throw new Exception( 'Atributo name não definido para tag: '.$oModule->nodeName.' na linha: '.$oModule->getLineNo());
                }
            }else if($oModule->nodeName != '#text'){
                throw new Exception( 'Tag inválida: '.$oModule->nodeName.' na linha: '.$oModule->getLineNo());
            }
        }

        return $this;
    }

    /**
     * Analisador do XML para converter em array de dados.
     *
     * @param string $sFileName
     * @return object Vision_control
     */
    public function xmlParser2($sFileName){
        $sCurrentFile = VIEWPATH . $sFileName . ".xml";
        $xmlDOM = $this->loadXML($sCurrentFile);
        $root = $xmlDOM->documentElement;
        
        foreach($root->childNodes as $parent_tag){
            $node_name = $parent_tag->nodeName;
            if(in_array($node_name,['main_content','main_menu'])){
                log_message('INFO', 'Parser de '.$node_name);
                $childs = [];
                
                foreach ($parent_tag->childNodes as $component) {
                    $childs[] = new DOMComponent($component,$node_name);
                }
                
                $id = explode('_', $node_name);
                $this->page_components[$node_name] = new Component($node_name,['id'=>$id[1]],$childs,$id[0]);
            }else if($node_name != '#text'){
                throw new Exception( "Tag inválida: '$node_name' na linha: " . $parent_tag->getLineNo());
            }
        }
        
        return $this;
    }

    public function loadMainMenu(){
        return $this->page_components['main_menu'] ?? $this->xmlParser2('sistema/main_menu')->page_components['main_menu'];
    }

    /**     
     * Função para converter DOMElement em array
     *
     * @param DOMElement $oElement
     * @return array
     */
    private function domelementToArray(DOMElement $oElement){
        $aArray = [];
        
        foreach($oElement->childNodes as $oChild){
            $aArray[$oChild->nodeName] = $oChild->nodeValue;
        }
        return $aArray;
    }

    /**     
     * Função para converter DOMElement em um array de objetos
     *
     * @param DOMElement $oElement
     * @return array
     */
    private function domelementToArrayOfObjects(DOMElement $oElement,$sParentId){
        $aElements = [];
        
        foreach($oElement->childNodes as $oChild){
            if($oChild instanceof DOMElement){
                $aElements[] = $this->domelementToObject($oChild,$sParentId);
            }
        }
        return $aElements;
    }

    /**     
     * Função para converter DOMElement em Object
     *
     * @param DOMElement $oElement
     * @return Component
     */
    private function domelementToObject(DOMElement $oElement,$sParentId){
        $name = $oElement->nodeName;
        $aChilds = [];
        $aAttr = [];
        $sId = $sParentId;
        $sContent = NULL;
        
        foreach($this->getComponentProperties($name) as $sAttrName => $aAttr){
            $aProp = $this->getAttrProperties($name,$sAttrName);
            if(isset($aProp['required']) && $aProp['required'] && !$oElement->hasAttribute($sAttrName)){
                throw new Exception( "Atributo '".$sAttrName."' obrigatório para tag '".$name."'' na linha ".$oElement->getLineNo().".\nArquivo: ".$this->sCurrentFile);
            }
        }
        foreach($oElement->attributes as $oAttr){
            $aProp = $this->getAttrProperties($name,$oAttr->name);
            $sType = 'text';
            if(isset($aProp['type'])){
                $sType = $aProp['type'];
            }
            switch ($sType) {
                case 'json':
                    $aAttr[$oAttr->name] = json_decode($oAttr->value);
                    if(json_last_error()!==JSON_ERROR_NONE){
                        log_message('ERROR',json_last_error_msg());
                    }
                    break;
                default:
                    $aAttr[$oAttr->name] = $oAttr->value;
                    break;
            }
            
        }
        
        if(isset($aAttr['id']) && $aAttr['id'] != NULL){
        	$aAttr['id'] = $sId .= '-' . $aAttr['id'];
        }else if(isset($aAttr['name']) && $aAttr['name'] != NULL){
        	$aAttr['id'] = $sId .= '-' . $aAttr['name'];
        }

        foreach($oElement->childNodes as $oChild){
            if ($oChild->nodeType === XML_TEXT_NODE) {
                $sContent = $oChild->wholeText;
            }else if($oChild instanceof DOMElement){
                $aChilds[] = $this->domelementToObject($oChild,$sId);
            }
        }

        return new Component($name,$aAttr,$aChilds,$sParentId,$sContent);
    }

    /**     
     * Função para converter DOMElement em objeto Entity
     *
     * @param DOMElement $oElement
     * @return object
     */
    private function domelementToEntity(DOMElement $oElement,$sParentId){
        $oEntity = [];
        $oEntity['name'] = $oElement->getAttribute('name');
        $rules = $this->CI->config->item('entity_rules')[$oEntity['name']];
        
        foreach($oElement->childNodes as $oChild){
            if(key_exists($oChild->nodeName,$oEntity)){
                throw new Exception( 'Tag já existente: '.$oChild->nodeName.'; linha: '.$oChild->getLineNo());
            }else if(key_exists($oChild->nodeName,$rules)){
                switch ($rules[$oChild->nodeName]['type']) {
                    case 'array':
                        $oEntity[$oChild->nodeName] = $this->domelementToArray($oChild);
                        break;
                    case 'arrayOfObjects':
                    	$oEntity[$oChild->nodeName] = $this->domelementToArrayOfObjects($oChild,$sParentId);
                    	break;
                    case 'object':
                    default:
                        if($oChild instanceof DOMElement){
                            $oEntity[$oChild->nodeName] = $this->domelementToObject($oChild,$sParentId);
                        }
                        break;
                }
            }else if($oChild->nodeName != '#text'){
                throw new Exception( 'Tag inválida: '.$oChild->nodeName.' na linha: '.$oChild->getLineNo());
            }
        }
        return $oEntity;
    }

    /**
     * Função que carrega o arquivo XML para um objeto DOM.
     *
     * @param string $sFilePath
     * @return DOMDocument
     */
    private function loadXML($sFilePath){
        $document = new DOMDocument( '1.0', 'UTF-8' );
        $document->preserveWhiteSpace = false;
        $document->load( $sFilePath );
        return $document;
    }

    /**
     * Função para obter o modulo príncipal.
     *
     * @param void
     * @return object $oTopBarComponents;
     */
    public function getMain(){
    	return $this->page_components['main_content'];
    }
    
    /**
     * Função para obter o menu príncipal.
     *
     * @return Component;
     */
    public function getMainmenu(){
    	return $this->loadMainMenu();
    }

    /**
     * Função para obter scripts da página.
     *
     * @param void
     * @return object $oScripts;
     */
    public function getScripts(){
    	return $this->page_components['scripts'];
    }

    /**
     * Função para obter links da página.
     *
     * @param void
     * @return object $oLinks;
     */
    public function getLinks(){
    	return $this->page_components['links'];
    }

    public function newComponent($sName,$aAttr,$aChilds,$sParentId){
        return new Component($sName,$aAttr,$aChilds,$sParentId);
    }

    public function log_message($type,$message){
        log_message($type,$message);
    }

    public function __toString() {
        $oTodo = [
            'page_components' => $this->page_components
        ];
        return json_encode($oTodo);
    }
}