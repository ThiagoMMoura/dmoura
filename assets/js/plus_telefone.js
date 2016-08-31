$(document).ready(function(){
    var campo = $('[data-plus-telefone]').attr('data-telefone-campo');
    var lista = $('[data-plus-telefone]').attr('data-telefone-lista');
    $('[data-plus-telefone] [data-add-telefone]').click(plus_telefone(campo, lista));
});

function plus_telefone(campo, lista){
    var html = '';
    var ddd = $('[data-plus-telefone] #ddd').val();
    var numero = $('[data-plus-telefone] #numero').val();
    var operadora = $('[data-plus-telefone] #operadora').val();
    var tipo = $('[data-plus-telefone] #tipo_telefone').val();
    if(campo==='input'){
       ddd = '<input type="text" name="ddd[]" value="' + ddd + '">';
       numero += '<input type="text" name="numero[]" value="' + numero + '">';
       operadora = '<input type="text" name="operadora[]" value="' + operadora + '" hidden>'+ operadora;
       tipo = '<input type="text" name="tipo[]" value="' + tipo + '">'+ tipo;
    }
    if(lista==='table'){
        html = '<tr>';
        html += '<td>' + ddd + '</td>';
        html += '<td>' + numero + '</td>';
        html += '<td>' + operadora + '</td>';
        html += '<td>' + tipo + '</td>';
        html += '</tr>';
        $('table [data-table-telefone] tbody').append(html);
    }
}