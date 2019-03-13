// require('./bootstrap');

require('./bootstrap');

// window.Vue = require('vue');
// require('./components/SelectDistrict');
// require('./components/UserAddressesCreateAndEdit');
require('./components/jquery.lazyload/jquery.lazyload.min');
require('./jquery.validate.min');
// require('./autocompleter/jquery.autocomplete');

// const app = new Vue({
//   el: '#app'
// });

/**
 * 通用模块
 */

var $win = $(window),
    $doc = $(document),
    $body = $('body', $doc),
    winW = $win.width();
var enter_event = "default";
var COUNTRY = $("#dLabel").find("span").html();
$(window).resize(function () {
    winW = $win.width();
});

$(function () {
    /*货币汇率转换相关*/
    var app_node = $('div#app');

    if ((typeof global_locale === 'undefined') || (typeof global_locale !== 'string')) {
        var global_locale = String(app_node.attr('data-global-locale'));
    }

    if ((typeof global_currency === 'undefined') || (typeof global_currency !== 'string')) {
        var global_currency = String(app_node.attr('data-global-currency'));
    }

    if ((typeof global_symbol === 'undefined') || (typeof global_symbol !== 'string')) {
        var global_symbol = String(app_node.attr('data-global-symbol'));
    }

    /*if ((typeof currencies === 'undefined') || (typeof currencies !== 'object')) {
     var currencies = JSON.parse(app_node.attr('data-currencies'));
     }*/

    if ((typeof symbols === 'undefined') || (typeof symbols !== 'object')) {
        var symbols = JSON.parse(app_node.attr('data-symbols'));
    }

    if ((typeof exchange_rates === 'undefined') || (typeof exchange_rates !== 'object')) {
        var exchange_rates = JSON.parse(app_node.attr('data-exchange-rates'));
    }

    if ((typeof float_multiply_by_100 === 'undefined') || (typeof float_multiply_by_100 !== 'function')) {
        function float_multiply_by_100(float) {
            float = String(float);
            // float = float.toString();
            var index_of_dec_point = float.indexOf('.');
            if (index_of_dec_point == -1) {
                float += '00';
            } else {
                var float_splitted = float.split('.');
                var dec_length = float_splitted[1].length;
                if (dec_length == 1) {
                    float_splitted[1] += '0';
                } else if (dec_length > 2) {
                    float_splitted[1] = float_splitted[1].substring(0, 1);
                }
                float = float_splitted.join('');
            }
            return Number(float);
        }
    }

    if ((typeof js_number_format === 'undefined') || (typeof js_number_format !== 'function')) {
        function js_number_format(number) {
            number = String(number);
            // number = number.toString();
            var index_of_dec_point = number.indexOf('.');
            if (index_of_dec_point == -1) {
                number += '.00';
            } else {
                var number_splitted = number.split('.');
                var dec_length = number_splitted[1].length;
                if (dec_length == 1) {
                    number += '0';
                } else if (dec_length > 2) {
                    number_splitted[1] = number_splitted[1].substring(0, 2);
                    number = number_splitted.join('.');
                }
            }
            return number;
        }
    }

    if ((typeof exchange_price === 'undefined') || (typeof exchange_price !== 'function')) {
        function exchange_price(price, to_currency, from_currency) {
            if (to_currency && to_currency !== 'USD' && exchange_rates[to_currency]) {
                var to_rate = exchange_rates[to_currency].rate;
                price = float_multiply_by_100(price);
                to_rate = float_multiply_by_100(to_rate);
                price = js_number_format(Math.imul(price, to_rate) / 10000);
            }
            if (from_currency && from_currency !== 'USD' && exchange_rates[from_currency]) {
                var from_rate = exchange_rates[from_currency].rate;
                price = float_multiply_by_100(price);
                from_rate = float_multiply_by_100(from_rate);
                price = js_number_format(price / from_rate);
            }
            return price;
            // 以下方法实现js的number_format功能虽然简单，但是存在数字四舍五入不准确的问题，结果不可预知：
            // (Math.ceil(number) / 100).toFixed(2)
            // js_number_format(Math.ceil(number) / 100)
        }
    }

    if ((typeof get_current_price === 'undefined') || (typeof get_current_price !== 'function')) {
        function get_current_price(price_in_usd) {
            return exchange_price(price_in_usd, global_currency);
        }
    }

    if ((typeof get_symbol_by_currency === 'undefined') || (typeof get_symbol_by_currency !== 'function')) {
        function get_symbol_by_currency(currency) {
            if (currency && currency !== 'USD' && symbols[currency]) {
                return symbols[currency];
            }
            return '&#36;';
        }
    }
});

