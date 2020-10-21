$(function (){
    $('.goto-top').click(function (){
        $('html,body').stop().animate({
            scrollTop: 0
        });
    });
    $(window).scroll(function (){
        if (Math.max($('body').scrollTop(), $('html').scrollTop()) > 50) {
            $('.goto-top').fadeIn(300);
        }else{
            $('.goto-top').fadeOut(300);
        }
    });
});