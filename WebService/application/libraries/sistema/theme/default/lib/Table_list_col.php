<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//define('MAX_RESPONSIVE_SIZE',12);

/**
 * Description of Table_list_col
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Table_list_col extends Element{
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * Construtor da classe Table_list_col.
     * 
     * @param Component $component
     * @param Container $parent
     * @param mixed $key
     * @param mixed $previousSiblingKey
     */
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
        $this->type = str_replace('tl_col_', '', $this->getElementName());
    }

    /**
     * Função de inicialização da classe.
     *
     * @return $this
     */
    public function &initialize(){      
        //$this->applyFieldSettings();

        return parent::initialize();
    }

    /**
     * Retorna o atributo type do elemento.
     *
     * @return mixed
     */
    public function getDataType(){
    	return $this->type;
    }
    
    /**
     * Altera o valor do atributo type do campo.
     *
     * @param mixed $type
     * @return static
     */
    public function setDataType($type) {
        $this->type = $type;
        return $this;
    }

    public function getTitle() {
        return $this->getAttr('title');
    }

    public function isOrdered() {
        return !($this->hasAttr('not-ordered') && $this->getAttr('not-ordered'));
    }
    
    public function getOrder() {
        return $this->isOrdered() ? ($this->getAttr('order') ?? 'ASC') : NULL;
    }

    public function getHeadClass(){
        return '';
    }

    public function getHeadTagAttributes(){
        return '';
    }

    public function __get($name){
        switch ($name) {
            case 'order':
                return $this->getOrder();
            case 'title':
                return $this->getTitle();
            case 'data_type':
                return $this->getDataType();
            default:
                return parent::__get($name);
        }
    }
    
    public function __isset($name){
        $attributes = ['order','title','data_type'];

        return in_array($name, $attributes) || parent::__isset($name);
    }

    public function getHeadHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/table_list.twig');
        
        return $template->render(array(
                  'element_type' => 'table_head_col'
                , 'id'           => $this->getId()
                , 'title'        => $this->getTitle()
                , 'order'        => $this->getOrder()
                , 'class'        => $this->getHeadClass()
                , 'attributes'   => $this->getHeadTagAttributes()
        ));
    }

    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/table_list.twig');
        
        return $template->render(array(
                  'element_type' => 'table_body_col'
                , 'type'         => $this->getDataType()
                , 'id'           => $this->getId()
                , 'name'         => $this->getName()
                , 'url'          => $this->getAttr('url')
                , 'class'        => $this->getClass()
                , 'attributes'   => $this->getTagAttributes()
        ));
    }
}
