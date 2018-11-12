@extends('layouts.app')
@section('title', $category->name_zh)
@section('content')
    <div class="products-search-level">
        <div class="m-wrapper">
            <!--面包屑-->
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">全部结果</a>
                    <span>></span>
                    <a href="#">{{ $category->name_zh }}</a>
                </p>
            </div>
            <div class="search-level">
                <ul>
                    <li class="active default">
                        <a code='index'>综合</a>
                    </li>
                    <li>
                        <a code='heat'>人气</a>
                    </li>
                    <li>
                        <a code='latest'>新品</a>
                    </li>
                    <li>
                        <a code='sales'>销量</a>
                    </li>
                    <li class="icon">
                        <a code='0'>
                            <span>价格</span>
                            <div>
                                <i code='price_asc'  class="w-icon-arrow arrow-up"></i>
                                <i code='price_desc' class="w-icon-arrow arrow-down"></i>
                            </div>
                        </a>
                    </li>
                </ul>
                <div>
                    <input type="text" class="min_price" placeholder="￥"/>
                    <span></span>
                    <input type="text" class="max_price" placeholder="￥"/>
                    <button class="searchByPrice">确定</button>
                </div>
            </div>
            <!--商品分类展示-->
            <div class="classified-display">
                <div class="classified-products">
                    <ul class="classified-lists"></ul>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function(){
        	var loading_animation;  //loading动画的全局name
        	var sort = "index";   //排序传参用的参数默认为综合排序
        	var dataoption_1;  //页面加载时用来请求ajax的data
        	var dataoption_2;  //通过价格区间方式获取数据ajax
        	var dataoption_3;  //滚动条使用
        	var loading = false;    //阻止同时进行多次ajax异步请求
        	var requestType = 0;   //用来判断滚动条加载数据时应该传递那种参数 0：页面加载时的默认排序，点击人气综合等排序 。1：根据价格区间来获取排序
            var page_num = 2;    //请求页面
        	window.onload=function(){
	        	dataoption_1={
	        		query: getQueryString("query"),
	        		sort: sort,
	        		page: 1
	        	}
	        	getResults(dataoption_1,true);
	        }
	        //获取商品列表
	        function getResults(data,type){
	        	$.ajax({
	        		type:"get",
	        		url:window.location.pathname,
	        		data:data,
	        		async: type,
	        		beforeSend:function(){
	        			loading_animation = layer.msg('加载中请稍候', {
			                icon: 16,
			                shade: 0.4,
			                time:false //取消自动关闭
						});
	        		},
	        		success:function(json){
	        			console.log(json)
						var dataobj = json.data.products.data;
						var html = "";
						if(dataobj.length>0){
							$.each(dataobj, function(i,n) {
								html+="<li>"+
								        "<a href='/products/"+ n.product_category_id +"'>"+
		                                  "<div class='list-img'>"+
		                                  	  "<img src='"+ n.thumb +"'>"+
		                                  "</div>"+
		                                  "<div class='list-info'>"+
			                                  "<p class='list-info-title'>"+ n.name_zh +"</p>"+
			                                  "<p>"+
			                                  "<span class='new-price'><i>&yen;</i>"+ n.price +"</span>"+
			                                  "<span class='old-price'><i>&yen;</i>"+ n.price +"</span>"+
			                                  "</p>"+
		                                  "</div>"+
		                                  "</a>"
	                                  "</li>"
							});	
							loading = false;
						}else {
							if(json.data.products.current_page==1){
								html = "<li class='empty_tips'>"+
										"<p>"+
										"<img src='{{ asset('img/warning.png') }}'>"+
										"很抱歉没有找到"+
										"“<span class='red'>"+ getQueryString("query") +"</span>”相关的商品"+
										"</p>"+
										"</li>"	
							}else {
								html ="<li class='ending_empty_tips'>"+
										"<p>暂无其他内容</p>"+
										"</li>"	
							}
							loading = true;    //当返回数组内容为空时阻止滚动条滚动
						}
						$(".classified-lists").append(html);
	        	    },
	        	    error:function(e){
	        	    	console.log(e);
	        	    },
	        	    complete:function(){
	        	    	layer.close(loading_animation);
	        	    }
	        	
	        	});
	        }
	        /*获取url参数*/
		    function getQueryString(name) {
		        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
		        var r = window.location.search.substr(1).match(reg);
		        if (r != null)
		            return decodeURI(r[2]);
		        return null;
		    }
		    $(window).scroll(function(){
		        //通过判断滚动条的top位置与可视网页之和与整个网页的高度是否相等来决定是否加载内容；
	            if((($(window).scrollTop()+$(window).height())+300)>=$(document).height()){
	            	if(loading == false){
	            		loading = true;
	            		if(requestType==0){
	            			dataoption_3 = {
	            				query: getQueryString("query"),
				        		page: page_num,
						        sort: sort
	            			}
	            		}else {
	            			dataoption_3 = {
	            				query: getQueryString("query"),
				        		page: page_num,
				        		min_price: $(".min_price").val(),
							    max_price: $(".max_price").val()
	            			}
	            		}
			        	getResults(dataoption_3,false);
			        	page_num++;
	            	}
	             }
		    })
		    //点击商品分类获取不同的信息
		    $(".search-level ul").on('click','li',function(){
		    	requestType = 0;
		    	page_num = 2;
		    	$(".search-level ul").find('li').removeClass("active");
		    	$(this).addClass("active");
		    	if($(this).hasClass("icon")){
		    		if($(this).attr('code')=='0'){
		    		    sort='price_asc'	
		    		    $(this).attr('code','1');
		    		    $(this).find('.arrow-up').css('opacity','1');
		    		    $(this).find('.arrow-down').css('opacity','0');
		    		}else {
		    			sort='price_desc'
		    			$(this).attr('code','0');
		    			$(this).find('.arrow-up').css('opacity','0');
		    		    $(this).find('.arrow-down').css('opacity','1');
		    		}
		    	}else {
		    		$(this).parents('ul').find('.arrow-up').css('opacity','1');
		    		$(this).parents('ul').find('.arrow-down').css('opacity','1');
		    		sort=$(this).find('a').attr('code');
		    	}
		    	dataoption_1={
	        		query: getQueryString("query"),
	        		page: 1,
			        sort: sort
			    }
			    $(".classified-lists").html("");
			    getResults(dataoption_1,true);
		    })
		    //根据价格区间来获取排序
		    $(".searchByPrice").on("click",function(){
		    	requestType = 1;
		    	page_num = 2;
		    	$(".search-level ul").find('li').removeClass("active");
		    	$(".search-level ul").find('.default').addClass("active");
		    	if(parseInt($(".min_price").val())>=parseInt($(".max_price").val())){
		    		layer.msg('请输入正确的价格区间');
		    	}else {
		    	    if($(".min_price").val()!=""&&$(".max_price").val()!=""){
		    	    	dataoption_2={
			        		query: getQueryString("query"),
			        		page: 1,
			        		min_price: $(".min_price").val(),
						    max_price: $(".max_price").val()
					    }	
					    $(".classified-lists").html("");
			            getResults(dataoption_2,true);
			    	}else {
			    		layer.msg("请输入正确的价格区间")
			    	}	
		    	}
		    })
        })
    </script>
@endsection
