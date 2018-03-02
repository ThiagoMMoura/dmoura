var addSubField = function(form_id,field_id){
    var count = $('#' + field_id).attr('data-subfield-count');
    if(count === '0'){
        $('#' + field_id + ' [data-subfield="0"]').show();
        cleanSubField(form_id,field_id,0);
        count++;
    }else{
        var clone = $('#' + field_id + ' [data-subfield="0"]').clone();
        clone.attr('data-subfield',count);
        clone.find('[data-subfield-remove]').attr('data-subfield-remove',count);
        clone.find('[data-subfield-index]').each(function(){
            $(this).attr('data-subfield-index',count);
        });
        $('#' + field_id).append(clone);
        cleanSubField(form_id,field_id,count);
        count++;
    }
    $('#' + field_id).attr('data-subfield-count',count);
};

var removeSubField = function(form_id,field_id,index){
    var count = 0;
    if($('#' + field_id).attr('data-subfield-count') === '1'){
        $('#' + field_id + ' [data-subfield="0"]').hide();
        cleanSubField(form_id,field_id,0);
    }else{
        $('#' + field_id + ' [data-subfield="' + index + '"]').remove();
        $('#' + field_id + ' [data-subfield]').each(function(){
            $(this).attr('data-subfield',count).find('[data-subfield-remove]').attr('data-subfield-remove',count);
            $(this).find('[data-subfield-index]').each(function(){
                $(this).attr('data-subfield-index',count);
            });
            count++;
        });
    }
    $('#' + field_id).attr('data-subfield-count',count);
};

var updateSubField = function(form){
    var s;
    for(s in form.sub_fields){
        var f;
        for(f in form.sub_fields[s].fields){
            var field = form.sub_fields[s].fields[f];
            field.value = [];
            if($('#' + s).attr('data-subfield-count')>0){
                $('#' + s + ' #'+f).each(function(index){
                    field.value[index] = valor(s + ' [data-subfield="'+index+'"] #' +f,field.type);
                });
            }
            console.log(field.value);
        }
    }
};

var cleanSubField = function(form_id,field_id,index){
    var form = dmx.getFormulario(form_id);
    $('#' + field_id + ' [data-subfield="'+index+'"] [data-input]').each(function(){
        var id = $(this).attr('id');
        valor(field_id + ' [data-subfield="' + index + '"] #' +id,form.getSubFieldsField(field_id,id).type,form.getSubFieldDefaultValue(field_id,id));
    });
};

/**
 * 
 * @param {String} id_form
 * @param {String} id_sub
 * @param {String} id
 * @returns {undefined}
 * @deprecated a função dmx.setValueOfSub substitui essa função.
 */
var setValueOfSubField = function(id_form,id_sub,id){
    var values = [];
    $('#' + id_sub + ' [data-input="'+id+'"]').each(function(index){
        values[index] = $(this).val();
    });
    dmx.setValueOfSub(id_form,id_sub,id,values);
};

/**
 * Função callback do formulário para alterar o valor dos campos.
 * 
 * @param {String} id
 * @param {String} value
 * @param {Object} form
 * @returns {Object}
 */
var setValue = function(id,value,form){
    var field = form.getField(id);
    valor(id,field.type,value);
    return form;
};

