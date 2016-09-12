<?php
if(isset($_callout)){?>
    <div class="row form-page">
        <div class="column small-12">
            <?php echo alertas($_callout['titulo'], $_callout['mensagem'], $_callout['tipo'], $_callout['fechavel']);?>
        </div>
    </div>
<?php }

