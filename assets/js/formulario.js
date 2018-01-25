/**
 * Descrição do objeto Formulário.
 * 
 * @author Thiago Moura
 * @param {object} obj_formulario 
 * @param {string} class_error 
 * @version 0.9.0
 */
function Formulario(obj_formulario,class_error){
    var formu = obj_formulario;
    var class_error = class_error;
    var field_identifier = formu['field-identifier'];

    var idFromName = function(name){
        var sec;
        for(sec in formu.sections){
            if(formu.sections[sec].fields[name] !== undefined){
                return formu.sections[sec].fields[name].id;
            }
        }
    };
    var isActionAllowed = function(action){
        var ac;
        for(ac in formu['not-permitted']){
            if(formu['not-permitted'][ac] === action){
                return false;
            }
        }
        return true;
    };
    
    var request = function(url,values){
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: values,
            context: this
        }).done(function(data){
            if(buttontype==='submeter'){
                this.verificaErros([]);
                var f;
                for(f in data['form']){
                    if(Array.isArray(data['form'][f])){

                    }else{
                        $('[data-input="' + idFromName(f) + '"]').val(data['form'][f]);
                    }
                }
                this.disableAction('update');
                this.showSaved('Sucesso');
            }else if(buttontype==='submeterfechar'){
                window.location.assign(buttonurl);
            }else if(buttontype==='submeternovo'){
                this.verificaErros([]);
                this.showSaved(data.message.message);
            }
        }).fail(function(e){
            var data = e.responseJSON;
            this.verificaErros(data.form);
            this.showError('Foram encontrados erros no preenchimento do formulário!');
        });
    };
    this.sendForm = function(buttontype,buttonurl){
        var action = formu.action;
        var values = this.getValues();
        if(isActionAllowed(values['action'])){
            $.ajax({
                url: action,
                type: 'POST',
                dataType: 'json',
                data: values,
                context: this
            }).done(function(data){
                if(buttontype==='submeter'){
                    this.verificaErros([]);
                    var f;
                    for(f in data['form']){
                        if(Array.isArray(data['form'][f])){

                        }else{
                            $('[data-input="' + idFromName(f) + '"]').val(data['form'][f]);
                        }
                    }
                    this.disableAction('update');
                    this.showSaved('Sucesso');
                }else if(buttontype==='submeterfechar'){
                    window.location.assign(buttonurl);
                }else if(buttontype==='submeternovo'){
                    this.verificaErros([]);
                    this.showSaved(data.message.message);
                }
            }).fail(function(e){
                var data = e.responseJSON;
                this.verificaErros(data.form);
                this.showError('Foram encontrados erros no preenchimento do formulário!');
            });
        }else{
            this.showError('Ação não é permitida!');
        }
    };
    this.verificaErros = function(fields_error){
        var for_form = '#' + formu.id;
        var sec;
        for(sec in formu.sections){
            var name;
            for(name in formu.sections[sec].fields){
                var field = formu.sections[sec].fields[name];
                if(fields_error !== undefined && fields_error[name] !== undefined && fields_error[name] !== ''){
                  $(for_form).find('[data-error="' + field.id + '"]').text(fields_error[name]);
                  $(for_form + ' #' + field.id).addClass(class_error);
                }else{
                  $(for_form + ' #' + field.id).removeClass(class_error);
                }
            }
        }
    };
    this.getValues = function (){
        var field_values = {};
        var sec;
        field_values['form'] = {};
        field_values['action'] = 'insert';
        for(sec in formu.sections){
            var section = formu.sections[sec];
            var name;
            for(name in section.fields){
                var field = section.fields[name];

                if(field.type === 'registro'){
                    var field_reg = $('[data-input="' + field.id + '"]');
                    if(field_reg.val() !== undefined && field_reg.val() !== '' && field_reg.val() !== '0'){
                        field_values['action'] = 'update';
                    }
                }
                field_values['form'][name] = valueOfField(field);
            }
        }
        console.log(field_values);
        return field_values;
    };
    var valueOfField = function(field){
        var field_value = null;
        switch(field.type){
            case 'registro':
            case 'lista':
            case 'texto':
            case 'numero':
            case 'real':
            case 'senha':
            case 'data':
            case 'alfanumerico':{
                field_value = $('[data-input="' + field.id + '"]').val();
                break;
            }case 'boleano':{
                field_value= $('[data-input="' + field.id + '"]').prop('checked')?1:0;
                break;
            }case 'lista-boleana':{
                field_value = $('[data-input="' + field.id + '"]').find(':checked').val();
                break;
            }
            case 'nfield':{
                var nf;
                var nfield_value = [];
                for(nf in field.nfields){
                    var nfield = field.nfields[nf];
                    var i = 0;
                    nfield_value[nfield.name] = [];

                    $('#' + field.id + ' [data-input="' + nfield.id + '"]').each(function(){
                        nfield_value[nfield.name][i] = $(this).val();
                        i++;
                    });
                }
                field_value = nfield_value;
                break;
            }
        }
        return field_value;
    };
    var setValueOfField = function(field,value){
        switch(field.type){
            case 'registro':
            case 'lista':
            case 'texto':
            case 'numero':
            case 'real':
            case 'senha':
            case 'data':
            case 'alfanumerico':{
                $('[data-input="' + field.id + '"]').val(value);
                break;
            }case 'boleano':{
                $('[data-input="' + field.id + '"]').prop('checked',value);
                break;
            }case 'lista-boleana':{
                $('[data-input="' + field.id + '"]').find('[value="'+value+'"]').attr('checked',true);
                break;
            }
            case 'nfield':{
                var nf;
                for(nf in field.nfields){
                    var nfield = field.nfields[nf];
                    var i = 0;

                    $('#' + field.id + ' [data-input="' + nfield.id + '"]').each(function(){
                        setValueOfField(nfield,value[nf][i]);
                        i++;
                    });
                }
                break;
            }
        }
    };
    this.showSaved = function(text, callback) {
        swal({
          title: "Salvo",
          text: text,
          type: "success",
          showCancelButton: false,
          confirmButtonText: "Fechar",
          closeOnConfirm: false },
        callback);
    };

    this.showError = function(text, callback) {
        swal({
          title: "Ocorreu um Erro!",
          text: text,
          type: "error",
          showCancelButton: false,
          confirmButtonText: "Fechar",
          closeOnConfirm: true },
        callback);
    };
    
    this.requestList = function(field){
        var data_form = {};
        data_form['action'] = 'list';
        data_form['form'] = field.options['list-dbfield'];
        $.ajax({
            url: field.options['list-url'],
            type: 'POST',
            dataType: 'json',
            data: data_form
        }).done(function(data){
            formulario[formu.id].setList(field.id,data.form,field.options.selected);
        }).fail(function(e){
            var data = e.responseJSON;
            formulario[formu.id].showError(data.message.message);
        });
    };
    
    this.requestGet = function(id){
        var data_form = {};
        data_form['action'] = 'get';
        data_form['form']= {};
        data_form['form'][field_identifier] = id;

        $.ajax({
            url: formu.action,
            type: 'POST',
            dataType: 'json',
            data: data_form
        }).done(function(data){
            var s;
            for(s in formu.sections){
                var f;
                for(f in formu.sections[s].fields){
                    setValueOfField(formu.sections[s].fields[f],data.form[f]);
                }
            }
            formulario[formu.id].disableAction('update');
        }).fail(function(e){
            var data = e.responseJSON;
            formulario[formu.id].showError(data.message.message);
        });
    };
    
    this.setList = function(list_id,list_data,selected){
        var i;
        for(i in list_data){
            if(selected===i){
                $('[data-input="' + list_id + '"]').append('<option id="' + i + '" selected>' + list_data[i] + "</option>");
            }else{
                $('[data-input="' + list_id + '"]').append('<option id="' + i + '">' + list_data[i] + "</option>");
            }
        }
    };
    
    this.addNfield = function(field){
        $('#' + field.id).attr('data-nfield-count',0);
        $('#' + field.id + ' [data-nfield="0"]').hide();
        $('#' + field.id + '-add').on('click',function(event){
            event.preventDefault();
            var count = $('#' + field.id).attr('data-nfield-count');
            if(count === '0'){
                $('#' + field.id + ' [data-nfield="0"]').show();
                formulario[formu.id].cleanNfield(field,0);
                count++;
            }else{
                var clone = $('#' + field.id + ' [data-nfield="0"]').clone();
                clone.attr('data-nfield',count);
                clone.find('[data-nfield-remove]').attr('data-nfield-remove',count);
                $('#' + field.id).append(clone);
                formulario[formu.id].cleanNfield(field,count);
                count++;
            }
            $('#' + field.id).attr('data-nfield-count',count);
            formulario[formu.id].removeNfield(field);
        });
    };
    
    this.removeNfield = function(field){
        $('#' + field.id + ' [data-nfield-remove]').off('click');
        $('#' + field.id + ' [data-nfield-remove]').one('click',function(event){
            event.preventDefault();
            var count = 0;
            if($('#' + field.id).attr('data-nfield-count') === '1'){
                $('#' + field.id + ' [data-nfield="0"]').hide();
            }else{
                var index = $(this).attr('data-nfield-remove');
                $('#' + field.id + ' [data-nfield="' + index + '"]').remove();
                $('#' + field.id + ' [data-nfield]').each(function(){
                    $(this).attr('data-nfield',count).find('[data-nfield-remove]').attr('data-nfield-remove',count);
                    count++;
                });
            }
            $('#' + field.id).attr('data-nfield-count',count);
        });
    };
    
    this.cleanNfield = function(field,index){
        /*setValueOfField(field,value)
        */$('#' + field.id + ' [data-nfield="' + index + '"] input').each(function(){
            $(this).val('');
        });
        $('#' + field.id + ' [data-nfield="' + index + '"] select').each(function(){
            $(this).val($(this).find(':selected').val());
        });
    };
    
    this.disableAction = function(action){
        if(!isActionAllowed(action)){
            var i;
            for(i in formu.buttongroup){
                var button = formu.buttongroup[i];
                switch(button.type){
                    case 'submeterfechar':
                    case 'submeternovo':
                    case 'submeter':{
                        if(action === 'update' || action === 'insert'){
                            $('#' + button.id).attr('disabled','disabled');
                            $('[for="' + button.id + '"],label:has(#' + button.id + ')').hide();
                        }
                        break;
                    }
                    case 'fechar':
                    case 'direcionar':{
                        break;
                    }
                }
            }
        }
    };
    
    this.cleanAllFields = function(){
        var s;
        for(s in formu.sections){
            var f;
            for(f in formu.sections[s].fields){
                var field = formu.sections[s].fields[f];
                setValueOfField(field,field.value);
            }
        }
    };
}

