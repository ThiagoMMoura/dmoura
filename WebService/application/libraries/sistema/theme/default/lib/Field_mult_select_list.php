<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Field
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Field_mult_select_list extends Field {
    
//    private $first_index;
    
    protected $frame;
    
    public function __construct(Component &$component, Container &$parent = NULL, $key = NULL, $previousSiblingKey = NULL){
        parent::__construct($component, $parent, $key, $previousSiblingKey);
        
        $this->first_index = NULL;
        
        $frame_component_attrs = ['id'=>'modal-' . $this->getId(),'title'=>'Selecionar ' . $this->getAttr('title')];
        $frame_component = new Component('frame', $frame_component_attrs, [],$this->getId());
        
        $this->frame = new Frame($frame_component);
    }
    
    /**
     * Função de inicialização da classe.
     *
     * @return static
     */
    public function &initialize(){
        $button_back = new Component('button',['title'=>'Fechar Seleção','ng'=>['click'=>'actionMultSelectClose()']],[],$this->getId());
        $this->frame->setButtonBack($button_back);

        $this->frame->addTopBarButton(new Component('button',['title'=>'Confirmar','ng'=>['click'=>"actionMultSelectConfirm();"],'data-attr'=>['modal-close'=>$this->frame->getId()]],[],$this->getId()));

        $this->frame->initialize();

        return parent::initialize();
    }
    
    public function getList(){
        return $this->getAttr('list') ?? $this->getName();
    }
    
    /**
     * Retorna o HTML do elemento.
     *
     * @return string
     */
    public function getHTML(){
        $twig = get_instance()->twig->getTwig();
        $template = $twig->load('sistema/theme/default/lib/field.twig');
        
        return $template->render(array(
                  'element_type' => $this->getElementName()
                , 'id'           => $this->getId()
                , 'name'         => $this->getName()
                , 'title'        => $this->getAttr('title')
                , 'class'        => $this->getClass()
                , 'form_name'    => $this->getFormName()
                , 'error_name'   => $this->getErrorName()
                , 'attributes'   => $this->getTagAttributes()
                , 'disabled'     => $this->hasDisabled()
                , 'list'         => $this->getList()
                , 'frame_id'     => $this->frame->getId()
                , 'frame'        => $this->frame->getHTML()
        ));
    }
}
