$(document).ready(function(){
    $('[data-plus-tel-form]').appendTo('body');
    var hide = $('[data-plus-tel-lista]').attr('data-hide-on-empty');
    if(hide==='true'){
        $('[data-plus-tel-lista]').hide();
    }
    $('[data-plus-telefone]').click(function(){
        add_telefone();
    });
    var pre_values = JSON.parse($('[data-plus-tel-lista]').attr('data-pre-values'));
    if(pre_values.length > 0){
        var value = '';
        for(value in pre_values){
            add_telefone(pre_values[value]);
        }
    }
    $('[data-plus-tel-lista]').attr('data-pre-values','');
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

function excluir_telefone(form){
    var id = $(form).attr('data-excluir-tel');
    $('[data-plus-tel-lista] [data-plus-tel-item="' + id + '"]').remove();
    var count = $('[data-plus-tel-lista]').attr('data-tel-count');
    count--;
    if(count===0){
        var hide = $('[data-plus-tel-lista]').attr('data-hide-on-empty');
        if(hide==='true'){
            $('[data-plus-tel-lista]').hide();
        }
    }
    $('[data-plus-tel-lista]').attr('data-tel-count',count);
}

function add_telefone(dados){
    var form = $('[data-plus-tel-form]').html();
    var last = $('[data-plus-tel-form] [data-plus-tel-item]').attr('data-plus-tel-item');
    var count = $('[data-plus-tel-lista]').attr('data-tel-count');
    
    if(last===null){
        last = 0;
    }
    if(count===undefined){
        count = 0;
    }
    
    $('[data-plus-tel-lista]').show();
    $('[data-plus-tel-lista]').append(form);
    $('[data-plus-tel-lista] [data-plus-tel-item="' + last + '"] [data-excluir-tel]').attr('data-excluir-tel',last);
    $('[data-plus-tel-lista] [data-plus-tel-item="' + last + '"] [data-excluir-tel]').click(function(){
        excluir_telefone(this);//
    });
    $('[data-plus-tel-lista] [data-plus-tel-item="' + last + '"] [name]').each(function(){
        var name = $(this).attr('name');
        if(name==='id_tel'){
            $(this).val(0);
        }
        if(dados!==undefined && dados!==null){
            if(dados[name]==='true' || dados[name]==='false' || dados[name]===true || dados[name]===false){
                $(this).attr('checked',dados[name]);
            }else{
                $(this).val(dados[name]);
            }
        }
        $(this).attr('form',$('[data-plus-tel-form]').attr('data-tel-id-form'));
        $(this).attr('name',name + '[' + last + ']');
    });
    count++;
    $('[data-plus-tel-lista]').attr('data-tel-count',count);
    var input_id = last;
    last++;
    $('[data-plus-tel-form] [data-plus-tel-item]').attr('data-plus-tel-item', last);
    $('[name="ddd['+input_id+']"]').focus();
}