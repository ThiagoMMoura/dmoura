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
                <div class="off-canvas-wrapper-inner altura-maxima is-off-canvas-open is-open-left" ><?php //data-off-canvas-wrapper ?>
                    <div class="off-canvas position-left fundo-transparente is-open" aria-hidden="false" id="offCanvas" ><?php //data-off-canvas?>

                        <!-- Close button -->
                        <button class="close-button hide-for-large" aria-label="Close menu" type="button" data-close>
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <!-- Menu -->
                        <ul class="vertical menu fonte-cinza-claro">
                            <li><a href="#">Foundation</a></li>
                            <li><a href="#">Dot</a></li>
                            <li><a href="#">ZURB</a></li>
                            <li><a href="#">Com</a></li>
                            <li><a href="#">Slash</a></li>
                            <li><a href="#">Sites</a></li>
                        </ul>

                    </div>

                    <div class="off-canvas-content"><?php //data-off-canvas-content?>

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
    $(document).ready(
            if($(window).width()<1024px){
                $.('#offCanvas').parent('.off-canvas-wrapper-inner').attr('data-off-canvas-wrapper','');
            }
    );
</script>