var valor = function(id,type,value){
    console.log('ID: '+id+' TYPE: '+type+' VALUE: '+value);
    switch(type){
        case 'identificador':
        case 'texto':
        case 'numero':
        case 'real':
        case 'senha':
        case 'data':
        case 'alfanumerico':
            if(value===undefined){
                return $('#'+id).val();
            }
            $('#'+id).val(value);
            break;
        case 'boleano':
            if(value===undefined){
                return $('#'+id).prop('checked')?1:0;
            }
            $('#'+id).prop('checked',value);
            break;
        case 'lista-boleana':
            if(value===undefined){
                var lista = [];
                var i = 0;
                $('#'+id+'-lista [data-input]').each(function(){
                    if($(this).prop('checked')){
                        lista[i] = $(this).attr('name');
                        i++;
                    }
                });
                return lista;
            }
            var i;
            $('#'+id+'-lista [data-input]').removeProp('checked');
            //$('#'+id+'-lista').attr('data-setbollist',JSON.stringify(value));
            for(i in value){
                $('#'+id+'-lista [data-input][name="'+value[i]+'"]').prop('checked',true);
            }
            break;
        case 'lista':
            if(value===undefined){
                return $('#'+id).find('option:contains("'+$('#'+id).val()+'")').attr('id');
            }
            $('#' + id).attr('data-setlist',value);
            $('#' + id).find('[id="' + value + '"]').prop('selected',true);
            break;
    }
};

var setSubFieldValue = function(sub_id,id,value,form){
    var type = form.getSubFieldsField(sub_id,id).type;
    var count = $('#' + sub_id).attr('data-subfield-count');
    if(value.length>count){
        while(value.length>count){
            addSubField(form.id,sub_id);
            count++;
        }
    }else if(value.length<count){
        while(value.length<count){
            removeSubField(form.id,sub_id,count);
            count--;
        }
    }
    var i;
    for(i=0;i<count;i++){
        valor(sub_id + ' [data-subfield="' + i + '"] #' + id,type,value[i]);
    }
    return form;
};

/**
 * Função que busca um lista de opções e monta o select.
 * 
 * @param {String} url
 * @param {String} dbfield
 * @param {String} selected
 * @param {Object} context
 * @param {function} callback_semaforo 
 * @returns {Object}
 */
var getList = function(url,dbfield,selected,context,callback_semaforo){
    Semaphoro.Up('request-list');
    dmx.formulario.requestList(url,dbfield,function(form){
        var i;
        for(i in form){
            if(selected===i){
                $(context).append('<option id="' + i + '" selected>' + form[i] + "</option>");
            }else{
                $(context).append('<option id="' + i + '">' + form[i] + "</option>");
            }
        }
        Semaphoro.Down('request-list',callback_semaforo);
        //$(context).find('[id="' + $(context).attr('data-setlist') + '"]').prop('selected',true);
    },function(form){Semaphoro.Down('request-list',callback_semaforo);});
};

var getBolList = function(url,dbfield,selected,context,callback_semaforo){
    Semaphoro.Up('request-list');
    dmx.formulario.requestList(url,dbfield,function(form){
        var i;
        var id = $(context).find('li[data-bollistmodel] [data-input]').attr('id').replace('modelo-','');
        for(i in form){
            var copy = $(context).find('li[data-bollistmodel]').clone();
            $(copy).removeClass('hide').removeAttr('data-bollistmodel').find('[data-input]').attr('id',id+'-'+i).attr('name',i)
                    .attr('onchange',replaceAll($(copy).find('[data-input]').attr('onchange'),'modelo-'+id,id));
            $(copy).find('label[for="modelo-'+id+'"]').attr('for',id+'-'+i);
            $(copy).html(replaceAll($(copy).html(),'{$legend$}',form[i]));
            $(context).append(copy);
        }
        Semaphoro.Down('request-list',callback_semaforo);
//        var data = $(context).data('setbollist');
//        for(i in data){
//            $(context).find('li [data-input][name="'+data[i]+'"]').prop('checked',true);
//        }
    },function(form){Semaphoro.Down('request-list',callback_semaforo);});
};

/**
 * Função para exibir mensagens na tela. Utilizada no callback de formulários.
 * 
 * @param {Object} message
 * @param {Object} form
 * @param {Function} callback
 * @returns {Object}
 */
var showMessage = function(message,form,callback){
    var type = ['info','success','warning','error'];
    swal({
          title: message.title,
          text: message.message,
          type: type[message.type],
          showCancelButton: false,
          confirmButtonText: "Fechar",
          closeOnConfirm: false },
        callback);
    return form;
};

