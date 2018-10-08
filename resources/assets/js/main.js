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
    // 获取导航栏到屏幕顶部的距离
    var oTop = $(".navbar-bottom").offset().top;
    //获取导航栏的高度，此高度用于保证内容的平滑过渡
    var martop = $('.navbar-bottom').outerHeight();
 
    var sTop = 0;
    // 监听页面的滚动
    $(window).scroll(function () {
        // 获取页面向上滚动的距离
        sTop = $(this).scrollTop();
        // 当导航栏到达屏幕顶端
        if (sTop >= oTop) {
            // 修改导航栏position属性，使之固定在屏幕顶端
            $(".navbar-bottom").addClass("fixed-header");
            // 修改内容的margin-top值，保证平滑过渡
        } else {
            // 当导航栏脱离屏幕顶端时，回复原来的属性
            $(".navbar-bottom").removeClass("fixed-header");
        }
    });
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


//返回顶部
$(function(){
	$('.online .note ul li').hover(function() {
        $(this).find('a').stop(true, true).fadeIn();
    }, function() {
        $(this).find('a').stop(true, true).fadeOut();
    });
    $(".show_customer").hover(function(){
    	$(".customer_info").stop(true, true).fadeIn();
    }, function() {
        $(".customer_info").stop(true, true).fadeOut();
    })
    $('#backtop,.backtop').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 400);
    });
})
