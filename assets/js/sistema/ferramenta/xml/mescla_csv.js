$(document).ready(function(){
    var $form = $('#form-upload');
    
    $('#enviar').hide();
    
    $form.find('#uploadcsv').on('change', function (event) {
        event.preventDefault();
        var arquivos = event.target.files;
        $.each(arquivos, function(index, arquivo) {
            var $modelo = $('.modelo-item-upload li').clone();
            var formdata = new FormData();
            formdata.append('uploadcsv', event.target.files[index]);
            
            $.ajax({
                url: $form.attr('action'),
                cache: false,
                contentType: false,
                processData: false,
                data: formdata,
                dataType: 'json',
                type: 'post',
                beforeSend: function() {
                    $modelo.find('.titulo-js').text(arquivo.name);
                    $('.lista-upload-xml').prepend($modelo);
                },
                success: function(file) {
                    $modelo.find('.enviando-js').addClass('hide');
                    $modelo.find('.sucesso-js').removeClass('hide');
                    $modelo.find('.download-js a').attr('href',file.url);
                    $modelo.find('.download-js').removeClass('hide');
                    $modelo.removeClass('primary').addClass('success');
                    $modelo.find('.close-button').removeClass('hide');
                },
                error: function (data) {
                    var msg = data.responseJSON;
                    $modelo.find('.enviando-js').addClass('hide');
                    $modelo.find('.erro-js').removeClass('hide').find('.msg-js').text(msg.error);
                    $modelo.removeClass('primary').addClass('alert');
                    $modelo.find('.close-button').removeClass('hide');
                }
            });
        });
    });
});


