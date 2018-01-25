$(document).ready(function(){
  //valida_form({cpf:'CPF já cadastrado!',nome:'Nome obrigatório!'});
  //swal("Good job!", "You clicked the button!", "success");
  $('[data-modern-ajax-submit]').on('click',function(event){
    event.preventDefault();
    var form_id = $(this).attr('data-form-id');
    var form = '#' + form_id;
    if(form_id===undefined || form_id===''){
      form = 'form';
    }

    var action = $(this).attr('formaction');
    if(action===undefined || action===''){
      action = $(form).attr('action');
    }

    $.ajax({
      url: action,
      type: 'POST',
      dataType: 'json',
      data: get_field_values(form)
    }).done(function(data){
      verifica_erros([],form);
      showSaved(data.callout.mensagem);
    }).fail(function(e){
      var data = e.responseJSON;
      verifica_erros(data.fieldserror,form);
      showError('Foram encontrados erros no preenchimento do formulário!');
    });
  });
});

function verifica_erros(fields, for_form){
  if(for_form === undefined){
    for_form = 'form';
  }
  
  $(for_form + ' [data-modern-ajax]').each(function(){
    var input = $(this).attr('data-field-group');
    if(fields !== undefined && fields[input] !== undefined && fields[input] !== ''){
      $(this).find('[data-error]').text(fields[input]);
      $(this).addClass('is-invalid');
    }else{
      $(this).removeClass('is-invalid');
    }
  });
}

function get_field_values(for_form){
  if(for_form === undefined){
    for_form = 'form';
  }
  var field_values = {};
  $(for_form + ' [data-modern-ajax]').each(function(){
    var field = $(this).find('[data-field]');
    if(field[0]!==undefined){
      var field_type = 'input';
      //alert(field.attr('name')+": "+$(this).attr('data-field-type'));
      if($(this).data('field-type')!==undefined){
        field_type = $(this).data('field-type');
      }
      switch(field_type){
        case 'input':{
            field_values[field.attr('name')] = field.val();
            break;
        }case 'checkbox':{
            field_values[field.attr('name')] = $(field).prop('checked')?1:0;
            break;
        }case 'radio':{
            field_values[field.attr('name')] = field.find(':checked').val();
            
        }
      }
    }
  });
  field_values['request'] = 'insert';
  var field_id = $(for_form + ' [data-field-id]');
  if(field_id.val() !== undefined && field_id.val() !== '' && field_id.val() !== '0'){
      field_values[field_id.attr('name')] = field_id.val();
      field_values['request'] = 'update';
  }
  return field_values;
}

function showSaved(text, callback) {
  swal({
    title: "Salvo",
    text: text,
    type: "success",
    showCancelButton: false,
    confirmButtonText: "Fechar",
    closeOnConfirm: false },
    callback);
}

function showError(text, callback) {
  swal({
    title: "Ocorreu um Erro!",
    text: text,
    type: "error",
    showCancelButton: false,
    confirmButtonText: "Fechar",
    closeOnConfirm: true });
}