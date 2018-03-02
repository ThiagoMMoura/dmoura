var dmx = {
    /**
     * 
     * @param {String} id
     * @returns {dmx.formulario.forms}
     */
    getFormulario : function(id){
        return this.formulario.forms[id];
    },
    
    getTabela : function(id){
        return this.tabela.tabelas[id];
    },
    
    novoFormulario : function(id,url,identificador,not_permitted,action){
        return this.formulario.novoFormulario(id,url,identificador,not_permitted,action);
    },
    
    novaTabela : function(table){
        return this.tabela.novaTabela(table);
    },
    
    setFormularioCallBack : function(id_form,set_value,set_subfieldvalue,set_action,update_allvalues,show_message){
        this.getFormulario(id_form).setCallback_setValue(set_value)
                .setCallback_setSubFieldValue(set_subfieldvalue)
                .setCallback_setAction(set_action)
                .setCallback_updateAllValues(update_allvalues)
                .setCallback_showMessage(show_message);
    },
    
    setValueOf : function(id_form,id_field,value){
        this.formulario.forms[id_form].fields[id_field].value = value;
    },
    
    setValueOfSub : function(id_form,id_field,id_sub,index,value){
        this.getFormulario(id_form).sub_fields[id_field].fields[id_sub].value[index] = value;
    },
    
    requestGet : function(id_form,id){
        var form = this.getFormulario(id_form);
        form.getIdentifier().value = id;
        return form.request(null,'get');
    },
    
    formulario: {
        novoFormulario : function(id,url,identificador,not_permitted,action){
            this.forms[id] = new this.Modelo_form(id,url,identificador,not_permitted);
            if(!isNothing(action)){
                this.forms[id].setAction(action);
            }
            return this.forms[id];
        },
        
        Modelo_form : function (id,url,identificador,not_permitted){
            /**
             * URL de envio do formulário.
             * 
             * @type String
             */
            this.url = url;
            
            /**
             * Identificador do formulário.
             * 
             * @type String
             */
            this.id = id;
            
            /**
             * Ação do formulário.
             * 
             * @type String
             */
            this.action = 'insert';
            
            /**
             * Armazena a id do campo identificador.
             * 
             * @type String
             */
            this.identifier = identificador;
            
            this.not_permitted = not_permitted;
            
            /**
             * Guarda dados de todos os campos não sub-campos do formulário.
             * 
             * @type object
             */
            this.fields = {};
            
            /**
             * Guarda dados de todos os sub-campos do formulário.
             * 
             * @type object
             */
            this.sub_fields = {};
            
            /**
             * Guarda dados dos botões do formulário.
             * 
             * @type object
             */
            this.buttons = {};
            
            this.callback = {
                setValue : function(id,value,context){return context;},
                setSubFieldValue : function(sub_id,id,value,context){return context;},
                setAction : function(action,context){return context;},
                updateAllValues : function(context){return context;},
                showMessage : function(message,context){return context;},
                setSFFIValue : function(sub_id,id,index,value,context){return context;},
                checkFieldError : function(field_id,context){ return context; },
                checkSubFieldError : function(field_id,context){ return context; }
                //addSubField : function(subfield_id,context){return context;},
                //removeSubField : function(subfield_id,context){return context;}
            };
            
            this.addField = function(field){
                this.fields[field.id] = field;
                return this;
            };
            
            this.addSubField = function(field){
                this.sub_fields[field.id] = field;
                return this;
            };
            
            this.addButton = function(button){
                this.buttons[button.id] = button;
                return this;
            };
            
            this.setCallback_setValue = function(call){
                this.callback.setValue = call;
                return this;
            };
            
            this.setCallback_setSubFieldValue = function(call){
                this.callback.setSubFieldValue = call;
                return this;
            };
            
            this.setCallback_setAction = function(call){
                this.callback.setAction = call;
                return this;
            };
            
            this.setCallback_updateAllValues = function(call){
                this.callback.updateAllValues = call;
                return this;
            };
            
            this.setCallback_showMessage = function(call){
                this.callback.showMessage = call;
                return this;
            };
            
            this.setCallback_setSFFIValue = function(call){
                this.callback.setSFFIValue = call;
                return this;
            };
            
            this.setCallback_checkFieldError = function(call){
                this.callback.checkFieldError = call;
                return this;
            };
            
            this.setCallback_checkSubFieldError = function(call){
                this.callback.checkSubFieldError = call;
                return this;
            };
            /**
             * Altera o valor do sub-campo indicado por parâmetro.
             * 
             * @param {String} sub_field_id
             * @param {String} field_id
             * @param {Integer} index
             * @param {String} value
             * @returns {this}
             */
            this.setSFFIValue = function(sub_field_id,field_id,index,value){
                this.sub_fields[sub_field_id].fields[field_id].value[index] = value;
                return this.callback.setSFFIValue(sub_field_id,field_id,index,value,this);
            };
            
            /**
             * Altera o valor do sub-campo indicado por parâmetro.
             * 
             * @param {string} sub_field_id
             * @param {string} field_id
             * @param {array} value
             * @returns {this}
             */
            this.setSubFieldValue = function(sub_field_id,field_id,value){
                this.sub_fields[sub_field_id].fields[field_id].value = value;
                return this.callback.setSubFieldValue(sub_field_id,field_id,value,this);
            };
            
            /**
             * Altera valor do sub-campo indicado por nome passado pelo parâmetro.
             * 
             * @param {string} field_id
             * @param {string} sub_name
             * @param {string} value
             * @returns {this}
             */
            this.setSubFieldValueByName = function(field_id,sub_name,value){
                return this.setSubFieldValue(field_id,this.getSubIdBySubFieldName(field_id,sub_name),value);
            };
            
            /**
             * Altera o valor do campo indicado por parâmetro.
             * 
             * @param {string} field_id
             * @param {string} value
             * @returns {this}
             */
            this.setValue = function(field_id,value){
                if(!isNothing(field_id)){
                    this.fields[field_id].value = value;
                    this.callback.setValue(field_id,value,this);
                }
                return this;
            };
            
            /**
             * Altera o valor do campo indicado pelo nome passado por parâmetro.
             * 
             * @param {String} field_name
             * @param {String} value
             * @returns {this}
             */
            this.setFieldValueByName = function(field_name,value){
                this.setValue(this.getIdByFieldName(field_name),value);
                return this;
            };
            
            /**
             * Altera o valor de erro do campo indicado por parâmetro.
             * 
             * @param {String} field_id
             * @param {String} error
             * @returns {this}
             */
            this.setError = function(field_id,error){
                if(!isNothing(field_id)){
                    this.fields[field_id].error = error;
                }
                return this;
            };
            
            this.setSubFieldError = function(field_id,error){
                if(!isNothing(field_id)){
                    this.sub_fields[field_id].error = error;
                }
                return this;
            };
            
            /**
             * Altera o valor de erro do campo indicado pelo nome passado por
             * parâmetro.
             * 
             * @param {String} field_name
             * @param {String} error
             * @returns {this}
             */
            this.setErrorByName = function(field_name,error){
                this.setError(this.getIdByFieldName(field_name),error);
                return this;
            };
            
            this.setSubFieldErrorByName = function(field_name,error){
                this.setSubFieldError(this.getIdBySubFieldName(field_name),error);
                return this;
            };
            
            /**
             * Altera a ação do formulário.
             * 
             * @param {string} action
             * @returns {this}
             */
            this.setAction = function(action){
                this.action = action;
                return this.callback.setAction(action,this);
            };
            
            this.isActionAllowed = function(action){
                var ac;
                for(ac in this.not_permitted){
                    if(this.not_permitted[ac] === action){
                        return false;
                    }
                }
                return true;
            };
            
            /**
             * Limpa valores de todos os campos não sub-campos.
             * 
             * @returns {datamaster.formularios.id}
             */
            this.cleanAllFields = function(){
                var f;
                for(f in this.fields){
                    this.cleanField(f);
                }
                return this;
            };
            
            /**
             * Limpa valores de todos os sub-campos.
             * 
             * @returns {dmx.formulario.Modelo_form}
             */
            this.cleanAllSubFields = function(){
                var s;
                for(s in this.sub_fields){
                    var f;
                    for(f in this.sub_fields[s].fields){
                        this.cleanSubField(s,f);
                    }
                }
                return this;
            };
            
            /**
             * Limpa o valor do campo não sub-campo indicado por parâmetro.
             * 
             * @param {string} field_id
             * @returns {datamaster.formularios.id}
             */
            this.cleanField = function(field_id){
                return this.setValue(field_id,this.fields[field_id].default_value);
            };
            
            /**
             * Limpa o valor do sub-campo indicado por parâmetro.
             * 
             * @param {String} sub_field_id
             * @param {String} field_id
             * @returns {this}
             */
            this.cleanSubField = function(sub_field_id,field_id){
                return this.setSubFieldValue(sub_field_id,field_id,[]);
            };
            
            /**
             * Retorna a id do campo com o nome passado por parâmetro.
             * 
             * @param {string} field_name
             * @returns {datamaster.formularios.id.fields}
             */
            this.getIdByFieldName = function(field_name){
                var id = undefined;
                var i;
                for(i in this.fields){
                    if(this.fields[i].name === field_name) {
                        id = i;
                        break;
                    }
                }
                return id;
            };
            
            /**
             * Retorna a id de um sub-campo com o nome passado por parâmetro.
             * 
             * @param {string} field_name
             * @returns {datamaster.formularios.id.sub_fields|datamaster.formularios.id.fields}
             */
            this.getIdBySubFieldName = function(field_name){
                var id = undefined;
                var i;
                for(i in this.sub_fields){
                    if(this.sub_fields[i].name === field_name){
                        id = i;
                        break;
                    }
                }
                return id;
            };
            
            /**
             * Retorna a id de um campo de um sub-campo com o nome passado por 
             * parâmetro.
             * 
             * @param {string} sub_id
             * @param {string} field_name
             * @returns {datamaster.formularios.id.sub_fields|datamaster.formularios.id@arr;sub_fields@arr;fields}
             */
            this.getSubIdBySubFieldName = function(sub_id,field_name){
                var id = undefined;
                var i;
                for(i in this.sub_fields[sub_id].fields){
                    if(this.sub_fields[sub_id].fields[i].name === field_name){
                        id = i;
                        break;
                    }
                }
                return id;
            };
            
            /**
             * Retorna o valor do campo ou sub_campo indicado por parâmetro.
             * 
             * @param {type} id_field
             * @param {type} is_sub
             * @returns {string;object}
             */
            this.getValue = function(id_field,is_sub=false){
                if(is_sub){
                    var retorno = {};
                    var f;
                    for(f in this.sub_fields[id_field].fields){
                        var field = this.sub_fields[id_field].fields[f];
                        retorno[field.name] = field.value;
                    }
                    return retorno;
                }else{
                    return this.fields[id_field].value;
                }
            };
            
            /**
             * Retorna um objeto com todos os valores de campos do formulário.
             * 
             * @returns {object}
             */
            this.getValues = function(){
                var retorno = {};
                var f;
                for(f in this.fields){
                    retorno[this.fields[f].name] = this.getValue(f);
                }
                for(f in this.sub_fields){
                    retorno[this.sub_fields[f].name] = this.getValue(f,true);
                }
                return retorno;
            };
            
            this.getSubFieldDefaultValue = function(id_sub,id_field){
                return this.sub_fields[id_sub].fields[id_field].default_value;
            };
            
            /**
             * Verifica erros no campo indicado por parâmetro.
             * 
             * @param {String} id_field
             * @returns {this}
             */
            this.checkFieldError = function(id_field){
                return this.callback.checkFieldError(id_field,this);
            };
            
            /**
             * Verifica erros no sub-campo indicado por parâmetro.
             * @param {string} id_field
             * @returns {this}
             */
            this.checkSubFieldError = function(id_field){
                return this.callback.checkSubFieldError(id_field,this);
            };
            
            /**
             * Verifica erros de todos os campos e sub-campos do formulário.
             * 
             * @returns {this}
             */
            this.checkErrors = function(){
                var f;
                for(f in this.fields){
                    this.checkFieldError(f);
                }
                for(f in this.sub_fields){
                    this.checkSubFieldError(f);
                }
                return this;
            };
            
            this.cleanAllErrors = function(){
                var f;
                for(f in this.fields){
                    this.getField(f).error = '';
                }
                for(f in this.sub_fields){
                    this.getSubFields(f).error = '';
                }
                return this;
            };
            
            this.request = function(id_button,action=null){
                var data_values = {};
                var success;
                var error;
                data_values.form = {};
                
                if(isNothing(action)){
                    data_values.action = this.action;
                }else{
                    data_values.action = action;
                }
                
                if(this.isActionAllowed(data_values.action)){
                    switch(data_values.action){
                        case 'delete':
                            data_values.form[this.getIdentifier().name] = this.getValue(this.identifier);
                            success = function(data){
                                this.callback.showMessage(data.message,this);
                            };
                            error = function(e){
                                var data = e.responseJSON;
                                this.callback.showMessage(data.message,this);
                            };;
                            break;
                        case 'get':
                            data_values.form[this.getIdentifier().name] = this.getValue(this.identifier);
                            success = function(data){
                                this.cleanAllErrors();
                                this.checkErrors();
                                var f;
//                                for(f in data.form){
//                                    if(typeof data.form[f] === 'object'){
//                                        var s;
//                                        for(s in data.form[f]){
//                                            this.setSubFieldValueByName(this.getIdBySubFieldName(f),s,data.form[f][s]);
//                                        }
//                                    }else{
//                                        this.setFieldValueByName(f,data.form[f]);
//                                    }
//                                }
                                for(f in this.fields){
                                    var name = this.fields[f].name; 
                                    if(data.form[name]){
                                        this.setValue(f,data.form[name]);
                                    }
                                }
                                for(f in this.sub_fields){
                                    var name = this.sub_fields[f].name; 
                                    if(data.form[name]){
                                        var s;
                                        for(s in this.sub_fields[f].fields){
                                            var subname = this.sub_fields[f].fields[s].name;
                                            if(data.form[name][subname]){
                                                this.setSubFieldValue(f,s,data.form[name][subname]);
                                            }
                                        }
                                    }
                                }
                                
                                this.setAction('update');
                            };
                            error = function(e){
                                var data = e.responseJSON;
                                this.setAction('insert');
                                this.callback.showMessage(data.message,this);
                            };
                            break;
                        case 'update':
                        case 'insert':
                            var button = this.buttons[id_button];
                            //Atualiza valores de todos os campos
                            this.callback.updateAllValues(this);
                            //Armazena valores para envio
                            data_values.form = this.getValues();
                            //Determina a função em caso de sucesso
                            success = function(data){
                                if(button.type==='submeter'){
                                    this.cleanAllErrors();
                                    this.checkErrors();
                                    var f;
                                    for(f in data.form){
                                        if(typeof data.form[f] === 'object'){
                                            var s;
                                            for(s in data.form[f]){
                                                this.setSubFieldValueByName(this.getIdBySubFieldName(f),s,data.form[f][s]);
                                            }
                                        }else{
                                            this.setFieldValueByName(f,data.form[f]);
                                        }
                                    }
                                    this.setAction('update');
                                    this.callback.showMessage(data.message,this);
                                }else if(button.type==='submeterfechar'){
                                    window.location.assign(button.url);
                                }else if(button.type==='submeternovo'){
                                    this.checkErrors();
                                    this.callback.showMessage(data.message,this);
                                }
                            };
                            //Define a função em caso de erro.
                            error = function(e){
                                var data = e.responseJSON;
                                var f;
                                for(f in data.form){
                                    if(f.includes('[')){
                                        this.setSubFieldErrorByName(f.split('[')[0],data.form[f]);
                                    }else{
                                        this.setErrorByName(f,data.form[f]);
                                    }
                                }
                                this.checkErrors();
                                this.callback.showMessage(data.message,this);
                            };
                            break; 
                    }
                    return $.ajax({
                            url: this.url,
                            type: 'POST',
                            dataType: 'json',
                            data: data_values,
                            context: this
                        }).done(success).fail(error);
                }else{
                    this.callback.showMessage({
                        'title':'Erro de permisão',
                        'message':'Está ação não é permitida!',
                        'type':3
                    },this);
                    return false;
                }
            };
            
            /**
             * Retorna o campo identificador.
             * 
             * @returns {Field}
             */
            this.getIdentifier = function(){
                return this.fields[this.identifier];
            };
            
            /**
             * Retorna o campo com a id passada por parâmetro.
             * 
             * @param {String} id
             * @returns {Field}
             */
            this.getField = function(id){
                return this.fields[id];
            };
            
            this.getSubFields = function(id){
                return this.sub_fields[id];
            };
            
            this.getSubFieldsField = function(id,sub){
                return this.sub_fields[id].fields[sub];
            };
            
            /**
             * Retorna o erro do campo indicado por parâmetro.
             * 
             * @param {string} id
             * @returns {error message}
             */
            this.getError = function(id){
                return this.fields[id].error;
            };
            
            /**
             * Retorna o erro do sub-campo indicado por parâmetro.
             * 
             * @param {string} id
             * @returns {error message}
             */
            this.getSubFieldsError = function(id){
                return this.sub_fields[id].error;
            };
            
            /**
             * Retorna TRUE se o campo contiver erro.
             * 
             * @param {String} id
             * @returns {Boolean}
             */
            this.hasError = function(id){
                return this.getError(id) !== '';
            };
        },
        /**
         * @type {dmx.formulario.Modelo_form}
         */
        forms : {},
        
        requestList : function(url,dbfield,success,error){
            var data_form = {};
            data_form['action'] = 'list';
            data_form['form'] = dbfield;
            return $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: data_form
                }).done(function(data){
                    success(data.form);
                }).fail(function(e){
                    var data = e.responseJSON;
                    error(data);
                });
        }
    },
    buttonAction : function(id_form,id_button){
        var form = this.getFormulario(id_form);
        var button = form.buttons[id_button];
        switch(button.type){
            case 'submeterfechar':
            case 'submeternovo':
            case 'submeter':
                form.request(id_button);
                break;
            case 'fechar':
            case 'direcionar':
                window.location.assign(button.url);
                break;
            case 'resetar':
            case 'novo':
                form.cleanAllFields()
                        .cleanAllSubFields()
                        .setAction('insert');
                break;
        }
    },
    /**
     * Objeto de controle de tabelas.
     */
    tabela : {
        /**
         * @type {object}
         */
        tabelas : {},
        /**
         * Cria uma nova tabela com o objeto passado por parâmetro.
         * 
         * @param {object} table
         * @returns {dmx.tabela.tabelas}
         */
        novaTabela : function(table){
            this.tabelas[table.id] = new this.tabela_modelo(table);
            return this.tabelas[table.id];
        },
        /**
         * Construtor de objeto Tabela.
         * 
         * @param {object} table
         * @returns {dmx.tabela.tabela_modelo}
         */
        tabela_modelo : function(table){
            /**
             * @type {String}
             */
            this.id = table.atributos.id;
            /**
             * @type {String}
             */
            this.name = table.atributos.name;
            /**
             * @type {String}
             */
            this.identifier = table.identifier;
            /**
             * @type {String}
             */
            this.model = table.atributos.model;
            /**
             * @type {String}
             */
            this.join = table.atributos.join;
            /**
             * @type {String}
             */
            this.url = table.atributos.url;
            /**
             * @type {String}
             */
            this.selector = table.atributos.selector;
            /**
             * @type {String}
             */
            this.order = table.atributos.order || false;
            /**
             * @type {String}
             */
            this.sortcolindex = table.atributos.sortcol;
            /**
             * @type {String}
             */
            this.orderby = null;
            /**
             * @type {String}
             */
            this.like = null;
            /**
             * @type {String}
             */
            this.where = null;
            /**
             * @type {Tag}
             */
            this.search = table.tags.search ? new Tag(table.tags.search): null;
            /**
             * @type {Tag}
             */
            this.head = new Tag(table.tags.head);
            /**
             * @type {Tag}
             */
            this.body = new Tag(table.tags.body);
            /**
             * @type {Tag}
             */
            this.buttongroup = new Tag(table.tags.buttongroup);
            /**
             * @type {String}
             */
            this.title = table.tags.title;
            
            this.records = 0;
            
            this.callback = {
                showMessage : function(message,context){ return context; },
                newRow : function(row,context){ return context; },
                getSelectedItems : function(context){ return context; },
                cleanTable : function(context){ return context; }
            };
            
            /**
             * Retorna array do select da Tabela.
             * 
             * @returns {Array}
             */
            this.getSelect = function(){
                var select = [];
                var c;
                var i = 0;
                for(c in this.head.tag('hcol')){
                    var hcol = this.head.tag('hcol')[c];
                    if(!isNothing(hcol.tag('select'))){
                        select[i] = hcol.tag('select');
                        i++;
                    }
                }
                return select;
            };
            
            /**
             * Retorna a Tag do botão especificado por parâmetro.
             * 
             * @param {String} id
             * @returns {Tag|undefined}
             */
            this.getButton = function(id){
                var i;
                for(i in this.buttongroup.tag('button')){
                    if(this.buttongroup.tag('button')[i].attr('id') === id){
                        return this.buttongroup.tag('button')[i];
                    }
                }
                return undefined;
            };
            
            /**
             * Retorna a HCol do indice passado por parâmetro.
             * 
             * @param {Integer} index
             * @returns {Tag}
             */
            this.getHCol = function(index){
                return this.head.tag('hcol')[index];
            };
            
            /**
             * Retorna a HCol com a id passada por parâmetro.
             * 
             * @param {String} id
             * @returns {Tag}
             */
            this.getHColById = function(id){
                return this.getHCol(this.getHColIndexById(id));
            };
            
            /**
             * Retorna o indice da HCol com a id passada por parâmetro.
             * 
             * @param {String} id
             * @returns {dmx.tabela.tabela_modelo@arr;head@call;tag}
             */
            this.getHColIndexById = function(id){
                var i;
                for(i in this.head.tag('hcol')){
                    if(this.getHCol(i).attr('id') === id){
                        return i;
                    }
                }
            };
            
            this.requestQuery = function(){
                var data_values = {};
                var success;
                var error;
                data_values.action = 'query';
                data_values.form = {};
                data_values.form.model = this.model;
                data_values.form.join = this.join;
                data_values.form.select = this.getSelect();
                if(isNothing(this.orderby) && !isNothing(this.sortcolindex)){
                    this.sortCol(this.sortcolindex,'ASC');
                }
                data_values.form.orderby = this.orderby;
                
                if(!isNothing(this.like)){
                    data_values.form.like = this.like;
                }
                
                if(!isNothing(this.where)){
                    data_values.form.where = this.where;
                }
                success = function(data){
                    var i;
                    this.callback.cleanTable(this);
                    for(i in data.form){
                        var row = data.form[i];
                        this.callback.newRow(row,this);
                    }
                    this.records = i;
                };
                error = function(e){
                    var data = e.responseJSON;
                    this.records = 0;
                    this.callback.showMessage(data.message,this);
                };
                return $.ajax({
                        url: this.url,
                        type: 'POST',
                        dataType: 'json',
                        data: data_values,
                        context: this
                    }).done(success).fail(error);
            };
            
            this.request = function(id_button){
                var button = this.getButton(id_button);
                var data_values = {};
                var success;
                var error;
                data_values.form = {};
                data_values.form[this.selector] = this.callback.getSelectedItems(this);
                switch(button.attr('type')){
                    case 'excluir':
                        data_values.action = 'delet';
                        data_values.form.model = this.model;
                        success = function(data){
                            this.requestQuery();
                            this.callback.showMessage(data.message,this);
                        };
                        error = function(e){
                            var data = e.responseJSON;
                            this.callback.showMessage(data.message,this);
                        };
                        break;
                    case 'favoritar':
                        data_values.action = 'favorite';
                        success = function(data){
                            this.callback.showMessage(data.message,this);
                        };
                        error = function(e){
                            var data = e.responseJSON;
                            this.callback.showMessage(data.message,this);
                        };
                }
                return $.ajax({
                        url: this.url,
                        type: 'POST',
                        dataType: 'json',
                        data: data_values,
                        context: this
                    }).done(success).fail(error);
            };
            
            /**
             * Altera a propriedade de ordenação da tabela.
             * 
             * @param {String} order
             * @returns {dmx.tabela.tabela_modelo}
             */
            this.setOrderBy = function(order){
                this.orderby = order;
                return this;
            };
            
            /**
             * Ordenada a tabela pela id da coluna indicada por parâmetro.
             * 
             * @param {String} id_col
             * @param {String} direction
             * @returns {dmx.tabela.tabela_modelo}
             */
            this.sortColById = function(id_col,direction){
                var index = this.getHColIndexById(id_col);
                return this.sortCol(index,direction);
            };
            
            /**
             * Ordena a tabela pelo indice da coluna indicado por parâmetro.
             * 
             * @param {Integer} index
             * @param {String} direction
             * @returns {dmx.tabela.tabela_modelo}
             */
            this.sortCol = function(index,direction){
                var order = this.getHCol(index).attr('orderby');
                var opposed = {'ASC':'DESC','DESC':'ASC'};
                order = replaceAll(order,'{$direction$}',direction);
                order = replaceAll(order,'{$opposed$}',opposed[direction]);
                return this.setOrderBy(order);
            };
            
            this.setCallback_showMessage = function(call){
                this.callback.showMessage = call;
                return this;
            };
            
            this.setCallback_newRow = function(call){
                this.callback.newRow = call;
                return this;
            };
            
            this.setCallback_getSelectedItems = function(call){
                this.callback.getSelectedItems = call;
                return this;
            };
            
            this.setCallback_cleanTable = function(call){
                this.callback.cleanTable = call;
                return this;
            };
        },
        
        buttonAction : function(id_table,id_button){
            var table = this.tabelas[id_table];
            var button = table.getButton(id_button);
            switch(button.attr('type')){
                case 'favoritar':
                case 'excluir':
                    table.request(id_button);
                    break;
                case 'editar':
                case 'novo':
                case 'direcionar':
                    window.location.assign(button.tag('url'));
                    break;
            }
        },
        
        /**
         * Ordena a tabela e colunas idicas pelas IDs.
         * 
         * @param {String} id_table
         * @param {String} id_col
         * @param {String} direction
         * @returns {$;false}
         */
        sort : function(id_table,id_col,direction){
            return this.tabelas[id_table].sortColById(id_col,direction).requestQuery();
        }
    }
};
function Button(button){
    this.id = button.id;
    this.type = button.type;
    this.url = button.url;
}
function Field(field){
    this.id=field.id;
    this.name=field.name;
    this.type=field.type;
    this.error=field.error;
    this.value=field.value;
    this.default_value=field.value;
}
function SubField(field){
    this.id=field.id;
    this.name=field.name;
    this.error=field.error;
    this.fields= arrayFields(field.subfields);
}
function arrayFields(fields){
    var f;
    var a = {};
    for(f in fields){
        a[f] = new Field(fields[f]);
        a[f].value = undefined;
    }
    return a;
}

function Tag(tag){
    this.atributos = tag.atributos;
    this.tags = newsTags(tag.tags);
    this.tagName = tag.tagName;
    this.attr = function(name){
        return this.atributos[name];
    };
    this.tag = function(name){
        return this.tags[name];
    };
}

function newsTags(tags){
    var retorno = {};
    var t;
    for(t in tags){
        if(tags[t].tagName !== undefined){
            retorno[t] = new Tag(tags[t]);
        }else if (typeof tags[t] === 'string'){
            retorno[t] = tags[t];
        }else{
            retorno[t] = newsTags(tags[t]);
        }
    }
    return retorno;
}