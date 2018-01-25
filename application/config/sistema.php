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
$config['login-required'] = 'sistema/autenticacao/login/error_login_required/' . ALERTA_ERRO;
$config['level-required'] = 'sistema/autenticacao/login/error_level_required/' . ALERTA_ERRO;
$config['template-html'] = 'sistema/html_base';
$config['pular-resenha'] = FALSE;
$config['pagina-resenha'] = 'sistema/usuario/senha/alterar';
$config['hash-senha'] = 'sha1';


$config['head-itens-antes-todos'] = array(
    array('meta'=>array('name'=>'x-ua-compatible','type'=>'http-equiv','content'=>'ie=edge')),
    array('meta'=>array('name'=>'viewport','type'=>'name','content'=>'width=device-width, initial-scale=1.0')),
    array('link'=>array('rel'=>'stylesheet','href'=>'assets/framework/css/foundation.min.css')),
    array('link'=>array('rel'=>'stylesheet','href'=>'assets/framework/foundation-icons.css')),
    array('link'=>array('rel'=>'stylesheet','href'=>'assets/css/sistema.css')),
    array('script'=>array('src'=>'assets/framework/js/vendor/jquery.js'))
);
$config['head-itens-depois-todos'] = array();
$config['body-scripts'] = array(
    array('src'=>'assets/framework/js/vendor/what-input.js'),
    array('src'=>'assets/framework/js/vendor/foundation.min.js'),
    array('codigo'=>'$(document).foundation();')
);
$config['prefixo-id-menu'] = 'mn-';
$config['menu-principal'] = array(
    //'titulo-menu' => array('titulo'=>'MENU','li-class'=>'menu-text text-center'),
    'contato' => array(
        'titulo' => 'Contato',
        'url' => '#contato',
        'submenu' => array(
            'telefone' => array(
                'titulo' => 'Telefones',
                'url' => '#telefone',
                'icone' => 'fa-phone',
                'submenu' => array(
                    'operadora' => array(
                        'titulo'=>'Operadora Telefônica',
                        'url'=>'#operadora',
                        'icone' => 'fa-plus',
                        'submenu' => array(
                            'cadastro-operadora' => array('titulo'=>'Cadastro Operadora','url'=>'sistema/contato/telefone/operadora/cadastro','icone' => 'fa-plus')
                        )
                    ),
                    'tipo-telefone' => array(
                        'titulo'=>'Tipo Telefone',
                        'url'=>'#tipo',
                        'icone' => 'fa-plus',
                        'submenu' => array(
                            'cadastro-tipo' => array('titulo'=>'Cadastro Tipo','url'=>'sistema/contato/telefone/tipo/cadastro','icone' => 'fa-plus')
                        )
                    )
                )
            )
        )
    ),
    'pessoa' => array(
        'titulo' => 'Pessoas',
        'url' => '#pessoa',
	'icone' => 'fa-users',
        'submenu' => array(
            'funcionario-cadastro' => array('nome' => 'Cadastro de Funcionário', 'titulo'=>'Funcionários','url'=>'sistema/pessoa/funcionario/cadastro','icone' => 'fa-plus'),
            'fisica-cadastro' => array('titulo'=>'Pessoa Fisica','url'=>'sistema/pessoa/fisica/cadastro','icone' => 'fa-user-plus'),
            'fisica-busca' => array('titulo'=>'Pessoa Fisica','url'=>'sistema/pessoa/fisica/busca','icone' => 'fa-search'),
            //'cadastro-empresa' => array('titulo'=>'Cadastro de Empresas','url'=>'#')
        )
    ),
    'ferramenta' => array(
        'titulo' => 'Ferramentas',
        'url' => '#ferramentas',
        'icone' => 'fa-wrench',
        'submenu' => array(
            'ferramentas-xml' => array(
                'titulo' => 'XML',
                'url' => '#xml',
                'icone' => 'fa-file-code-o',
                'submenu' => array(
                    'extracao-ncm' => array('nome'=>'Extração de código NCM em Notas Fiscais Eletrônicas','titulo'=>'Extrair NCM de NF-e','url'=>'sistema/ferramenta/xml/extracao_ncm','icone'=>'fa-code'),
                    'mescla-csv' => array('nome'=>'Mesclagem de CSV com dados do banco','titulo'=>'Mesclar CSV','url'=>'sistema/ferramenta/xml/mescla_csv','icone'=>'fa-code')
                )
            )
        )
    )
    //'cadastro-produto' => array('titulo'=>'Cadastro de produtos','url'=>'#')
);
$config['estilos_menu'] = array(
    'padrao' => array(),//ul-class,ul-atributos,li-class,li-atributos
    'drilldown' => array('ul-class'=>'vertical menu','ul-atributos'=>array('data-back-button'=>"<li class='js-drilldown-back'><a>Voltar</a></li>",'data-drilldown'=>''))
);
$config['estilos_submenu'] = array(
    'padrao' => array(),
    'drilldown' => array('ul-class'=>'vertical menu')
);
$config['alerta'] = array(
    'classes' => 'callout',
    'tipo' => array(
        ALERTA_SECUNDARIO => 'secondary',
        ALERTA_INFO => 'primary',
        ALERTA_SUCESSO => 'success',
        ALERTA_RISCO => 'warning',
        ALERTA_ERRO => 'alert',
        ALERTA_SISTEMA => 'primary'
    ),
    'fechavel' => array(
        'atributo' => 'data-closable',
        'botao' => '<button class="close-button" aria-label="Fechar alerta" type="button" data-close>
                        <span aria-hidden="true">&times;</span>
                    </button>'
    ),
    'titulo' => array('abre'=>'<h5>','fecha'=>'</h5>'),
    'mensagem' => array('abre'=>'<p>','fecha'=>'</p>')
);