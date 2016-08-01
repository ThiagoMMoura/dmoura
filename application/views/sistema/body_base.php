<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="top-bar-wrapper">
    <div class="top-bar-wrapper-inner is-open-top">
        <div id="barra-dmoura" class="dm-top-bar position-top">
            <div class="barra-principal fonte-branco">
                <div class="small-7 medium-4 column">
                    <div class="dm-logotipo">
                        <?php echo img('assets/imagens/DMOURA.png');?>
                    </div>
                </div>
                <div class="small-5 medium-8 column">
                    <div class="dm-usuario float-right" data-toggle="dm-usuario-menu">
                        <div class="dm-usuario-foto">
                            <?php echo img('assets/imagens/user.png');?>
                        </div>
                        <div class="dm-usuario-descricao hide-for-small-only">
                            <div class="dm-usuario-nome">
                                Marlene Mariano de Moura
                            </div>
                            <div class="dm-usuario-cargo fonte-cinza-claro">
                                <small>Gerente Administrativo</small>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-pane bottom" id="dm-usuario-menu" data-dropdown data-auto-focus="true" data-close-on-click="true">
                        <ul class="menu">
                            <li><a href="#">Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="barra-ferramentas">
                <div class="busca-menu">
                    <button type="button" class="button hide-for-large" data-toggle="offCanvas"><i class="fi-list"></i></button>
                    <input type="search" class="show-for-large" name="bf-busca-menu" placeholder="Pesquisar Menu">
                    <div class="icone-input"><i class="fi-magnifying-glass show-for-large"></i></div>
                </div>
                <div class="barra-ferramentas-interna">
                    <div class="barra-ferramentas-interna-conteudo">
                        <div class="barra-ferramentas-esquerda">
                            <ul class="menu ">
                                <li class="menu-text">Titulo</li>
                                <li><a href="#">Buscar</a></li>
                                <li><a href="#">Salvar</a></li>
                            </ul>
                        </div>
                        <div class="barra-ferramentas-direita">
                            <ul class="menu ">
                                <li><label for="limpar"><i class="fi-page-delete"></i><span>Limpar</span></label></li>
                                <li><label for="excluir"><i class="fi-trash"></i><span>Excluir</span></label></li>
                                <li><label for="salvar"><i class="fi-save"></i><span>Salvar</span></label></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="top-bar-content">
            <div class="off-canvas-wrapper altura-maxima">
                <div class="off-canvas-wrapper-inner altura-maxima" data-off-canvas-wrapper>
                    <div class="off-canvas position-left" data-reveal-class="reveal-for-large" data-close-on-click="false" id="offCanvas" data-off-canvas>
                        <!-- Busca -->
                        <div class="off-canvas-busca hide-for-large">
                            <input type="search" name="of-busca-menu" placeholder="Pesquisar Menu">
                            <div class="icone-input"><i class="fi-magnifying-glass"></i></div>
                        </div>
                        <!-- Menu -->
                        <div class="off-canvas-menu">
                            <?php echo imprimir_menu($this->config->item('menu-principal'),'drilldown');?>
                        </div>
                    </div>

                    <div id="dmoura-conteudo" class="off-canvas-content" data-off-canvas-content>
                        <div  class="row expanded">
                            <?php echo $imprimir_body;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        if(Foundation.MediaQuery.atLeast('large')){
            $('#offCanvas').foundation('open');
        }else{
            $('#offCanvas').foundation('close');
        }
        var h = $(window).height();
        var b = $('#barra-dmoura').height();
        $('#dmoura-conteudo').css('max-height',h-b).css('height',h-b);
    });
    $(window).on('resize',function(event){
        if(Foundation.MediaQuery.atLeast('large')){
            $('#offCanvas').foundation('open');
        }else{
            $('#offCanvas').foundation('close');
        }
        var h = $(window).height();
        var b = $('#barra-dmoura').height();
        $('#dmoura-conteudo').css('max-height',h-b).css('height',h-b);
    });
</script>