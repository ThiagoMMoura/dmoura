<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="top-bar-wrapper">
    <div class="top-bar-wrapper-inner is-open-top">
        <div id="barra-dmoura" class="dm-top-bar position-top fundo-azul fonte-branco">
            <div class="barra-principal">
                <div class="small-6 column">
                    <div class="dm-logotipo">
                        <?php echo img('assets/imagens/DMOURA.png');?>
                    </div>
                </div>
                <div class="small-6 column">
                    <div class="dm-usuario">
                        <div class="dm-usuario-foto">
                            <?php echo img('assets/imagens/user.png');?>
                        </div>
                        <div class="dm-usuario-descricao hide-for-small-only">
                            <div class="dm-usuario-nome">
                                Marlene Mariano de Moura
                            </div>
                            <div class="dm-usuario-cargo hide-for-small-only fonte-cinza-claro">
                                <small>Gerente Administrativo</small>
                            </div>
                        </div>
                        <div class="dm-usuario-menu">

                        </div>
                    </div>
                </div>
            </div>
            <div class="barra-ferramentas fundo-azul-8">
                <div class="busca-menu">
                    <button type="button" class="button hide-for-large fundo-azul-4" data-toggle="offCanvas"><i class="fi-list"></i></button>
                    <input type="search" class="fundo-azul-4 show-for-large" name="in-busca-menu" placeholder="Pesquisa menus">
                    <i class="fi-magnifying-glass show-for-large"></i>
                </div>
                <div class="barra-ferramentas-interna">
                    <div class="barra-ferramentas-esquerda">
                        <ul class="menu ">
                            <li><a href="#">Deletar</a></li>
                            <li><a href="#">Buscar</a></li>
                            <li><a href="#">Salvar</a></li>
                        </ul>
                    </div>
                    <div class="barra-ferramentas-direita">
                        Titulo
                    </div>
                </div>
            </div>
            
        </div>
        <div class="top-bar-content fundo-azul-1">
            <div class="off-canvas-wrapper altura-maxima">
                <div class="off-canvas-wrapper-inner altura-maxima" data-off-canvas-wrapper>
                    <div class="off-canvas position-left fundo-transparente " data-reveal-class="reveal-for-large" data-close-on-click="false" id="offCanvas" data-off-canvas>

                        <!-- Close button 
                        <button class="close-button hide-for-large" aria-label="Fechar menu" type="button" data-close>
                            <span aria-hidden="true">&times;</span>
                        </button>-->

                        <!-- Menu -->
                        <div class="fundo-azul-4 hide-for-large" style="padding: 0.7em 1em;"></div>
                        <?php echo imprimir_menu($this->config->item('menu-principal'),'drilldown');?>
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