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
                <div class="off-canvas-wrapper-inner altura-maxima" data-off-canvas-wrapper><?php //is-off-canvas-open is-open-left ?>
                    <div class="off-canvas position-left fundo-transparente " data-reveal-class="reveal-for-large" data-close-on-click="false" id="offCanvas" data-off-canvas><?php //aria-hidden="false" is-open?>

                        <!-- Close button -->
                        <button class="close-button hide-for-large" aria-label="Close menu" type="button" data-close>
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <!-- Menu -->
                        <ul class="vertical menu fonte-cinza-claro">
                            <?php foreach($this->config->item('menu-principal') as $k => $v){
                                if(is_array($v)){
                                    $_titulo = isset($v['titulo'])?$v['titulo']:$k;
                                    $_url = isset($v['url'])?$v['url']:'#';
                                    ?>
                                    <li><a href="<?php echo $_url;?>"><?php echo $_titulo;?></a></li>
                                <?php }
                            }?>
                        </ul>
                    </div>

                    <div class="off-canvas-content" data-off-canvas-content>

                        <div class="row expanded">
                            Corpo
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