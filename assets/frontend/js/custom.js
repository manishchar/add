jQuery(window).load(function() {
    "use strict";
    // loader fade out
    jQuery(".loader").fadeOut();
    // fade out div that covers website
    jQuery(".load").delay(1000).fadeOut("slow");
})

$(document).ready(function() {

    "use strict";

    // Navigation
    $('body').scrollspy({
        target: '#minify_nav',
        offset: 100
    });

    $('nav a[href^="#"]:not([href="#"]), .scroll').on('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top - 60
        }, 1500);
        event.preventDefault();
    });

    $(window).on("scroll touchmove", function () {
        $('#minify_nav').toggleClass('mini-nav', $(document).scrollTop() > 60);
    });

    // Form Submit Validations
    $.validator.addMethod("valueNotZero", function(value, element, arg){
        return value != 0;
    }, "select an option.");


    var validator =  $(".ajaxform").validate({
        debug: false,
        ignore: [],
          rules: {
                    name: {
                         required: true
                    },
                    number: {
                         required: true,
                         number:true,
                         maxlength:15,
                         minlength:6
                    },
                    email: {
                         required: true,
                         email:true
                    },
                    message: {
                         required: true
                    },
                    color:{
                        valueNotZero: true
                    }
                },
    submitHandler: function(form) {
            // form.submit();
            ajaxsubmit(form);
        }

 });


 function ajaxsubmit (form){
        // event.preventDefault();
        var url = $(form).attr('action');
        var data = $( form ).serialize();
        // alert('data = ' + data);
        $.ajax({
              type: "POST",
              url: url,
              data: data,
              success: success,
              dataType: 'text'
            });
    };

    function success(a){
        // alert(a);
        if(a == 1){
                $('.email-success-text').removeClass('hide');
        }else if(a == 0){
                $('.email-error-text').removeClass('hide');
        }
        var clear = setInterval(function(){
            $('.email-success-text').addClass('hide');
            $('.email-error-text').addClass('hide');
             $('form.ajaxform').trigger("reset");
                clearInterval(clear);
        },4000);
    };

});

// wow animation integration
// ===============================================
var wow = new WOW(
{
    mobile: false
});
wow.init();

// Statistics Counter
// ===============================================
$('.count').counterUp({
    delay: 10,
    time: 1000
});

//  testimonial carousel
// ===============================================
$('.testimonial-carousel').owlCarousel({
    items: 1,
    animateIn: 'slideInRight',
    animateOut: 'fadeOutDown',
    loop: true,
    margin: 10,
    autoplay:true,
});

// tour packages carousel
// ===============================================
$('.tour-packages').owlCarousel({
    margin: 25,
    autoplay: true,
    loop: true,
    autoplayHoverPause: true,
    responsiveClass: true,
    responsive: {
        0: {
            items:1,
            nav: false
        },
        768: {
            items:2,
            nav: false
        },
        1000: {
            items:3,
            nav: false
        }
    }
});

// mobile app screenshot carousel
// ===============================================
$('.app-screenshot').owlCarousel({
    margin: 20,
    autoplay: true,
    loop: true,
    autoplayHoverPause: true,
    responsiveClass: true,
    responsive: {
        0: {
            items:1,
            nav: false
        },
        768: {
            items:2,
             nav: false
        },
        1000: {
            items:4,
            nav: false
        }
    }
});

// countdown timer
// ===============================================
$('#timer').countdown('2017/12/11 00:00:00') /* change here your "countdown to" date */

.on('update.countdown', function(event) {
    var format = '<li><span class="h2 f-w-700 no-m-b">%D</span><h4>Days</h4></li><li><span class="h2 f-w-700 no-m-b">%H</span><h4>Hours</h4></li><li><span class="h2 f-w-700 no-m-b">%M</span><h4>Min</h4></li><li><span class="h2 f-w-700 no-m-b">%S</span><h4>Sec</h4></li>';
    
    
    $(this).html(event.strftime(format));
})
.on('finish.countdown', function(event) {
$(this).html('This offer has expired!')
    .parent().addClass('disabled');
});

// nivo lightbox
// ===============================================
$('#screenshots a').nivoLightbox({
    effect: 'fadeScale',
});

// Mailchimp form integration
// ===============================================
$('.newsletter-signup').ajaxChimp({
    callback: mailchimpCallback,
    url: "https://roziek.us16.list-manage.com/subscribe/post?u=24b48b89dd987bacb21b24469&amp;id=1a02369eae"
});

function mailchimpCallback(resp) {
     if (resp.result === 'success') {
        $('.email-success-text').html(resp.msg).fadeIn(1000);
        $('.email-error-text').fadeOut(1000);
        
    } else if(resp.result === 'error') {
        $('.email-error-text').html(resp.msg).fadeIn(1000);
        $('.email-success-text').fadeOut(1000);
    }
}