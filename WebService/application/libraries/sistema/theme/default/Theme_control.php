<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include "lib/Default_theme.php";

/**
 * Description of Theme Control
 *
 * @package	Application
 * @subpackage	Libraries
 * @author Thiago Moura
 */
class Theme_control implements JsonSerializable{
    
    private $CI;
    protected $main_content;
    
    protected $twig;
    
    protected $theme_path;
    
    /**
     *
     * @var Mainmenu
     */
    protected $main_menu;
    
    public function __construct(\Twig_Environment &$twig, Component $main_content, Component $menu) {
        $this->CI =& get_instance();
        $this->theme_path = config_item('theme');
        $this->twig =& $twig;
        
        $angularParser = new Twig_SimpleFilter('NG', function ($string) {
            return "{[{".$string."}]}";
        });
        $this->twig->addFilter($angularParser);
        $this->twig->addExtension(new Twig_Extension_StringLoader());
        
        // Criando e inicializando menu príncipal.
        $this->main_menu = new Mainmenu($menu);
        $this->main_menu->initialize();
        
        // Criando e inicializando conteúdo príncipal.
        $this->main_content = new Element_container($main_content);
        log_message('INFO', 'INITIALIZE ALL');
        $this->main_content->initialize();
        
        $this->twig->addGlobal('_data',$this);
        $this->twig->addGlobal('sv_main_menu',$this->main_menu->getHTML());
    }
    
    public function getForms(){
        return $this->main_content->whereInstanceOfRecursive(Form::class);
    }
    
    public function getTableLists(){
        return $this->main_content->whereInstanceOfRecursive(Table_list::class);
    }
    
    public function display($data = []){
        
        return $this->twig->render($this->theme_path . 'master_view.twig',$data);
    }
    
    public function __get($name) {
        switch($name){
            case 'forms':
                return $this->getForms();
            case 'table_lists':
                return $this->getTableLists();
            default:
                return NULL;
        }
    }
    
    public function __isset($name){
        $attributes = ['forms','table_lists'];
        
        return in_array($name, $attributes);
    }
    
    public function getHTML(){
        return $this->main_content->getHTML();
    }

    public function __toString(){
        return json_encode($this->jsonSerialize());
    }

    public function jsonSerialize(){
        return [
            'forms' => $this->getForms()
        ];
    }
}
