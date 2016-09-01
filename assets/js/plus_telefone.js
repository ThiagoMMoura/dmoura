$(document).ready(function(){
    var campo = $('[data-plus-telefone]').attr('data-telefone-campo');
    var lista = $('[data-plus-telefone]').attr('data-telefone-lista');
    $('[data-plus-telefone] [data-add-telefone]').click(function(){
        plus_telefone(campo, lista);
    });
});

function plus_telefone(campo, lista){
    var html = '';
    var ddd = $('[data-plus-telefone] #ddd').val();
    var numero = $('[data-plus-telefone] #numero').val();
    if(numero==="" || numero.length < 8){
        alert("Número de telefone inválido.");
        return;
    }
    var operadora = $('[data-plus-telefone] #operadora').val();
    var tipo = $('[data-plus-telefone] #tipo_telefone').val();
    var count = $.attr('[data-telefone-count]');
    if(count===undefined){
        count = 1;
    }else{
        count++;
    }
    
    if(campo==='input'){
       ddd = '<input type="text" name="ddd[' + count + ']" value="' + ddd + '" class="only-value">';
       numero = '<input type="text" name="numero['+count+']" value="' + numero + '" class="only-value">';
       operadora = '<input type="text" name="operadora['+count+']" value="' + operadora + '" class="hide">'+ $('[data-plus-telefone] #operadora option:selected').text();
       tipo = '<input type="text" name="tipo['+count+']" value="' + tipo + '" class="hide">'+ $('[data-plus-telefone] #tipo_telefone option:selected').text();
    }
    if(lista==='table'){
        html = '<tr>';
        html += '<td>' + ddd + '</td>';
        html += '<td>' + numero + '</td>';
        html += '<td>' + tipo + '</td>';
        html += '<td>' + operadora + '</td>';
        html += '<td><a onclick="excluir_telefone('+count+')"><i class="fi-x"></i></a></td>';
        html += '</tr>';
        if(count===1){
            $('table[data-lista-telefone] tbody').html('');
        }
        $('table[data-lista-telefone] tbody').append(html);
    }
}