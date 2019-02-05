/**
* Função para exibir mensagens na tela.
* 
* @param {Object} message
* @param {Function} callback
* @returns {Object}
*/
var showMessage = function(message,callback){
   var type = ['info','success','warning','error'];
   swal({
         title: message.title,
         text: message.message,
         type: type[message.type],
         showCancelButton: false,
         confirmButtonText: "Fechar",
         closeOnConfirm: false },
       callback);
};

var reInitEqualizer = function(element){
   requestAnimationFrame(function(){
       Foundation.reInit($(element));
   });
};

var isEmpty = function(value){
    return value === undefined || value === null || value === '';
};

var completeURLData = function(url_edit,data){
    var url_seg = url_edit.split("/");
    var new_url = '';
    var i;
    console.log(url_edit,data);
    for (i = 0; i < url_seg.length;i++) {
        if (url_seg[i].slice(0,1) === "$") {
            var value = data[url_seg[i].slice(1)]

            if (value) {
                new_url += value;
            }
        } else {
            new_url += url_seg[i];
        }

        if (i < url_seg.length -1) {
            new_url += "/";
        }
    }

    return new_url;
};

app.controller('ctrl_body', function($scope,$location,$filter,$window,$rootScope) {

    $scope.$on('request', function(event,url,data,successCallback,errorCallback){
        event.stopPropagation();
        return $.ajax({
            method: 'POST',
            url: url,
            dataType: 'json',
            data: data,
            context: event.targetScope
        }).then(successCallback, errorCallback);
    });
    
    $scope.goToPage = function (url) {
        if (url.indexOf('http://') === 0 || url.indexOf('https://') === 0) {
            $window.location.href = url;
        } else {
            $window.location.href = $location.url(url).absUrl();
        }
    };
});

app.controller('content_ctrl', function($scope, $timeout){
    $scope.top_bar_buttons = [];
    var show_load_page = true;
    var count_on_loading = 0;

    $scope.showLoadPage = function(){
        return show_load_page;
    };

    $scope.finishLoadPage = function(){
        count_on_loading--;
        count_on_loading < 0 && (count_on_loading = 0);
        show_load_page = count_on_loading !== 0;
    };

    $scope.startLoadPage = function(){
        count_on_loading++;
        show_load_page = true;
    };
    
    $timeout(function(){
        if (show_load_page && count_on_loading === 0){
            console.info('finalizado a força');
            $scope.finishLoadPage();
        }
    },1000);
});

/**
 * Definição do controle da barra de topo principal do sistema.
 */
app.controller('mainTopBar', function($scope,$rootScope){
   /* -- CODE -- */
});

/**
 * Controle para formulários de cadastramento.
 */
