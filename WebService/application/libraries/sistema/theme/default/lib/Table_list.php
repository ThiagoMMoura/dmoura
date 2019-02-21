<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Table_list
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Table_list extends Element_container{
    
    /**
     * Construtor da classe Table_list.
     * 
     * @param Component $component
     * @param Container $parent
     * @param mixed $key
     * @param mixed $previousSiblingKey
     */
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
    }

    /**
     * Função de inicialização da classe.
     *
     * @return $this
     */
    public function &initialize(){
        //if($this->hasEqualizer()){
           // $this->setAttributeToTag('data-equalizer-watch', '');
        //}
        
        //$this->applyFieldSettings();

        return parent::initialize();
    }

    public static function getTableListObject(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        switch($component->name){
            //case 'tl_filter':
                //return new Table_list_filter($component, $parent, $key, $previousSiblingKey);
            case 'tl_row':
                return new Table_list_row($component, $parent, $key, $previousSiblingKey);
            case 'tl_col_link':
                //return new Table_list_col_link($component, $parent, $key, $previousSiblingKey);
            case 'tl_col':
                return new Table_list_col($component, $parent, $key, $previousSiblingKey);
            case 'tl_button':
                return new Table_list_button($component, $parent, $key, $previousSiblingKey);
            //case 'tl_selection':
                //return new Table_list_selection($component, $parent, $key, $previousSiblingKey);
            case 'table_list':
                return new Table_list($component, $parent, $key, $previousSiblingKey);
        }
    }

    public function getURL(){
        return $this->getAttr('url');
    }

    /**
     * Retorna TRUE caso equalizer esteja ativo, FALSE caso contrário.
     *
     * @return boll
     */
    public function hasEqualizer(){
    	return (bool) $this->getAttr('equalizer');
    }
    
    /**
     * Ativa ou desativa o equalizer.
     *
     * @param bool $value
     * @return static
     */
    protected function setEqualizer($value){
        $this->setAttr('equalizer',$value);
        
        return $this;
    }

    public function __get($name){
        switch ($name) {
            case 'url':
                return $this->getURL();
            default:
                return parent::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['url'];

        return in_array($name, $attributes) || parent::__isset($name);
    }

    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/table_list.twig');

        $row = $this->getElements()->whereInstanceOf(Table_list_row::class)->first();
        
        return $template->render(array(
                  'element_type' => 'table'
                , 'id'           => $this->getId()
                , 'name'         => $this->getName() ?? 'table'
                , 'title'        => $this->getAttr('title')
                , 'url'          => $this->getAttr('url')
                , 'class'        => $this->getClass()
                , 'attributes'   => $this->getTagAttributes()
                , 'head'         => $row->getHeadHTML()
                , 'body'         => parent::getHTML()
        ));
    }
}
