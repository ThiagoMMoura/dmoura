$(document).ready(function(){
    var fixContentHeight = function(){
        var h = $(window).height();
        var b = $('.screen-fixed-content-top').height() + $('.screen-fixed-content-bottom').height();
        $('.content-block').css('max-height',h-b).css('height',h-b);
    };
    fixContentHeight();
    $(window).on('resize',fixContentHeight);
});