$(function () {
    if (!$.fn.lazyload) return;
    $("img.lazy", $body).lazyload({
        effect: "fadeIn",
        threshold: 100,
        failure_limit: 0
    });
});

$(function () {
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
    $(window).on("scroll", function () {
        var t = document.documentElement.scrollTop || document.body.scrollTop;
        if (screen.width > 0) {
            if (t >= 400) {
                $(".backtop").css("display", "block");
            } else {
                $(".backtop").css("display", "none");
            }
        }
    });
});

// placeholder
$(function () {
    if (!placeholderSupport()) { // 判断浏览器是否支持 placeholder
        $('[placeholder]').focus(function () {
            var input = $(this);
            if (input.val() == input.attr('placeholder')) {
                input.val('');
                input.removeClass('placeholder');
            }
        }).blur(function () {
            var input = $(this);
            if (input.val() == '' || input.val() == input.attr('placeholder')) {
                input.addClass('placeholder');
                input.val(input.attr('placeholder'));
            }
        }).blur();
    }
});

function placeholderSupport() {
    return 'placeholder' in document.createElement('input');
}

// 返回顶部
$(function () {
    $('.online .note ul li').hover(function () {
        $(this).find('a').stop(true, true).fadeIn();
    }, function () {
        $(this).find('a').stop(true, true).fadeOut();
    });
    $(".show_customer").hover(function () {
        $(".customer_info").stop(true, true).fadeIn();
    }, function () {
        $(".customer_info").stop(true, true).fadeOut();
    });
    $(".show_qr").hover(function () {
        $(".qr_info").stop(true, true).fadeIn();
    }, function () {
        $(".qr_info").stop(true, true).fadeOut();
    });
    $('#backtop,.backtop').click(function () {
        $("html, body").animate({
            scrollTop: 0,
        }, 400);
    });
    $(".show_fenxaing").hover(function () {
        $(".fenxiang_info").stop(true, true).fadeIn();
    }, function () {
        $(".fenxiang_info").stop(true, true).fadeOut();
    });
    // 配置网站分享

});

