<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
$itens = [
    [
        'title'=>'Como usar a tag script – JavaScript',
        'link'=>'https://devheroes.io/como-usar-tag-script-javascript-s01e03/',
        'description'=>'Neste post vamos ver como usar a tag script, inline ou arquivo externo, onde posicionar ela e quais são seus principais atributos.'
    ],
    [
        'title'=>'Font Awesome',
        'link'=>'https://fontawesome.com/cheatsheet',
        'description'=>'Referências de icones usados no sitema.'
    ],
    [
        'title' => 'Utilizando Sessões em PHP de forma inteligente',
        'link' => 'https://rubsphp.blogspot.com/2011/04/sessoes-em-php.html',
        'description' => 'Artigo que explica os conceitos de sessões em sistemas Web, como utilizá-las de forma correta, como configurá-las no PHP e como usar a variável $_SESSION.'
    ]
];
?>
<html>
    <head>
        <title>Links estudo</title>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="assets/framework/css/foundation.min.css" />
        <link rel="stylesheet" href="assets/framework/foundation-icons.css" />
    </head>
    <body>
        <h3 class="text-center">Páginas para estudo e implementação no sistema</h3>
        <ul class="no-bullet">
            <?php foreach ($itens as $item) { ?>
            <li>
                <a href="<?php echo $item['link']; ?>"><h4><?php echo $item['title']; ?></h4></a>
                <p><b>Descrição: </b><?php echo $item['description']; ?></p>
            </li>
            <?php } ?>
        </ul>
    </body>
</html>
