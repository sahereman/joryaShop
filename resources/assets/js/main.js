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

// $(function() {
//     // 获取导航栏到屏幕顶部的距离
//     var oTop = $(".navbar-bottom").offset().top;
//     //获取导航栏的高度，此高度用于保证内容的平滑过渡
//     var martop = $('.navbar-bottom').outerHeight();
//
//     var sTop = 0;
//     // 监听页面的滚动
//     $(window).scroll(function () {
//         // 获取页面向上滚动的距离
//         sTop = $(this).scrollTop();
//         // 当导航栏到达屏幕顶端
//         if (sTop >= oTop) {
//             // 修改导航栏position属性，使之固定在屏幕顶端
//             $(".navbar-bottom").addClass("fixed-header");
//             // 修改内容的margin-top值，保证平滑过渡
//         } else {
//             // 当导航栏脱离屏幕顶端时，回复原来的属性
//             $(".navbar-bottom").removeClass("fixed-header");
//         }
//     });
// });

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
    // $(".show_qr").hover(function(){
    // 	$(".qr_info").stop(true, true).fadeIn();
    // }, function() {
    //     $(".qr_info").stop(true, true).fadeOut();
    // })
    $('#backtop,.backtop').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 400);
    });
})

//登陆注册弹窗
$(function(){
	$(".rotary_btn").on("click",function(){
		var show_code=$(this).attr("code");//		show_code表示显示的内容，0表示登录显示，1表示注册显示
		if(show_code==0){
			$(".register_form").hide();
			$(".login_form").show();
			$(".login_frame").css("transform","rotateY(180deg)");    //弹窗翻转
			$(".login_form").css("transform","rotateY(180deg)"); 
			$(".dialog_logo").css("transform","rotateY(180deg)"); 
			$(".close").css("transform","rotateY(180deg)");
			$(".close").css("right","93%");
		}else {
			$(".register_form").show();
			$(".login_form").hide();
			$(".login_frame").css("transform","rotateY(360deg)");    //弹窗翻转
			$(".register_form").css("transform","rotateY(360deg)");
			$(".dialog_logo").css("transform","rotateY(360deg)"); 
			$(".close").css("transform","rotateY(360deg)"); 
			$(".close").css("right","0");
		}
	})
	//弹窗关闭
	$(".close").on("click",function(){
		$(".dialog_iframe").hide();
		$(".login_frame").css("transform","rotateY(0deg)");    //弹窗翻转
		$(".register_form").css("transform","rotateY(0deg)");
	    $(".dialog_logo").css("transform","rotateY(0deg)"); 
	    $(".close").css("transform","rotateY(0deg)"); 
	    $(".close").css("right","0");
	})
	// //登陆注册按钮点击事件
	// $(".login").on("click",function(){
	// 	$(".login_frame").css("transform","rotateY(180deg)");    //弹窗翻转
	// 	$(".login_form").css("transform","rotateY(180deg)");
	// 	$(".dialog_logo").css("transform","rotateY(180deg)");
	// 	$(".close").css("transform","rotateY(180deg)");
	// 	$(".close").css("right","93%");
	// 	$(".dialog_iframe").show();
	// 	$(".register_form").hide();
	// 	$(".login_form").show();
	// })
	// $(".register").on("click",function(){
	// 	$(".dialog_iframe").show();
	// 	$(".register_form").show();
	// 	$(".login_form").hide();
	// })
	
	// //切换登录方式
	// $(".common_login").on("click",function(){
	// 	$(".login_type ul li").removeClass('active');
	// 	$(this).addClass("active");
	// 	$(".login_form form").removeClass("active");
	// 	$("#login-form").addClass("active");
	// 	$(".login_form .btn_dialog").removeClass("active");
	// 	$(".commo_btn").addClass("active");
	// })
	// $(".mailbox_login").on("click",function(){
	// 	$(".login_type ul li").removeClass('active');
	// 	$(this).addClass("active");
	// 	$(".login_form form").removeClass("active");
	// 	$("#mailbox_login").addClass("active");
	// 	$(".login_form .btn_dialog").removeClass("active");
	// 	$(".mailbox_btn").addClass("active");
	// })
	
	//获取验证码倒计时
	$(".generate_code").click(function(){      
		var disabled = $(".generate_code").attr("disabled");      
		if(disabled){        
			return false;      
		}      
//		if($("#mobile").val() == "" || isNaN($("#mobile").val()) || $("#mobile").val().length != 11 ){        
//			alert("请填写正确的邮箱！");        
//			return false;      
//		}      
//		$.ajax({        
//			async:false,        
//			type: "GET",        
//			url: "{:U('User/sms')}",        
//			data: {mobile:$("#mobile").val()},        
//			dataType: "json",        
//			async:false,        
//			success:function(data){          
//				console.log(data);          
				settime();        
//			},        
//			error:function(err){          
//				console.log(err);        
//			}      
//		});    
	});    
	var countdown=60;    
	var _generate_code = $(".generate_code");    
	function settime() {      
		if (countdown == 0) {        
			_generate_code.attr("disabled",false);
			$(".generate_code").css({backgroundColor:"#7ca442",color:"#fff",cursor:"pointer"});
			_generate_code.val("获取验证码");        
			countdown = 60;        
			return false;      
		} else {        
			$(".generate_code").attr("disabled", true);
			$(".generate_code").css({backgroundColor:"#f5f7f4",color:"#d1d3cf",cursor:"not-allowed"});
			_generate_code.val("" + countdown + "s");        
			countdown--;      
		}      
		setTimeout(function() {        
			settime();      
		},1000);    
	}
})
