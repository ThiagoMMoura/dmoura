<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function add_head_link($mixed,$attr = array()){
    if(is_array($mixed)){
        
    }else{
        
    }
}

function add_head_item($mixed,$tipo = 'link',$attr = array()){
    if(is_array($mixed)){
        if($tipo==='link'){
            add_head_link($mixed);
        }else if($tipo==='script'){
            
        }else{
            return FALSE;
        }
    }else{
        if($tipo==='link'){
            add_head_link($mixed,$attr);
        }else if($tipo==='script'){
            
        }else{
            return FALSE;
        }
    }
    
}