var setAction = function(action,form){
    var i;
    for(i in form.buttons){
        var button = form.buttons[i];
        switch(button.type){
            case 'submeterfechar':
            case 'submeternovo':
            case 'submeter':
                if(action === 'update' || action === 'insert'){
                    if(!form.isActionAllowed(action)){
                        $('#' + button.id).attr('disabled','disabled');
                        $('[for="' + button.id + '"],label:has(#' + button.id + ')').hide();
                    }else{
                        $('#' + button.id).removeAttr('disabled');
                        $('[for="' + button.id + '"],label:has(#' + button.id + ')').show();
                    }
                }
                break;
            case 'fechar':
            case 'direcionar':
                break;
            case 'novo':
                if(action === 'insert'){
                    $('#' + button.id).attr('disabled','disabled');
                    $('[for="' + button.id + '"],label:has(#' + button.id + ')').hide();
                }else{
                    $('#' + button.id).removeAttr('disabled');
                    $('[for="' + button.id + '"],label:has(#' + button.id + ')').show();
                }
                break;
        }
    }
    
    return form;
};

var checkFieldError = function(field_id,form){
    var error = form.getError(field_id);
    if(!isNothing(error)){
        $('#'+field_id + ' ~ .form-error').html(error).addClass('is-visible');
        $('#'+field_id).addClass('is-invalid-input');
        $('label[for="'+field_id + '"], label:has(#'+field_id+')').addClass('is-invalid-label');
    }else{
        $('#'+field_id + ' ~ .form-error').html(error).removeClass('is-visible');
        $('#'+field_id).removeClass('is-invalid-input');
        $('label[for="'+field_id + '"], label:has(#'+field_id+')').removeClass('is-invalid-label');
    }
    return form;
};

var checkSubFieldError = function(field_id,form){
    var error = form.getSubFieldsError(field_id);
    if(!isNothing(error)){
        $('#'+field_id + ' > .form-error').html(error).addClass('is-visible');
    }else{
        $('#'+field_id + ' > .form-error').html(error).removeClass('is-visible');
    }
    return form;
};

var initForm = function(sv_form,id){
    var esseForm = dmx.novoFormulario(sv_form.id,sv_form.action,sv_form['field-identifier'],sv_form['not-permitted'],'insert');
    esseForm.setCallback_setValue(setValue);
    esseForm.setCallback_setSubFieldValue(setSubFieldValue);
    esseForm.setCallback_showMessage(showMessage);
    esseForm.setCallback_setAction(setAction);
    esseForm.setCallback_checkFieldError(checkFieldError);
    esseForm.setCallback_checkSubFieldError(checkSubFieldError);
    //esseForm.setCallback_updateAllValues(updateSubField);
    var sec;
    for(sec in sv_form.sections){
        var f;
        for(f in sv_form.sections[sec].fields){
            var field = sv_form.sections[sec].fields[f];
            if(field.type === 'subfield'){
                esseForm.addSubField(new SubField(field));
            }else{
                esseForm.addField(new Field(field));
            }
        }
    }
    var b;
    var executaRequestGet = function(){
        if(!isNothing(id)){
            dmx.requestGet(sv_form.id,id);
        }else{
            esseForm.setAction('insert');
        }
        desativaLoadingContent();
    };
    for(b in sv_form.buttongroup){
        var button = sv_form.buttongroup[b];
        esseForm.addButton(new Button(button));
    }
    var countrequest = 0;
    //Populando listas.
    $('[data-getlist]').each(function(){
        var data = $(this).data('getlist');
        countrequest++;
        getList(data['list-url'],data['list-dbfield'],data.selected,this,executaRequestGet);
    });
    $('[data-subfield="0"]').hide();
    //Populando listas-boleanas
    $('[data-getbollist]').each(function(){
        var data = $(this).data('getbollist');
        countrequest++;
        getBolList(data['list-url'],data['list-dbfield'],data.selected,this,executaRequestGet);
    });
    if(countrequest===0){
        executaRequestGet();
    }
};


