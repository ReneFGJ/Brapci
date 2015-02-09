$(window).scroll(function() {
    if ($(this).scrollTop() > 1){  
        $('header').addClass("sticky");
        $('#logo_top').removeClass("logo");
        $('#logo_top').addClass("logo_scrool");
    }
    else{
        $('header').removeClass("sticky");
        $('#logo_top').addClass("logo");
        $('#logo_top').removeClass("logo_scrool");
    }
});

$('body').addClass("margin120");