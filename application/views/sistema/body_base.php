<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="top-bar-wrapper">
    <div class="top-bar-wrapper-inner is-open-top">
        <div id="barra-dmoura" class="dm-top-bar position-top fundo-azul fonte-branco">
            <div class="medium-6 column">
                <div class="dm-logotipo">
                    <?php echo img('assets/imagens/DMOURA.png');?>
                </div>
            </div>
            <div class="medium-6 column">
                <div class="dm-usuario">
                    <div class="dm-usuario-foto text-center">
                        <?php echo img('assets/imagens/user.png');?>
                    </div>
                    <div class="dm-usuario-descricao text-center">
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
            <button type="button" class="button hide-for-large" data-toggle="offCanvas">Menu</button>
        </div>
        <div class="top-bar-content fundo-azul">
            <div class="off-canvas-wrapper altura-maxima">
                <div class="off-canvas-wrapper-inner altura-maxima" data-off-canvas-wrapper>
                    <div class="off-canvas position-left fundo-transparente " data-reveal-class="reveal-for-large" data-close-on-click="false" id="offCanvas" data-off-canvas>

                        <!-- Close button 
                        <button class="close-button hide-for-large" aria-label="Fechar menu" type="button" data-close>
                            <span aria-hidden="true">&times;</span>
                        </button>-->

                        <!-- Menu -->
                        <div class="fundo-azul-4" style="padding: 0.7em 1em;"><?php echo $titulo;?></div>
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