function isNothing(value){
    return (value===undefined || value===null || value==='');
}
$(document).ready(function(){
    var fixContentHeight = function(){
        var h = $(window).height();
        var b = $('.screen-fixed-content-top').height() + $('.screen-fixed-content-bottom').height();
        $('.content-block').css('max-height',h-b).css('height',h-b);
    };
    fixContentHeight();
    $(window).on('resize',fixContentHeight);
    
    //Script de formulários
    $('.modern-input-group > input.input-group-field').each(function(index){
      if($(this).attr('placeholder') === '' || $(this).attr('placeholder') === undefined){
        $(this).attr('placeholder',$(this).parent().find('.input-group-label').text());
      }
    });
    $('.modern-input-group > .input-group-label, .modern-input-group > .error-msg').on('click',function(){
      $(this).parent().find('.input-group-field').focus();
    });
    $('.modern-input-group > .input-group-field').on('focus',function(){
      $(this).parent().addClass('on-focus');
    });
    $('.modern-input-group > .input-group-field').on('blur',function(){
      $(this).parent().removeClass('on-focus');
    });
});