app.controller('registration_form_ctrl', function($scope,$filter,$window,$location) {
    var action;
    var initialized = false;
    $scope.default_value = {};
    $scope.list = {};
    $scope.form = {};
    $scope.error = {};
    $scope.current_url = '';
    
    $scope.requestAssociativeOptionsList = function(name,url,assign={}){
        Semaphoro.Up('create-ctrl');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {action:'list'},
            dataType: 'json',
            context: $scope
        }).done(function(data){
            this.list[name] = angular.extend(assign,data.form);
            
            if(Semaphoro.Down('create-ctrl')){
                console.info('Fim do carregamento de listas.');
                
                this.$broadcast('initialize');
                this.$apply();
            }
        }).fail(function(e){
            var data = e.responseJSON;
            Semaphoro.Down('create-ctrl');
            /*this.$apply();*/
        });
    };
    
    $scope.getItem = function(form_data){
        Semaphoro.Up('form_get_item');
        
        var successCallback = function(data){
            this.form = angular.extend(this.form,data.form);
            action = 'update'; console.info('Action to UPDATE');
            this.$broadcast('update_data');
            
            if (Semaphoro.Down('form_get_item')) {
                this.finishLoadPage();
                this.$apply();
            }
        };
        
        var errorCallback = function(e){
            var data = e.responseJSON;
            
            if (Semaphoro.Down('form_get_item')) {
                this.finishLoadPage();
            }
            
            showMessage(data.message);
        };
        
        $scope.$emit('request',$scope.form_action,{action:'get',form:form_data},successCallback, errorCallback);
    };
    
    $scope.setDatalists = function(datalists) {
        Semaphoro.Create('create-ctrl');
        Semaphoro.Up('create-ctrl');
        $scope.startLoadPage();
        
        if (datalists) {
            var i;
            
            for (i in datalists.data) {
                $scope.list[i] = datalists.data[i];
            }
            
            for (i in datalists.urls){
                var list = datalists.urls[i];
                $scope.requestAssociativeOptionsList(list.name,list.url,datalists.data[list.name]);
            }
        }
        
        if (Semaphoro.Down('create-ctrl')) {
            $scope.$broadcast('initialize');
        }
    };
    
    var getDataFormEdit = function(path,url_edit){
        console.log(url_edit,path);
        if ($scope.edit_action) {
            var i;
            var data = {};
            var seg = [];
            var seg_edit = [];
            //var path = angular.copy($location.path());
            //var url_edit = $scope.edit_action;
            
            if (path.slice(-1)==='/') {
                path = path.slice(0,-1);
            }
            seg = path.slice(1).split("/");
            
            if (url_edit.slice(-1)==="/") {
                url_edit = url_edit.slice(-1);
            }
            seg_edit = url_edit.split("/");
            
            if (seg.length === seg_edit.length) {
                for(i in seg_edit){
                    if (seg_edit[i]===seg[i]) {
                        continue;
                    } else if (seg_edit[i].slice(0,1)==="$") {
                        data[seg_edit[i].slice(1)] = seg[i];
                    } else {
                        return null;
                    }
                }
                
                return data;
            }
        }
        
        return null;
    };
    
    $scope.$on('initialize',function(event){
        console.info('initialize form INI');
        if (!initialized) {
            console.info('initilize controller form');
            console.group();
            $scope.formReset();
            console.log($scope.page_title,' | ',$location.host(),' | ',$location.absUrl(),' - Edit: ',$scope.edit_action,' - Path: ',$location.path());
            var data = getDataFormEdit($location.path(),$scope.edit_action);
            console.log('DATA',data);
            if (data) {
                Semaphoro.Create('form_get_item');
                Semaphoro.Up('form_get_item');
                
                $scope.getItem(data);
                
                if (Semaphoro.Down('form_get_item')) {
                    $scope.finishLoadPage();
                }
            } else {
                $scope.finishLoadPage();
            }
            
            initialized = true;
            console.groupEnd();
        }
    });
        
    $scope.formReset = function(){
        console.info('formReset INI');
        action = 'insert';
        $scope.form = angular.copy($scope.default_value);
        $scope.error = {};
    };
    
    var getForm = function(){
        return $scope.form;
    };

    $scope.getFieldData = function(name){
        return $scope.form[name];
    };
    
    $scope.setFieldData = function(name,value){
        return $scope.form[name] = value;
    };
    
    $scope.setDefaultValue = function(name,value){
        return $scope.default_value[name] = value;
    };

    $scope.save = function(bShowMessage = true){
        var successCallback = function(data) {
            action = 'update';
            this.error = {};
            this.form = angular.extend(this.form,data.form);
            $location.url(completeURLData(this.edit_action,data.form)).replace();
            this.$apply();
            reInitEqualizer('#'+this.form_id+' [data-equalizer]');
            console.log(data);
            
            if(bShowMessage){
                showMessage(data.message);
            }
        };
        var errorCallback = function(response) {
            var data = response.responseJSON;
            
            this.error = data.form;
            this.$apply();
            reInitEqualizer('#'+this.form_id+' [data-equalizer]');
            showMessage(data.message);
        };
        $scope.$emit('request',$scope.form_action,{action:action,form:getForm()},successCallback, errorCallback );
    };

    $scope.newForm = function(){
        if ($scope.edit_action) {
            $location.url($scope.edit_action.split('$')[0]);
        }
        $scope.formReset();
        console.info("Novo.");
    };

    $scope.saveAndLoadPage = function(url){
        $scope.startLoadPage();
        var successCallback = function(data) {
            this.loadPage(url);
        };
        
        var errorCallback = function(response) {
            var data = response.responseJSON;
            
            this.error = data.form;
            this.finishLoadPage();
            this.$apply();
            reInitEqualizer('#'+$scope.form_id+' [data-equalizer]');
            showMessage(data.message);
        };
        
        $scope.$emit('request',$scope.form_action,{action:action,form:getForm()},successCallback, errorCallback );
    };

    $scope.loadPage = function(url){
        console.info("Fechado.");
        $window.location.href =  url;
    };

});

app.controller('table_list_ctrl', function($scope,$filter,$window,$rootScope) {
    $scope.table_items = [];
    
    $scope.getList = function(){
        $scope.startLoadPage();
        var successCallback = function(data){
            this.table_items = data.form;
            this.finishLoadPage();
            this.$apply();
        };

        var errorCallback = function(response) {
            var data = response.responseJSON;
            this.finishLoadPage();
            showMessage(data.message);
        };
        
        $scope.$emit('request',$scope.table_url,{action:'query'},successCallback, errorCallback);
    };
    
});

