<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Form_button
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Form_button extends Button{
    
    /**
     * @var string
     */
    protected $type;

    /**
     * Construtor da classe Form_button.
     * 
     * @param Component $component
     * @param Container $parent
     * @param mixed $key
     * @param mixed $previousSiblingKey
     */
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        $this->type = $component->getAttr('type');
        unset($component->getAttrs()['type']);
        parent::__construct($component, $parent, $key, $previousSiblingKey);
    }

    /**
     * Função de inicialização da classe.
     *
     * @return $this
     */
    public function &initialize(){
        $this->setAttr('hide',TRUE);
        
        switch($this->type){
            case 'save':
                $this->addNGClickEvent('save();');
                $this->getIcon() || $this->setIcon('save');
                $this->getTitle() || $this->setTitle('Salvar');
            break;
            case 'new':
                $this->addNGClickEvent('newForm();');
                $this->getIcon() || $this->setIcon('plus');
                $this->getTitle() || $this->setTitle('Novo');
            break;
            case 'save_close':
                $this->addNGClickEvent("saveAndLoadPage('" . base_url($this->getAttr('url')) . "');");
                $this->getIcon() || $this->setIcon('save');
                $this->getTitle() || $this->setTitle('Salvar & Fechar');
            break;
            case 'close':
                $this->addNGClickEvent("loadPage('" . base_url($this->getAttr('url'))  . "');");
                $this->getIcon() || $this->setIcon('times');
                $this->getTitle() || $this->setTitle('Fechar');
            break;
        }

        return parent::initialize();
    }

    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/button.twig');
        
        return $template->render(array(
                  'element_type' => 'form_button'
                , 'id'           => $this->getId()
                , 'title'        => $this->getTitle()
                , 'icon'         => $this->getIcon()
                , 'class'        => $this->getClass()
                , 'attributes'   => $this->getTagAttributes()
                , 'title_class'  => $this->getAttr('title_class')
        ));
    }
}
