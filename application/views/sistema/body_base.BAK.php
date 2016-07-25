<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="top-bar-wrapper">
    <div class="top-bar-wrapper-inner is-open-top">
        <div id="barra-dmoura" class="dm-top-bar position-top fundo-azul fonte-cinza-claro">
            <div class="medium-6 column">
                Coluna 1
            </div>
            <div class="medium-6 column">
                Coluna 2
            </div>
            <button type="button" class="button hide-for-large" data-toggle="offCanvas">Menu</button>
        </div>
        <div class="top-bar-content fundo-azul-claro">
            <div class="row expanded menu-visivel" id="dm-layout">
                <div class="fundo-transparente " id="dm-menu">

                    <!-- Close button -->
                    <button class="close-button hide-for-large" aria-label="Fechar menu" type="button" data-close>
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <!-- Menu -->
                    <div style="padding: 0.7em 1em; background-color: rgba(0,0,0,0.05);"><?php echo $titulo;?></div>
                    <?php echo imprimir_menu($this->config->item('menu-principal'),'drilldown');?>
                </div>

                <div id="dm-content">
                    <div class="row expanded">
                        <div id="dmoura-conteudo"><?php echo $imprimir_body;?></div>
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
        //$('#dmoura-conteudo').css('max-height',h-b);
    });
    $(window).on('resize',function(event){
        if(Foundation.MediaQuery.atLeast('large')){
            $('#offCanvas').foundation('open');
        }else{
            $('#offCanvas').foundation('close');
        }
    });
</script>