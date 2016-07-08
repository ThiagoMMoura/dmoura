<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <?php echo imprime_conteudo_head();?>
    </head>
    <body>
        <p>Página renderizada em <small>{elapsed_time}</small> segundos.</p>
        <?php
        echo $imprimir_body;
        ?>
    </body>
</html>