/**
 * Controle para campos de lista seleção.
 */
app.controller('field_select_list_ctrl', function($scope,$filter) {
    
    var getFirst = function(obj){
        var o;
        for(o in obj){
            return o;
        }
    };
    
    $scope.$on('initialize', function(event) {
        if ($scope.default_value === ':first' && angular.isDefined($scope.list[$scope.list_name])) {
            $scope.default_value = getFirst($scope.list[$scope.list_name]);
        }
        
        if ($scope.default_value) {
            $scope.setDefaultValue($scope.field_name,$scope.default_value);
            $scope.field_selection($scope.default_value);
        }
    });

    $scope.field_selection = function(select_value){
        return arguments.length ? $scope.setFieldData($scope.field_name,select_value) : $scope.getFieldData($scope.field_name);
    };

    $scope.field_selection_name = function(value){
        return  ($scope.list[$scope.list_name] && $scope.getFieldData($scope.field_name))
                ? $scope.list[$scope.list_name][$scope.getFieldData($scope.field_name)]
                : null;
    };
});

/**
 * Controle para campos de seleção de Data.
 */
app.controller('field_date_ctrl', function($scope,$filter) {
    var actual_date = null;
    
    $scope.field_value = function(value){
        if (arguments.length) {
            actual_date = value;
            
            $scope.setFieldData($scope.field_name,(value ? $filter('date')(value,'yyyy-MM-dd') : null));
        }
        
        actual_date && actual_date.setTime(Date.parse($scope.getFieldData($scope.field_name)));
        
        return actual_date;
    };
});

/**
 * Controle para campos de lista de multipla seleção.
 */
app.controller('field_mult_select_list_ctrl', function($scope,$filter) {
    var field_data_default = {};
    $scope.field_data = {};
    
    $scope.actionMultSelectConfirm = function(){
        var new_data = [];
        var i;
        
        for(i in $scope.field_data){
            if($scope.field_data[i]){
                new_data.push($scope.field_data[i]);
            }
        }console.log($scope.field_data);
        
        field_data_default = angular.copy($scope.field_data);
        $scope.setFieldData($scope.field_name,new_data);
    };
    
    $scope.actionMultSelectClose = function(){
        $scope.field_data = angular.copy(field_data_default);
    };
    
    $scope.$on('update_data', function () {
        var aux_data = $scope.getFieldData($scope.field_name);
        var i, pos = 0;
        
        field_data_default = {};
        for (i in $scope.list[$scope.field_list_name]) {
            var l;
            
            for(l in aux_data){
                if(aux_data[l] == i){
                    field_data_default[pos] = aux_data[l].toString();
                    break;
                }
            }
            
            pos++;
        }
        
        $scope.field_data = angular.copy(field_data_default);
    });
});

/**
 * Controle da campo de adição de conjuntos de dados.
 */
app.controller('field_dataset_add_ctrl', function($scope,$filter,$window,$rootScope) {
    $scope.default_value = {};
    $scope.field_data_local = {};
    $scope.dataset_index = -1;

    $scope.getFieldData = function(name){
        return $scope.field_data_local[name];
    };

    $scope.setFieldData = function(name,value){
        return $scope.field_data_local[name] = value;
    };
    
    $scope.setDefaultValue = function(name,value){
        return $scope.default_value[name] = value;
    };

    $scope.modalAdd = function(namemodal){
        console.log($scope.field_data_local);
        (!$scope.form[namemodal]) && ($scope.form[namemodal] = []);

        if ($scope.dataset_index >= 0) {
            $scope.form[namemodal][$scope.dataset_index] = angular.copy($scope.field_data_local);
        }else{
            $scope.form[namemodal].push(angular.copy($scope.field_data_local));
        }
        console.log('ModalAdd');
        /*$(document).foundation();*/
    };

    $scope.modalReset = function(namemodal){
        $scope.dataset_index = -1;
        $scope.field_data_local = angular.copy($scope.default_value);
        console.log('ModalReset');
    };

    $scope.printFieldLabel = function(prefix,value,suffix,list = null){
        if (value) {
            if(list){
                value = $scope.list[list][value];
            }
            return prefix + value + suffix;
        }

        return null;
    };

    $scope.modalEdit = function(namemodal,id){
        $scope.dataset_index = id;
        $scope.field_data_local = angular.copy($scope.form[namemodal][id]);
        console.info('ModalEdit');
        console.log(namemodal+' index: '+$scope.dataset_index);
    };

    $scope.modalRemove = function(namemodal,id){
        $scope.form[namemodal].splice(id,1);
        console.info('ModalRemove');
    };
});