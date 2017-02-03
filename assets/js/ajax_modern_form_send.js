$(document).ready(function(){
  valida_form({cpf:'CPF já cadastrado!',nome:'Nome obrigatório!'});
  swal("Good job!", "You clicked the button!", "success");
  $('[data-modern-ajax-submit]').on('click',function(event){
    event.preventDefault();
    var form = $(this).attr('id');
  });
});

function valida_form(forms, for_form){
  if(for_form === undefined){
    for_form = 'form';
  }
  $(for_form + ' [data-modern-ajax]').each(function(){
    var input = $(this).attr('data-input-group');
    if(forms[input] !== undefined && forms[input] !== ''){
      $(this).find('[data-error]').text(forms[input]);
      $(this).addClass('is-invalid');
    }else{
      
    }
  });
}

function get_field_values(for_form){
  if(for_form === ''){
    for_form = 'form';
  }
  $(for_form + ' [data-modern-ajax]').each(function(){
    var field_type = 'input';
    if($(this).hasData('field-type')){
      field_type = $(this).data('field-type');
    }
    switch(field_type){
      case 'input':{
          return $('');
      }
    }
  });
}