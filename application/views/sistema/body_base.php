<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="top-bar-wrapper">
    <div class="top-bar-wrapper-inner is-open-top">
        <div class="dm-top-bar position-top fundo-azul fonte-cinza-claro">
            <div class="medium-6 column">
                Coluna 1
            </div>
            <div class="medium-6 column">
                Coluna 2
            </div>
            <button type="button" class="button hide-for-large" data-toggle="offCanvas">Menu</button>
        </div>
        <div class="top-bar-content fundo-azul-claro">
            <div class="off-canvas-wrapper altura-maxima">
                <div class="off-canvas-wrapper-inner altura-maxima" data-off-canvas-wrapper>
                    <div class="off-canvas position-left fundo-transparente " data-reveal-class="reveal-for-large" data-close-on-click="false" id="offCanvas" data-off-canvas>

                        <!-- Close button -->
                        <button class="close-button hide-for-large" aria-label="Fechar menu" type="button" data-close>
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <!-- Menu -->
                        <div style="padding: 0.7em 1em; background-color: rgba(0,0,0,0.05);"><?php echo $titulo;?></div>
                        <?php echo imprimir_menu($this->config->item('menu-principal'),'drilldown');?>
                    </div>

                    <div class="off-canvas-content" style="box-shadow: none;" data-off-canvas-content>

                        <div class="row expanded">
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
    });
    $(window).on('resize',function(event){
        if(Foundation.MediaQuery.atLeast('large')){
            $('#offCanvas').foundation('open');
        }else{
            $('#offCanvas').foundation('close');
        }
    });
</script>