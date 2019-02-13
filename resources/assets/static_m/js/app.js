require('./bootstrap');

//window.Vue = require('vue');
//require('./components/SelectDistrict');
//require('./components/UserAddressesCreateAndEdit');
// require('./components/jquery.lazyload/jquery.lazyload.min');
// require('./jquery.validate.min');

//const app = new Vue({
//  el: '#app'
//});
/**
 * 通用模块
 */
//懒加载
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

//图片懒加载方法一
//var scrollElement = document.querySelector('.lazy'),
//   viewH = document.documentElement.clientHeight;
//
//function lazyload(){
// var nodes = document.querySelectorAll('img[data-src]');
// Array.prototype.forEach.call(nodes,function(item,index){
//   var rect;
//   if(item.dataset.src==='') return;
//   rect = item.getBoundingClientRect();
//   if(rect.bottom>=0 && rect.top < viewH){
//       (function(item){
//         var img = new Image();
//         img.onload = function(){
//           item.src = img.src;
//         }
//         img.src = item.dataset.src
//         item.dataset.src = ''
//       })(item)
//   }
// })
//}
//
//lazyload();
//
//document.addEventListener('scroll',throttle(lazyload,500,1000));
//
//function throttle(fun, delay, time) {
//   var timeout,
//       startTime = new Date();
//   return function() {
//       var context = this,
//           args = arguments,
//           curTime = new Date();
//       clearTimeout(timeout);
//       if (curTime - startTime >= time) {
//           fun.apply(context, args);
//           startTime = curTime;
//       } else {
//           timeout = setTimeout(fun, delay);
//       }
//   };
//};


//懒加载方法二
window.onload = function(){
	var flag = true;//在一定时间之后才调用滚动函数
	let imgSrc = getRealImgSrc();//获取图片真正的src
	//获得图片的真实地址
	function getRealImgSrc(){
		let imgSrcTemp = [];
		let img = document.getElementsByTagName("img");//图片
		for(let i =0, len = img.length; i<len; i++){
			imgSrcTemp.push(img[i].getAttribute("data-src"));
		}
		return imgSrcTemp;
	}
	imgLazyLoad();
	//初始化
	function init(){
		let clientHeight = document.documentElement.clientHeight;
		let img = document.getElementsByTagName("img");//图片
		for(let i=0,len = img.length;i<len;i++){
			if(img[i].offsetTop <= clientHeight ){
				img[i].setAttribute("src",imgSrc[i]);
				img[i].style.opacity = 1;
			}
		}
	}

	//图片懒加载
	function imgLazyLoad(){
		let scrollTop = document.body.scrollTop || document.documentElement.scrollTop;
		let clientHeight = document.documentElement.clientHeight;
		let img = document.getElementsByTagName("img");//图片
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

//init();//初始化首屏的加载

	//滚动加载
	window.onscroll = function(){
		if(flag){
			flag = false;
			imgLazyLoad();
		}
	}

	//滚动函数节流使用
	setInterval(function(){
		flag = true;
	},100);
	
	//解决ios10及以上Safari无法禁止缩放的问题
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
	
	
}
