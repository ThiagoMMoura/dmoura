Olá, <?php echo $nome;?>

Seu cadastro foi efetuado com sucesso.

<?php if(isset($senha)){?>
Sua senha de acesso é: <font style="color: green;"><?php echo $senha;?></font>
<?php } ?>
Para sua segurança, em seu primeiro login você será redirecionado para a criação de uma nova senha.

Atenciosamente,
Equipe <?php echo config_item('nome_fantasia');?>
<?php echo config_item('email_contato');
 