// 登陆注册弹窗
$(function () {
    $(".rotary_btn").on("click", function () {
        var show_code = $(this).attr("code"); // show_code表示显示的内容，0表示登录显示，1表示注册显示
        if (show_code == 0) {
            $(".register_form").addClass("dis_n");
            $(".login_frame").removeClass("dialog_close_active"); // 弹窗翻转
            $(".register_form").removeClass("dialog_close_active");
            $(".dialog_logo").removeClass("dialog_close_active");
            $(".close").removeClass("dialog_close_active");
            $(".login_frame").removeClass('register_active');
            $(".login_form").removeClass('register_active');
            $(".dialog_logo").removeClass('register_active');
            $(".close").removeClass('register_active');
            $(".login_frame").addClass('login_active');
            $(".login_form").addClass('login_active');
            $(".dialog_logo").addClass('login_active');
            $(".close").addClass('login_active');
            $(".login_form").removeClass("dis_n");
            $(".common_login").click();
        } else {
            enter_event = "register"
            $(".login_form").addClass("dis_n");
            $(".login_frame").removeClass("dialog_close_active"); // 弹窗翻转
            $(".register_form").removeClass("dialog_close_active");
            $(".dialog_logo").removeClass("dialog_close_active");
            $(".close").removeClass("dialog_close_active");
            $(".login_frame").removeClass('login_active');
            $(".register_form").removeClass('login_active');
            $(".dialog_logo").removeClass('login_active');
            $(".close").removeClass('login_active');
            $(".login_frame").addClass('register_active');
            $(".register_form").addClass('register_active');
            $(".dialog_logo").addClass('register_active');
            $(".close").addClass('register_active');
            $(".register_form").removeClass("dis_n");
        }
    });
    // 弹窗关闭
    $(".close").on("click", function () {
        $(".dialog_iframe").addClass("dis_n");
        $(".login_form").removeClass("dis_n");
        $(".login_form").removeClass("dis_n");
        $(".register_form").addClass("dis_n");
        $(".login_frame").removeClass('login_active');
        $(".register_form").removeClass('login_active');
        $(".dialog_logo").removeClass('login_active');
        $(".close").removeClass('login_active');
        $(".login_frame").removeClass('register_active');
        $(".login_form").removeClass('register_active');
        $(".dialog_logo").removeClass('register_active');
        $(".close").removeClass('register_active');
        $(".login_frame").addClass("dialog_close_active"); // 弹窗翻转
        $(".register_form").addClass("dialog_close_active");
        $(".dialog_logo").addClass("dialog_close_active");
        $(".close").addClass("dialog_close_active");
    });
    // 登陆注册按钮点击事件
    $(".login").on("click", function () {
        $(".login_frame").removeClass("dialog_close_active"); // 弹窗翻转
        $(".register_form").removeClass("dialog_close_active");
        $(".dialog_logo").removeClass("dialog_close_active");
        $(".close").removeClass("dialog_close_active");
        $(".login_frame").removeClass('register_active');
        $(".login_form").removeClass('register_active');
        $(".dialog_logo").removeClass('register_active');
        $(".close").removeClass('register_active');
        $(".login_frame").addClass('login_active');
        $(".login_form").addClass('login_active');
        $(".dialog_logo").addClass('login_active');
        $(".close").addClass('login_active');
        $(".dialog_iframe").removeClass("dis_n");
        $(".login_form").removeClass("dis_n");
        $(".register_form").addClass("dis_n");
        $(".common_login").click();
    });
    $(".register").on("click", function () {
        enter_event = "register";
        $(".dialog_iframe").removeClass("dis_n");
        $(".login_form").addClass("dis_n");
        $(".register_form").removeClass("dis_n");
    });

    // 切换登录方式
    $(".common_login").on("click", function () {
        enter_event = "common_login";
        $(".login_type ul li").removeClass('active');
        $(this).addClass("active");
        $(".login_form form").removeClass("active");
        $("#login-form").addClass("active");
        $(".login_form .btn_dialog").removeClass("active");
        $(".commo_btn").addClass("active");
    });
    $(".mailbox_login").on("click", function () {
        enter_event = "mailbox_login";
        $(".login_type ul li").removeClass('active');
        $(this).addClass("active");
        $(".login_form form").removeClass("active");
        $("#mailbox_login").addClass("active");
        $(".login_form .btn_dialog").removeClass("active");
        $(".mailbox_btn").addClass("active");
    });

    // 获取验证码倒计时
    var countdown = 60;
    var _generate_code;
    // var myReg = /^[a-zA-Z0-9_-]+@([a-zA-Z0-9]+\.)+(com|cn|net|org)$/;
    var myReg = /^\d+$/;
    // 注册获取验证码
    $("#register_email").focus(function () {
        if ($("#register_countryCode").val() == "null") {
            layer.msg((COUNTRY == "中文") ? '请先选择国家' : 'Please select a country first');
            $(this).blur();
        }
    });
    $("#getRegister_code").on("click", function () {
        var clickDome = $(this);
        if($(this).hasClass("hadclicked")){
            layer.msg("Please wait a moment");
            return;
        }
        $(this).addClass("hadclicked");
        var disabled = $("#getRegister_code").attr("disabled");
        _generate_code = $("#getRegister_code");
        countdown = 60;
        if (disabled) {
            return false;
        }
        if (!myReg.test($("#register_email").val()) || $("#register_user").val() == "" || $("#register_psw").val() == "") {
            $(".register_error  span").html((COUNTRY == "中文") ? '请将信息填写完整' : 'Please enter a username or email box');
            $(".register_error ").show();
            return false;
        }
        var data = {
            phone: $("#register_email").val(),
            country_code: $("#register_countryCode").val(),
            email: $("#register_mail").val(),
            // name: $("#register_user").val(),
            // password: $("#register_psw").val(),
            _token: $("#register_token_code").find("input").val()
        };
        var url = clickDome.attr('data-url');
        $.ajax({
            type: "post",
            url: url,
            data: data,
            success: function (data) {
                settime();
                clickDome.removeClass("hadclicked");
            },
            error: function (err) {
                console.log(err);
                if (err.status == 422) {
                    var arr = []
                    var dataobj = err.responseJSON.errors;
                    for (let i in dataobj) {
                        arr.push(dataobj[i]); //属性
                    }
                    layer.msg(arr[0][0]);
                    clickDome.removeClass("hadclicked");
                }
                if (err.status == 500) {
                    $("#getRegister_code").prop("disabled", false);
                    clickDome.removeClass("hadclicked");
                    layer.alert("System error, please click again");
                }
            },
            complete: function (data) {
            }
        });
    });
    // 登录获取验证码
    $("#login_email").focus(function () {
        if ($("#login_countryCode").val()=="null") {
            layer.msg((COUNTRY == "中文") ? '请先选择国家' : 'Please select a country first');
            $(this).blur();
        }
    });
    $("#getLogin_code").on("click", function () {
        var clickDome = $(this);
        if($(this).hasClass("hadclicked")){
            layer.msg("Please wait a moment");
            return;
        }
        $(this).addClass("hadclicked");
        var disabled = $("#getLogin_code").attr("disabled");
        _generate_code = $("#getLogin_code");
        countdown = 60;
        if (disabled) {
            return false;
        }
        var data = {
            phone: $("#login_email").val(),
            country_code: $("#login_countryCode").val(),
            _token: $("#login_token_code").find("input").val()
        };
        var url = clickDome.attr('data-url');
        $.ajax({
            type: "post",
            url: url,
            data: data,
            success: function (data) {
                settime();
                clickDome.removeClass("hadclicked")
            },
            error: function (err) {
                console.log(err);
                if (err.status == 422) {
                    layer.msg($.parseJSON(err.responseText).errors.phone[0]);
                    clickDome.removeClass("hadclicked");
                }
                if (err.status == 500) {
                    $("#getLogin_code").prop("disabled", false);
                    clickDome.removeClass("hadclicked");
                    layer.alert("System error, please click again");
                }
            },
        });
    });
    // 邮箱验证登录
    /*$(".mailbox_btn").on("click", function () {
     var clickDome = $(this);
     if ($("#login_code").val() == "" || $("#login_code").val() == null) {
     $(".error_content span").html("请输入正确的手机号和验证码");
     $(".error_content").show();
     return false;
     }
     var data = {
     country_code: $("#login_countryCode").val(),
     phone: $("#login_email").val(),
     code: $("#login_code").val(),
     _token: $("#login_token_code").find("input").val()
     };
     var url = clickDome.attr('data-url');
     $.ajax({
     type: "post",
     url: url,
     data: data,
     success: function (json) {
     json = json.replace(/\s+/g, "");
     var dataObj = $.parseJSON(json);
     location.reload();
     },
     error: function (err) {
     console.log(err);
     if (e.status == 422) {
     layer.msg($.parseJSON(e.responseText).errors.code[0]);
     }
     },
     });
     });*/

    function settime() {
        if (countdown == 0) {
            _generate_code.attr("disabled", false);
            _generate_code.css({backgroundColor: "#7ca442", color: "#fff", cursor: "pointer"});
            _generate_code.val((COUNTRY == "中文") ? '获取验证码' : 'Verification code');
            countdown = 60;
            return false;
        } else {
            _generate_code.attr("disabled", true);
            _generate_code.css({backgroundColor: "#f5f7f4", color: "#d1d3cf", cursor: "not-allowed"});
            _generate_code.val("" + countdown + "s");
            countdown--;
        }
        setTimeout(function () {
            settime();
        }, 1000);
    }

    // 获取路由参数如存在参数action=login则显示登录弹窗
    // 获取url参数
    function getUrlVars() {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars["action"];
    }

    var action = "";
    $(document).ready(function () {
        if (getUrlVars() != undefined) {
            action = getUrlVars()
        }
        switch (action) {
            case "login":
                $(".login").click();
                break;
            default :
                break;
        }
    });
    /*window.onload = function () {
     if (getUrlVars() != undefined) {
     action = getUrlVars()
     }
     switch (action) {
     case "login":
     $(".login").click();
     break;
     default :
     break;
     }
     };*/
});

