var dmx = {
    /**
     * 
     * @param {String} id
     * @returns {dmx.formulario.forms}
     */
    getFormulario : function(id){
        return this.formulario.forms[id];
    },
    
    novoFormulario : function(id,url,identificador,not_permitted,action){
        return this.formulario.novoFormulario(id,url,identificador,not_permitted,action);
    },
    
    setCallsBackToFormTemplate : function(set_value,set_subfieldvalue,set_action,update_allvalues,show_message){
        this.formulario.Modelo_form.callback= {
                setValue : set_value,
                setSubFieldValue : set_subfieldvalue,
                setAction : set_action,
                updateAllValues : update_allvalues,
                showMessage : show_message
        };
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
        form.request(null,'get');
        return form;
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
                this.fields[field_id].value = value;
                return this.callback.setValue(field_id,value,this);
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
                this.fields[field_id].error = error;
                return this;
            };
            
            this.setSubFieldError = function(field_id,error){
                this.sub_fields[field_id].error = error;
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
                var id;
                for(id in this.fields){
                    if(this.fields[id].name === field_name) break;
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
                var id;
                for(id in this.sub_fields){
                    if(this.sub_fields[id].name === field_name) break;
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
                var id;
                for(id in this.sub_fields[sub_id].fields){
                    if(this.sub_fields[sub_id].fields[id].name === field_name) break;
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
                                this.callback.showMessage(data.message);
                            };
                            error = function(e){
                                var data = e.responseJSON;
                                this.callback.showMessage(data.message);
                            };;
                            break;
                        case 'get':
                            data_values.form[this.getIdentifier().name] = this.getValue(this.identifier);
                            success = function(data){
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
                            };
                            error = function(e){
                                var data = e.responseJSON;
                                this.callback.showMessage(data.message);
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
                                    this.callback.showMessage(data.message);
                                }else if(button.type==='submeterfechar'){
                                    window.location.assign(button.url);
                                }else if(button.type==='submeternovo'){
                                    this.checkErrors();
                                    this.callback.showMessage(data.message);
                                }
                            };
                            //Define a função em caso de erro.
                            error = function(e){
                                var data = e.responseJSON;
                                var f;
                                for(f in data.form){
                                    if(Array.isArray(data.form[f])){
                                        this.setSubFieldErrorByName(f,data.form[f]);
                                    }else{
                                        this.setErrorByName(f,data.form[f]);
                                    }
                                }
                                this.checkErrors();
                                this.callback.showMessage(data.message);
                            };
                            break; 
                    }
                    $.ajax({
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
                    });
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
            $.ajax({
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
        a[f].value = [a[f].value];
    }
    return a;
}


//dmx.novoFormulario('teste2');console.log(dmx.getFormulario('teste2').callback.setValue('','','contexto'));
//console.log(dmx.formulario.Modelo_form.prototype);
//console.log(new dmx.formulario.Modelo_form());