<div class="row">
    <div class="column">
        
    </div>
</div>
<div class="row">
    <div class="column">
        <?php foreach($lista_pessoas as $pessoa){
            foreach($pessoa as $k => $v){
                echo $k . ' - ' . $v . ' | ';
            }
            echo '<br/>';
        }?>
    </div>
</div>

