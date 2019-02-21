<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description of Form_button
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Form_button extends Top_bar_button{
    
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
}
