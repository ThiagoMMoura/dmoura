$(document).ready(function(){
    var campo = $('[data-plus-telefone]').attr('data-telefone-campo');
    var lista = $('[data-plus-telefone]').attr('data-telefone-lista');
    var modal = $('[data-plus-telefone]').attr('data-telefone-modal');
    $('[data-plus-telefone] [data-add-telefone]').click(function(){
        plus_telefone(campo, lista, modal);
    });
});
function valida_plus_telefone(){
    var ddd = $('[data-plus-telefone] #ddd').val();
    if(ddd!=="" && ddd.length !== 2){
        alert("Número de DDD deve conter dois digitos.");
        return false;
    }
    var numero = $('[data-plus-telefone] #numero').val();
    if(numero==="" || numero.length < 8){
        alert("Número de telefone inválido. Minimo 8 digitos!");
        return false;
    }
    if(numero!==numero.replace(/[^0-9]/g,"")){
        alert("Digite somente números para o campo telefone.");
        return false;
    }
    var tipo = $('[data-plus-telefone] #tipo_telefone').val();
    if(tipo===null || tipo===""){
        alert("Selecione um tipo.");
        return false;
    }
    return true;
}

function plus_telefone(campo, lista, modal){
    var html = '';
    if(!valida_plus_telefone()){
        return false;
    }
    var ddd = $('[data-plus-telefone] #ddd').val();
    var numero = $('[data-plus-telefone] #numero').val();
    var operadora = $('[data-plus-telefone] #operadora').val();
    var tipo = $('[data-plus-telefone] #tipo_telefone').val();
    var count = $('[data-lista-telefone]').attr('data-telefone-count');
    if(count===undefined){
        count = 1;
    }else{
        count++;
    }
    
    if(campo==='input'){
       ddd = '<input type="text" name="ddd[' + count + ']" value="' + ddd + '" class="only-value" pattern="\\d{0,2}" disabled>';
       numero = '<input type="text" name="numero_telefone['+count+']" value="' + numero + '" class="only-value" pattern="\\d{8,11}" required readonly><span class="form-error">Preenchimento obrigatório</span>';
       operadora = '<input type="text" name="operadora['+count+']" value="' + operadora + '" class="hide">'+ $('[data-plus-telefone] #operadora option:selected').text();
       tipo = '<input type="text" name="tipo['+count+']" value="' + tipo + '" class="hide">'+ $('[data-plus-telefone] #tipo_telefone option:selected').text();
    }
    if(lista==='table'){
        html = '<tr id="plus-tel-linha-' + count + '">';
        html += '<td>' + ddd + '</td>';
        html += '<td>' + numero + '</td>';
        html += '<td>' + tipo + '</td>';
        html += '<td>' + operadora + '</td>';
        html += '<td><a data-excluir-telefone="'+count+'"><i class="fi-x"></i></a></td>';
        html += '</tr>';
        $('table[data-lista-telefone] tbody').append(html);
    }
    if(modal!==undefined){
        $('#'+modal).foundation('close');
    }
    $('[data-excluir-telefone="' + count + '"]').click(function(){
        excluir_telefone(count);
    });
    $('[data-lista-telefone]').attr('data-telefone-count',count);
    
    var vazio = $('[data-telefones-add]').attr('data-telefones-add');
    $('[data-telefones-add]').attr('data-telefones-add',(vazio++));
    $('[data-telefones-add]').hide();
}

function excluir_telefone(id){
    $('#plus-tel-linha-'+id).remove();
    var vazio = $('[data-telefones-add]').attr('data-telefones-add');
    $('[data-telefones-add]').attr('data-telefones-add',vazio--);
    $('[data-telefones-add="0"]').show();
}