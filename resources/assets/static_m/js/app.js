require('./bootstrap');

// window.Vue = require('vue');
// require('./components/SelectDistrict');
// require('./components/UserAddressesCreateAndEdit');
//  require('./components/jquery.lazyload/jquery.lazyload.min');
//  require('./jquery.validate.min');

// const app = new Vue({
//   el: '#app'
// });
/**
 * 通用模块
 */

// 懒加载
var $win = $(window),
    $doc = $(document),
    $body = $('body', $doc),
    winW = $win.width();
$(function () {
    if (!$.fn.lazyload) return;
    $("img.lazy", $body).lazyload({
        effect: "fadeIn",
        threshold: 100,
        failure_limit: 0
    });
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

// 图片懒加载方法一
// var scrollElement = document.querySelector('.lazy'),
//    viewH = document.documentElement.clientHeight;
//
// function lazyload(){
//  var nodes = document.querySelectorAll('img[data-src]');
//  Array.prototype.forEach.call(nodes,function(item,index){
//    var rect;
//    if(item.dataset.src==='') return;
//    rect = item.getBoundingClientRect();
//    if(rect.bottom>=0 && rect.top < viewH){
//        (function(item){
//          var img = new Image();
//          img.onload = function(){
//            item.src = img.src;
//          }
//          img.src = item.dataset.src
//          item.dataset.src = ''
//        })(item)
//    }
//  })
// }
//
// lazyload();
//
// document.addEventListener('scroll',throttle(lazyload,500,1000));
//
// function throttle(fun, delay, time) {
//    var timeout,
//        startTime = new Date();
//    return function() {
//        var context = this,
//            args = arguments,
//            curTime = new Date();
//        clearTimeout(timeout);
//        if (curTime - startTime >= time) {
//            fun.apply(context, args);
//            startTime = curTime;
//        } else {
//            timeout = setTimeout(fun, delay);
//        }
//    };
// };


// 懒加载方法二
window.onload = function(){
    var flag = true; // 在一定时间之后才调用滚动函数
    let imgSrc = getRealImgSrc(); // 获取图片真正的src
    // 获得图片的真实地址
    function getRealImgSrc(){
        let imgSrcTemp = [];
        let img = document.getElementsByTagName("img"); //图片
        for(let i =0, len = img.length; i<len; i++){
            imgSrcTemp.push(img[i].getAttribute("data-src"));
        }
        return imgSrcTemp;
    }
    imgLazyLoad();
    // 初始化
    function init(){
        let clientHeight = document.documentElement.clientHeight;
        let img = document.getElementsByTagName("img"); // 图片
        for(let i=0,len = img.length;i<len;i++){
            if(img[i].offsetTop <= clientHeight ){
                img[i].setAttribute("src",imgSrc[i]);
                img[i].style.opacity = 1;
            }
        }
    }

    // 图片懒加载
    function imgLazyLoad(){
        let scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
        let clientHeight = document.documentElement.clientHeight;
        let img = document.getElementsByTagName("img"); // 图片
        for(let i=0,len = img.length;i<len;i++){
            if(img[i].offsetTop <= scrollTop + clientHeight-100){
                if(!img[i].getAttribute("src")){
                    img[i].setAttribute("src",imgSrc[i]);
                    img[i].onload = function(){
                        this.className += " imgFadeIn";
                        setTimeout(function(){
                            this.style.opacity = 1;
                        }.bind(this),200)
                    }
                }
            }
        }
    }

// init(); // 初始化首屏的加载

    // 滚动加载
    window.onscroll = function(){
        if(flag){
            flag = false;
            imgLazyLoad();
        }
    };

    // 滚动函数节流使用
    setInterval(function(){
        flag = true;
    },100);
    
    // 解决ios10及以上Safari无法禁止缩放的问题
    // 阻止双击放大
    var lastTouchEnd = 0;
    document.addEventListener('touchstart', function(event) {
        if (event.touches.length > 1) {
            event.preventDefault();
        }
    });
    document.addEventListener('touchend', function(event) {
        var now = (new Date()).getTime();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, false);

    // 阻止双指放大
    document.addEventListener('gesturestart', function(event) {
        event.preventDefault();
    });
};
