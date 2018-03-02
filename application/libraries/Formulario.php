<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Description of Gerador Formulário
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Formulario {
    
    private $CI;
    private $root;
    private $id;
    public function __construct() {
        $this->CI =& get_instance();
        $this->root = [
            'sv_form' => array(),
            'sv_script' => array(),
            'sv_link' => array()
        ];
    }
    
    public function parser($file){
        $xmlDOM = $this->loadXML(VIEWPATH . $file . ".xml");
        
        $form = $xmlDOM->getElementsByTagName('form')->item(0);
        $this->root['sv_form'] = $this->form($form);
        
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
    
    public function get_form(){
        return $this->root['sv_form'];
    }
    
    public function get_script(){
        return $this->root['sv_script'];
    }
    
    public function get_link(){
        return $this->root['sv_link'];
    }
    
    private function form(DOMElement $dom){
        $this->id = $dom->getAttribute('id');
        $field_identifier = $this->id . '-' . $dom->getAttribute('field-identifier');
        if($field_identifier == NULL){
            trigger_error("O atributo 'field-identifier' não foi definido para o form.", E_USER_ERROR);
            die();
        }
        return [
            'sections' => $this->section($dom),
            'buttongroup' => $this->button_group($dom),
            'action' => base_url($dom->getAttribute('action')),
            'method' => $dom->getAttribute('method'),
            'id' => $this->id,
            'field-identifier' => $field_identifier,
            'not-permitted' => explode('|',$dom->getAttribute('not-permitted')),
            'atributes' => $dom->getAttribute('atributes')
        ];
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
    
    private function section(DOMElement $form){
        $sections = $form->getElementsByTagName('section');
        $section = array();
        foreach($sections as $sec){
            $section[] = [
                'name' => $sec->getAttribute('name'),
                'title' => $sec->getAttribute('title'),
                'id' => $this->id . '-' . $sec->getAttribute('id'),
                'class' => $sec->getAttribute('class'),
                'colunm' => $this->column($sec),
                'screen-min' => $sec->getAttribute('screen-min'),
                'atributes' => $sec->getAttribute('atributes'),
                'fields' => $this->fields($sec)
            ];
        }
        return $section;
    }
    
    private function fields(DOMElement $section){
        $fields = $section->getElementsByTagName('field');
        $field = array();
        foreach($fields as $fld){
            $temp = $this->field($fld);
            $field[$temp['name']] = $temp;
        }
        return $field;
    }
    
    private function subfields(DOMElement $dom){
        $subfields = $dom->getElementsByTagName('subfield');
        $subfield = array();
        foreach($subfields as $nfld){
            $temp = $this->field($nfld,$this->id . '-' . $dom->getAttribute('id'));
            $subfield[$temp['id']] = $temp;
        }
        return $subfield;
    }
    
    private function field(DOMElement $dom,$id = ''){
        if($id===''){
            $id = $this->id;
        }
        $type = $dom->getAttribute('type');
        return [
                'type' => $type,
                'legend' => $dom->getAttribute('legend'),
                'description' => $dom->getAttribute('description'),
                'name' => $dom->getAttribute('name'),
                'value' => $dom->getAttribute('value'),
                'id' => $id . '-' . $dom->getAttribute('id'),
                'class' => $dom->getAttribute('class'),
                'placeholder' => $dom->getAttribute('placeholder'),
                'required' => $dom->getAttribute('required')=="TRUE",
                'disabled' => $dom->getAttribute('disabled')=="TRUE" || $type=="identificador",
                'autofocus' => $dom->getAttribute('autofocus')=="TRUE",
                'checked' => $dom->getAttribute('checked')=="TRUE",
                'maxlength' => $dom->getAttribute('maxlength'),
                'tabindex' => $dom->getAttribute('tabindex'),
                'action' => $dom->getAttribute('action'),
                'column' => $this->column($dom),
                'atributes' => $dom->getAttribute('atributes'),
                'options' => $this->option($dom),
                'subfields' => $this->subfields($dom)
            ];
    }
    
    private function option(DOMElement $field){
        $options = $field->getElementsByTagName('options');
        $option = array();
        if($options->length>0){
            $opt = $options->item(0);

            $option = [
                'selected' => $field->getAttribute('value'),
                'list-url' => $opt->getAttribute('list-url')?base_url($opt->getAttribute('list-url')):NULL,
                'list-dbfield' => explode("|",$opt->getAttribute('list-dbfield')),
                'list-data' => json_decode($opt->getAttribute('list-data'))
            ];
            $opts = $opt->getElementsByTagName('option');
            foreach($opts as $op){
                $option['options'][] = [
                    'id' => $op->getAttribute('id'),
                    'value' => $op->nodeValue
                ];
            }
        }
        return $option;
    }
    
    private function button_group(DOMElement $dom){
        $group = $dom->getElementsByTagName('buttongroup')->item(0);
        $btn_group = array();
        if($group != NULL){
            $buttons = $group->getElementsByTagName('button');
            foreach($buttons as $button){
                $temp = $this->button($button);
                $btn_group[$temp['id']] = $temp;
            }
        }
        return $btn_group;
    }
    
    private function button(DOMElement $dom){
        return [
            'type' => $dom->getAttribute('type'),
            'name' => $dom->getAttribute('name'),
            'title' => $dom->getAttribute('title'),
            'id' => $this->id . '-' . $dom->getAttribute('id'),
            'class' => $dom->getAttribute('class'),
            'icon' => $dom->getAttribute('icon'),
            'url' => base_url($dom->getAttribute('url')),
            'atributes' => $dom->getAttribute('atributes')
        ];
    }
    
    private function loadXML($nome){
        $document = new DOMDocument( '1.0', 'UTF-8' );
        $document->preserveWhiteSpace = false;
        $document->load( $nome );
        return $document;
    }
    
    private function column(DOMElement $dom){
        $column = array('s'=>12);
        $attr = $dom->getAttribute('column');
        
        if($attr!=''){
            foreach(explode('|',$attr) as $col){
                $aux = explode(':',$col);
                $column[$aux[0]] = $aux[1];
            }
        }
        
        return $column;
    }
}

