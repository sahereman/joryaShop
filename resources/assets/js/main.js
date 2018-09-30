/**
 * 通用模块
 */

var $win = $(window),
    $doc = $(document),
    $body = $('body', $doc),
    winW = $win.width();

$(window).resize(function() {
    winW = $win.width();
})

$(function() {
    if (!$.fn.lazyload) return;
    $("img.lazy", $body).lazyload({
        effect: "fadeIn",
        threshold: 100,
        failure_limit: 0
    });
});

$(function() {
    $(window).on("scroll", function() {
        var t = document.documentElement.scrollTop || document.body.scrollTop;
        if (screen.width > 0) {
            if (t >= 100) { 
                $(".navbar").addClass("fixed-header");
                $(".navbar-top").addClass("fixed-top");
//              $(".navbar-top").slideUp();
            } else {
                $(".navbar").removeClass("fixed-header");
                $(".navbar-top").removeClass("fixed-top");
//              $(".navbar-top").slideDown();
            }
        }
    })
});

// placeholder
$(function() {
    if (!placeholderSupport()) { // 判断浏览器是否支持 placeholder
        $('[placeholder]').focus(function() {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function() {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur();
    };
})

function placeholderSupport() {
    return 'placeholder' in document.createElement('input');
}
