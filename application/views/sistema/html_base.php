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
        //Scripts finais da página
        echo imprime_body_scripts(); ?>
        <div style="position:absolute;float: right;right: 0px;bottom: 20px; padding: 20px; background-color: #ddb;">
            Página renderizada em <small>{elapsed_time}</small> segundos.
        </div>
    </body>
</html>
