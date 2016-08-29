<p>Olá, <?php echo $nome;?></p>

<p>Seu cadastro foi efetuado com sucesso.</p>
<br/>
<p>
<?php if(isset($senha)){?>
Sua senha de acesso é: <font style="color: green;font-weight: bold"><?php echo $senha;?></font><br/>
<?php } ?>
Para sua segurança, em seu primeiro login você será redirecionado para a criação de uma nova senha.
</p>
<p>
    Atenciosamente,<br/>
    Equipe <?php echo config_item('nome_fantasia');?><br/>
    <?php echo config_item('email_contato');?><br/>
</p>