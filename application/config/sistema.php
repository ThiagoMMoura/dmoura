<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Lista de configurações do sistema.
 *
 * @author Thiago Moura
 * @version 0.1
 */
$config['home'] = 'sistema/dashboard'; //URL da página príncipal.
$config['sistema'] = TRUE; //Define se está pasta pertence ao sistema ou ao site.
$config['theme'] = 'sistema/theme/default/';
$config['login'] = 'sistema/autenticacao/login'; //URL do login.
$config['login-required'] = 'sistema/autenticacao/login/error_login_required/' . MSG_ERROR;
$config['level-required'] = 'sistema/autenticacao/login/error_level_required/' . MSG_ERROR;
//$config['template-html'] = 'sistema/html_base';
$config['pular-resenha'] = FALSE;
$config['pagina-resenha'] = 'sistema/usuario/senha/alterar';
$config['hash-senha'] = 'sha1';

$config['prefixo-id-menu'] = 'mn-';