var formulario = [];

function novoFormulario(formu,class_error,id){
    formulario[formu.id] = new Formulario(formu,class_error);
    if(id !== undefined){
        formulario[formu.id].requestGet(id);
    }
    var i;
    for(i in formu.buttongroup){
        var button = formu.buttongroup[i];
        switch(button.type){
            case 'submeterfechar':
            case 'submeternovo':
            case 'submeter':{
                $('#' + button.id).on('click',button,function(event){
                    event.preventDefault();
                    var b = event.data;
                    formulario[formu.id].sendForm(b.type,b.url);
                });
                break;
            }
            case 'fechar':
            case 'direcionar':{
                $('#' + button.id).on('click',button,function(event){
                    event.preventDefault();
                    var b = event.data;
                    window.location.assign(b.url);
                });
                break;
            }
        }
    }
    for(i in formu.sections){
        var f;
        for(f in formu.sections[i].fields){
            var field = formu.sections[i].fields[f];
            if(field.type === 'lista'){
                $('[data-input="' + field.id + '"]').val('Carregando...');
                formulario[formu.id].requestList(field);
                $('[data-input="' + field.id + '"]').val('');
            }else if(field.type === 'nfield'){
                var nf;
                for(nf in field.nfields){
                    var nfield = field.nfields[nf];
                    if(nfield.type === 'lista'){
                        $('#' + field.id + ' [data-input="' + nfield.id + '"]').val('Carregando...');
                        formulario[formu.id].requestList(nfield);
                        $('#' + field.id + ' [data-input="' + nfield.id + '"]').val('');
                    }
                }
                formulario[formu.id].addNfield(field);
                //formulario[formu.id].removeNfield(field);
            }
        }
    }
}
//TESTES
var datamaster = {
    formularios: {
        tipo1: {
            'id' : {
                callback:{
                    limparcampo : function(id){console.log(this.addSubField(id));},
                    addSubField : function(subfield_id){return this.context;},
                    removeSubField : function(subfield_id){},
                    context : this
                },
                fields: {
                    'id' : { type:'', name:'', erro:'', value:'' }
                },
                buttons: {
                    'id' : { type:'', url:'' }
                },
                url:'',
                context:this,
                teste: function(){
                    this.callback.limparcampo(1);
                }
            }
        }
    }
};
console.log(datamaster);
//datamaster.formularios.tipo1.id.teste();