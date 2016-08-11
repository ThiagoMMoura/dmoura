<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?php echo base_url('assets/framework/css/foundation.min.css');?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/framework/foundation-icons.css');?>">
        <link rel="stylesheet" href="<?php echo base_url('assets/css/sistema/login.css');?>">
        <script src="<?php echo base_url('assets/framework/js/vendor/jquery.js');?>"></script>
        <title>Login D'Moura</title>
    </head>
    <body>
        <div class="row">  
          <div class="small-12 medium-centered medium-6 columns login">
                <?php echo validation_errors(); ?>
            <?php echo form_open('sistema/login/entrar');?>
            <div class="row">
              <div class="large-12 columns text-center">
                <?php echo heading("D'Moura",2);?>
              </div>
            </div>
            <div class="row">
              <div class="large-12 columns">
                <?php
                $form_email = array('name'=>'usuario','placeholder'=>'Email ou matricula');
                echo form_label('Usuário'.form_input($form_email,''));
                ?>
              </div>
            </div>
            <div class="row">
              <div class="large-12 columns">
                <?php
                $form_senha = array('name'=>'senha','placeholder'=>'Senha');
                echo form_label('Senha'.form_password($form_senha,''));
                ?>
              </div>
            </div>
            <div class="row">
              <div class="large-12 columns">
                <?php echo form_submit('entrar', 'Entrar', 'class="button expanded large"'); ?>
              </div>
            </div>
            <?php
            echo form_close();
            ?>
          </div>
        </div>
        <script src="<?php echo base_url('assets/framework/js/vendor/what-input.js');?>"></script>
        <script src="<?php echo base_url('assets/framework/js/vendor/foundation.min.js');?>"></script>
        <script>$(document).foundation();</script>
        <script type="text/javascript">
            function centralizar_login(){
                var winh = $(window).height() || $(window).clientHeight();
                var margin = (winh - $('.login').height()) / 2;
                $('.login').css('margin-top',margin);
                $('.login').css('margin-bottom',margin);
            }
            centralizar_login();
            $(window).resize(function (){
                centralizar_login();
            });
        </script>
    </body>
</html>