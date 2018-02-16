/**
 * Função para exibir mensagens na tela. Utilizada no callback de formulários.
 * 
 * @param {Object} message
 * @param {Object} table
 * @param {Function} callback
 * @returns {Object}
 */
var showMessage = function(message,table,callback){
    var type = ['info','success','warning','error'];
    swal({
          title: message.title,
          text: message.message,
          type: type[message.type],
          showCancelButton: false,
          confirmButtonText: "Fechar",
          closeOnConfirm: false },
        callback);
    return table;
};

var newRow = function(row,table){
    var c;
    var clone = $('[data-row="model"]').clone();
    var count = $('#'+table.id).attr('data-row-count') || 0;
    $(clone).attr('data-row',count);
    for(c in row){
        $(clone).html(replaceAll($(clone).html(),'{$'+c+'$}',row[c]));
    }
    for(c in table.body.tag('bcol')){
        var bcol = table.body.tag('bcol')[c];
        var hcol = table.head.tag('hcol')[c];
        if(bcol.attr('type')==='texto'){
            $(clone).find('#'+hcol.attr('id')).html(formatType(hcol.attr('type'),row[bcol.tag('content')]));
        }else if(bcol.attr('type')==='link'){
            $(clone).find('#'+hcol.attr('id')+' a').html(formatType(hcol.attr('type'),row[bcol.tag('content')]));
        }
    }
    $('[data-row="model"]').before(clone);
    count++;
    $('#'+table.id).attr('data-row-count',count);
    return table;
};

var formatType = function(type,value){
    switch(type){
        case 'data':
            value = value.replace(/([0-9]{4})-([0-9]{2})-([0-9]{2}$)/g,"$3/$2/$1");
            break;
        case 'cpf_cnpj':
            if(value.length===11){
                value = value.replace(/([0-9]{3})([0-9]{3})([0-9]{3})([0-9]{2}$)/g,"$1.$2.$3-$4");
            }else{
                value = value.replace(/([0-9]{2})([0-9]{3})([0-9]{3})([0-9]{4})([0-9]{2}$)/g,"$1.$2.$3/$4-$5");
            }
            break;
        case 'cep':
            value = value.replace(/([0-9]{5})([0-9]{3}$)/g,"$1-$2");
            break;
        case 'real':
            value = value.replace(/([0-9]+)\.([0-9]+$)/g,"$1,$2");
            break;
    }
    return value;
};

var cleanTable = function(table){
    $('#'+table.id+' tbody tr[data-row!="model"]').remove();
    return table;
};

var getSelectedItems = function(table){
    var retorno = [];
    $('#' + table.id + ' [name="selector"]:checked').each(function(index){
        retorno[index] = $(this).val();
    });
    return retorno;
};

var resizeTable = function(id_table){
    $('#'+id_table+' thead th').each(function(){
        var size = $(this).attr('data-size');
        
        if(size === 'exato'){
            var ico = $(this).find('i').outerWidth();
            ico = ico>0?ico+6:0;
            size = ($(this).outerWidth() + ico) + 'px';
            $(this).css({'min-width':size,'width':size});
        }else{
            size = (size) + '%';
            $(this).css({'max-width':size});
        }
    });
};

var sortToggle = function(id_table, col){
    var direction = $(col).attr('data-sort-direction');
    $('#'+id_table+' thead th[data-sort-direction] i').each(function(){
        $(this).removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
    });
    if(direction === 'ASC'){
        $(col).find('i').removeClass('fa-sort').addClass('fa-sort-down');
        $(col).attr('data-sort-direction','DESC');
    }else{
        $(col).find('i').removeClass('fa-sort').addClass('fa-sort-up');
        $(col).attr('data-sort-direction','ASC');
    }
};

var search = function(id_table){
    var tabela = dmx.getTabela(id_table);
    var searchfieldid = tabela.search.tag('searchfield').attr('id');
    var dbfield = $('#'+searchfieldid).find('option:contains("'+$('#'+searchfieldid).val()+'")').attr('id');
    var searchtextid = tabela.search.tag('searchtext').attr('id');
    var value = $('#'+searchtextid).val();
    setLike(id_table,dbfield,value);
};

var setLike = function(id_table,field,value){
    var tabela = dmx.getTabela(id_table);
    tabela.like = {};
    tabela.like[field] = value;
};

var initTable = function(sv_table){
    var tbl = dmx.novaTabela(sv_table);
    tbl.setCallback_showMessage(showMessage)
            .setCallback_newRow(newRow)
            .setCallback_getSelectedItems(getSelectedItems)
            .setCallback_cleanTable(cleanTable);
    resizeTable(tbl.id);
    tbl.requestQuery();
};