$(function () {
    // 自定义弹窗关闭
    $(".dialog_popup").on("click", ".close", function () {
        $(".dialog_popup").hide();
    });
    $(".dialog_popup .btn_area").on("click", ".cancel", function () {
        $(".dialog_popup").hide();
    });
});

// 登陆注册弹窗调整
$(function () {
    $("#login-form").validate({
        rules: {
            username: {
                required: true
            },
            password: {
                required: true
            }
        },
        messages: {
            username: {
                required: (COUNTRY == "中文") ? '请输入用户名或邮箱' : 'Please enter a username or email box',
            },
            password: {
                required: (COUNTRY == "中文") ? '请输入密码' : 'Please input a password',
            }
        }
    });
    $("#register-form").validate({
        rules: {
            name: {
                required: true
            },
            password: {
                required: true
            },
            phone: {
                required: true
            }
        },
        messages: {
            name: {
                required: (COUNTRY == "中文") ? '请输入用户名' : 'Enter one user name',
            },
            password: {
                required: (COUNTRY == "中文") ? '请输入密码' : 'Please input a password',
            },
            phone: {
                required: (COUNTRY == "中文") ? '输入手机号' : 'Enter cell phone number',
            },
        },
    });
    $("#mailbox_login").validate({
        rules: {
            phone: {
                required: true,
            },
        },
        messages: {
            phone: {
                required: (COUNTRY == "中文") ? '请输入正确有效的手机号' : 'Please input the correct cell phone number',
            },
        },
    });
    //普通登录
    $(".commo_btn").on("click", function () {
        var clickDome = $(this);
        if ($("#login-form").valid()) {
            var data = {
                username: $("#login-form").find("input[name='username']").val(),
                password: $("#login-form").find("input[name='password']").val(),
                _token: $("#commn_login_token_code").find("input").val(),
            };
            // $('#login-form').submit();
            var url = clickDome.attr('data-url');
            $.ajax({
                type: "post",
                url: url,
                data: data,
                success: function (json) {
                    if (json.code == 200) {
                        window.location.reload();
                    } else {
                        layer.alert(json.message);
                    }
                },
                error: function (e) {
                    console.log(e)
                },
                complete: function () {

                }
            });
        }
    });
    // 注册
    $("#register_btn").on("click", function () {
        var clickDome = $(this);
        if ($("#register-form").valid()) {
            if ($("#register_code").val() != "" && $("#agreement").prop("checked") != false) {
                // $('#register-form').submit();
                var data = {
                    name: $("#register-form").find("input[name='name']").val(),
                    password: $("#register-form").find("input[name='password']").val(),
                    phone: $("#register_email").val(),
                    country_code: $("#register_countryCode").val(),
                    _token: $("#register_token_code").find("input").val(),
                    code: $("#register_code").val(),
                    email: $("#register_mail").val()
                };
                var url = clickDome.attr('data-url');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (json) {
                        if (json.code == 200) {
                            window.location.reload();
                        } else {
                            layer.alert(json.message);
                        }
                    },
                    error: function (e) {
                        console.log(e);
                        if (e.status == 422) {
                            layer.msg($.parseJSON(e.responseText).errors.code[0]);
                        }
                    },
                    complete: function () {

                    }
                });
            } else {
                layer.msg("Please complete the information");
            }
        }
    });
    // 手机密码登录
    $(".mailbox_btn").on("click", function () {
        var clickDome = $(this);
        if ($("#mailbox_login").valid()) {
            if ($("#login_code").val() != "") {
                // $('#mailbox_login').submit();
                var data = {
                    phone: $("#login_email").val(),
                    country_code: $("#login_countryCode").val(),
                    _token: $("#login_token_code").find("input").val(),
                    code: $("#login_code").val()
                };
                var url = clickDome.attr('data-url');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (json) {
                        if (json.code == 200) {
                            window.location.reload();
                        } else {
                            layer.alert(json.message);
                        }
                    },
                    error: function (e) {
                        console.log(e);
                        if (e.status == 422) {
                            layer.msg($.parseJSON(e.responseText).errors.code[0]);
                        }
                    },
                    complete: function () {

                    }
                });
            } else {
                $(".mailbox_error").css("display", "block");
            }
        }
    });
    // 区号选择
    $(".choose_tel_area").on("change", function () {
        $(this).parents(".register_phone").find(".areaCode_val").html($(this).find("option:checked").val());
        $(this).parents(".register_phone").find("input").addClass("active");
        $(this).parents(".register_phone").find("input").prop('placeholder', (COUNTRY == "中文") ? '请输入手机号' : 'Please enter phone number');
    });
});
// 顶部模糊搜索
$(function () {
    var lastTime;
    $(".selectInput_header").bind("input propertychange", function (event) {
        lastTime = event.timeStamp;
        var clickDom = $(this);
        setTimeout(function () {
            if (lastTime - event.timeStamp == 0) {
                $.ajax({
                    type: "get",
                    url: clickDom.attr("data-url"),
                    data: {
                        "query": $(".selectInput_header").val()
                    },
                    success: function (json) {
                        var html = "";
                        var name;
                        $.each(json.data.products, function (i, n) {
                            name = (COUNTRY == "中文") ? n.name_zh : n.name_en
                            html += "<li>" +
                                "<a code='" + n.id + "' >" + name + "</a>" +
                                "</li>"
                        });
                        $(".selectList ul").html("");
                        $(".selectList ul").append(html);
                        $(".selectList").removeClass("dis_n");
                        enter_event = "header_search";
                    },
                    error: function (e) {
                        console.log(e);
                        if (e.status == 422) {
                        }
                    }
                });
            }
        }, 300);
    });

    // 判断搜索框焦点
    $(".selectInput_header").bind("input focus", function (event) {
        enter_event = "header_search";
    });

    // 点击页面部分关闭搜索结果弹窗
    $(document).on("click", function () {
        $(".selectList").addClass("dis_n");
        $(".selectList ul").html("");
    });
    // 点击搜索结果赋值
    $(".selectList ul").on("click", "li", function () {
        window.location.href = $(".selectList").attr("data-url") + "?query=" + $(this).find("a").html();
    });
    // 点击查找按钮
    $(".search_btn").on("click", function () {
        window.location.href = $(".selectList").attr("data-url") + "?query=" + $(".selectInput_header").val();
    });
    // 绑定回车键出发搜索
    // 回车键事件函数
    $(document).keyup(function (event) {
        if (event.keyCode == 13) {
            switch (enter_event) {
                case "header_search": // 搜索
                    $(".search_btn").trigger("click");
                    break;
                case "common_login": // 普通登陆
                    $(".commo_btn").click();
                    break;
                case "mailbox_login": // 手机验证码登陆
                    $(".mailbox_btn").click();
                    break;
                case "register": // 注册
                    $("#register_btn").trigger("click");
                    break;
                default :
                    break;
            }
        }
    });

    // header hover效果
    $(".navbar-bottom-bottom-left .first_menu").mouseenter(function (event) {
        $(this).find(".nav-panel-dropdown").fadeIn();
    }).mouseleave(function (event) {
        $(this).find(".nav-panel-dropdown").fadeOut();
    });
    //header search change
    $(".for_show_search").on("click",function(){
    	$(this).addClass("active");
    	$(".show_search").addClass("active");
    })
    //footer订阅功能
    $("#subFootCode").on("click",function(){
        var dataUrl = $(this).attr("data-url");
        if($("#footemail").val() == ""){
            layer.msg("The email field is required");
            return;
        }
        var data = {
            _token: $("#footer_token_code").find("input").val(),
            email: $("#footemail").val(),
            captcha: $("#footverCode").val()
        }
        $.ajax({
            type:"post",
            url:dataUrl,
            data: data,
            success:function(data){
                layer.msg("Subscription success!");
            },
            error:function(err){
                console.log(err);
                if(err.status!=200){
                    var errorTips = err.responseJSON.errors.captcha;
                    layer.msg(errorTips[0])
                }
            }
        });
    })
});
