<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta charset="utf-8" />
        <?php echo imprime_conteudo_head();?>
    </head>
    <body>
        <?php 
        //Corpo da página
        $this->load->view('sistema/body_base');
        
        if(ENVIRONMENT !== 'production'){
            $this->load->view('sistema/debug_info_sistema');
        } ?>
    </body>
    <?php 
    //Scripts finais da página
    echo imprime_body_scripts();
    ?>
</html>
