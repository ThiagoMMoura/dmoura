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
        <?php /*$this->load->view('sistema/cabecalho_body');
        ?>
        <p>Página renderizada em <small>{elapsed_time}</small> segundos.</p>
        <?php*/
        //Corpo da página
        echo $imprimir_body;
        //Scripts finais da página
        ?>
        <?php echo imprime_body_scripts(); ?>
    </body>
</html>
