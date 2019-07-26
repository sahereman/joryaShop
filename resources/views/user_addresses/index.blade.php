@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? '个人中心 - 收货地址' : 'Personal Center - Shipping Address') . ' - ' . \App\Models\Config::config('title'))
@section('content')
    <div class="User_addresses">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">@lang('basic.home')</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">@lang('basic.users.Personal_Center')</a>
                    <span>></span>
                    <a href="javascript:void(0);">@lang('basic.users.Receiving_address')</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="user_addresses_content">
                @if($addresses->isEmpty())
                        <!--当没有收货地址列表时显示,如需显示当前内容需要调整一下样式-->
                <div class="no_addressList">
                    <img src="{{ asset('img/location.png') }}">
                    <p>@lang('basic.users.shipping address yet')</p>
                    <a class="new_address">@lang('basic.users.Set up a new shipping address')</a>
                </div>
                @else
                        <!--存在收货地址列表-->
                <div class="receive_address">
                    <div class="address_note">
                        <div class="pull-left">
                            <p>@lang('basic.users.Stored shipping address')
                                （@lang('basic.users.Up to'){{ $max }}@lang('basic.users.addresses_and can save')<span
                                        class="residual">{{ $max - $count }}</span>）</p>
                        </div>
                        <div class="pull-right">
                            <a class="new_address">+@lang('basic.address.The new address')</a>
                        </div>
                    </div>
                    <!--地址列表-->
                    <div class="address_list">
                        <table>
                            <thead>
                            <tr>
                                <th class="address_name">@lang('basic.address.The consignee')</th>
                                <th class="address_info">@lang('basic.address.address')</th>
                                <th class="address_tel">@lang('basic.address.Contact')</th>
                                <th class="address_operation">@lang('basic.users.operating')</th>
                                <th class="default_address"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($addresses as $address)
                                <tr>
                                    <td class="address_name">{{ $address->name }}</td>
                                    <td class="address_info">{{ $address->full_address }}</td>
                                    <!--新增用于修改是显示-->
                                    <td class="dis_n address_country">{{ $address->country }}</td>
                                    <td class="dis_n address_city">{{ $address->city }}</td>
                                    <td class="dis_n address_province">{{ $address->province }}</td>
                                    <td class="dis_n address_detail">{{ $address->address }}</td>
                                    <td class="dis_n address_zip">{{ $address->zip }}</td>
                                    <!--电话建议后台正则处理前端处理容易泄露-->
                                    <td class="address_tel">{{ $address->phone }}</td>
                                    <td class="address_operation">
                                        <a url="{{ route('user_addresses.update', ['address' => $address->id]) }}"
                                           class="edit_address">@lang('basic.address.edit')</a>
                                        <a url="{{ route('user_addresses.destroy', ['address' => $address->id]) }}"
                                           class="delete_address">@lang('basic.delete')</a>
                                    </td>
                                    <td class="default_address">
                                        <!--两种情况，正式情况只能显示一种，且默认地址只有一个-->
                                        @if($address->is_default)
                                            <a class="setDefaultAddress haddefault">@lang('basic.address.Default address')</a>
                                        @else
                                            <a url="{{ route('user_addresses.set_default', ['address' => $address->id]) }}"
                                               class="setDefaultAddress">@lang('basic.address.Set to the default')</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!--是否确认删除弹出层-->
    <div class="dialog_popup confirm_delete">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>@lang('app.Prompt')</span>
                </div>
                <div class="textarea_content">
                    <p>
                        <img src="{{ asset('img/warning.png') }}">
                        <span>@lang('basic.address.Are you sure you want to delete this address')</span>
                    </p>
                </div>
            </div>
            <div class="btn_area">
                <a class="success">@lang('app.determine')</a>
                <a class="cancel">@lang('app.cancel')</a>
            </div>
        </div>
    </div>
    <div class="dialog_popup confirm_residual">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>@lang('app.Prompt')</span>
                </div>
                <div class="textarea_content">
                    <p>
                        <img src="{{ asset('img/warning.png') }}">
                        <span>@lang('basic.address.The number of delivery addresses exceeds the upper limit')</span>
                    </p>
                </div>
            </div>
            <div class="btn_area">
                <a class="cancel">@lang('app.cancel')</a>
            </div>
        </div>
    </div>
    <!--新建收货地址弹出层-->
    {{--<div class="dialog_popup new_receipt_address">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>@lang('basic.address.The new address')</span>
                </div>
                <div class="textarea_content">
                    <form method="POST" action="{{ route('user_addresses.store') }}" enctype="multipart/form-data"
                          id="creat-form111">
                        {{ csrf_field() }}
                        <ul>
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>@lang('basic.address.The consignee')：</span>
                                    <input class="user_name" name="name" type="text"
                                           placeholder="@lang('basic.address.Enter the consignee name')">
                                </p>
                                <p>
                                    <span class="input_name"><i>*</i>@lang('basic.address.Contact')：</span>
                                    <input class="user_tel" name="phone" type="text"
                                           placeholder="@lang('basic.address.Enter the real and valid mobile phone number')">
                                </p>
                            </li>
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>Country or region：</span>
                                    <input class="user_country" name="country" type="text"
                                           placeholder="Enter the Country or region">
                                </p>
                            </li>
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>City：</span>
                                    <input class="user_city" name="city" type="text"
                                           placeholder="Enter the City">
                                </p>
                                <p>
                                    <span class="input_name"><i>*</i>State/Province/Region：</span>
                                    <input class="user_province" name="province" type="text"
                                           placeholder="Enter the State/Province/Region">
                                </p>
                            </li>
                            <li>
                                <span class="input_name"><i>*</i>@lang('basic.address.Detailed address')：</span>
                                <textarea name="address"
                                          placeholder="@lang('basic.address.Detailed_address')"></textarea>
                            </li>
                            <li>
                                <p class="default_address_set">
                                    <label>
                                        <input type="checkbox" name="is_default" class="setas_default" value="1">
                                        <span>@lang('basic.address.Set to the default')</span>
                                    </label>
                                </p>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div class="btn_area">
                <a class="success">@lang('app.determine')</a>
                <a class="cancel">@lang('app.cancel')</a>
            </div>
        </div>
    </div>
    <!--编辑收货地址弹出层-->
    <div class="dialog_popup edit_harvest_address">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>@lang('basic.address.Edit harvest address')</span>
                </div>
                <div class="textarea_content">
                    <!--这个表单的userAddress值是不确定的，需要根据点击的按钮对应的那一行数据的值-->
                    <form method="POST" action=""
                          enctype="multipart/form-data" id="edit-form">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <ul>
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>@lang('basic.address.The consignee')：</span>
                                    <input class="user_name" name="name" type="text"
                                           placeholder="@lang('basic.address.Enter the consignee name')">
                                </p>
                                <p>
                                    <span class="input_name"><i>*</i>@lang('basic.address.Contact')：</span>
                                    <input class="user_tel" name="phone" type="text"
                                           placeholder="@lang('basic.address.Enter the real and valid mobile phone number')">
                                </p>
                            </li>
                            <!--新增修改内容的国家地区城市-->
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>Country or region：</span>
                                    <input class="user_country" name="country" type="text"
                                           placeholder="Enter the Country or region">
                                </p>
                            </li>
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>City：</span>
                                    <input class="user_city" name="city" type="text"
                                           placeholder="Enter the City">
                                </p>
                                <p>
                                    <span class="input_name"><i>*</i>State/Province/Region：</span>
                                    <input class="user_province" name="province" type="text"
                                           placeholder="Enter the State/Province/Region">
                                </p>
                            </li>
                            <li>
                                <span class="input_name"><i>*</i>@lang('basic.address.Detailed address')：</span>
                                <textarea name="address"
                                          placeholder="@lang('basic.address.Detailed_address')"></textarea>
                            </li>
                            <li>
                                <p class="default_address_set">
                                    <label>
                                        <input type="checkbox" name="is_default" class="setas_default"
                                               id="edit_default" value="1">
                                        <span>@lang('basic.address.Set to the default')</span>
                                    </label>
                                </p>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div class="btn_area">
                <a class="success">@lang('app.determine')</a>
                <a class="cancel">@lang('app.cancel')</a>
            </div>
        </div>
    </div>--}}
    <!--新增地址新版-->
    <div id="addNewAddress" class="dis_n address-info-form">
    	<form method="POST" action="{{ route('user_addresses.store') }}" enctype="multipart/form-data"
              id="creat-form">
            {{ csrf_field() }}
            <ul>
                <li>
                    <p>
                        <span class="input_name"><i>*</i>Country：</span>
                        {{--<input class="user_country" name="country" type="text">--}}
                        {{-- 国家选择 --}}
                        <select name="country"  class="user_country" id="user_country"></select>
                    </p>
                </li>
                <li>
                    <p>
                        <span class="input_name"><i>*</i>@lang('basic.address.The consignee')：</span>
                        <input class="user_name" name="name" type="text">
                    </p>
                </li>
                <li>
                    <p>
                        <span class="input_name"><i>*</i>@lang('basic.address.Detailed address')：</span>
                        <input name="address" class="user_detailed" placeholder="@lang('basic.address.Detailed_address')">
                    </p>
                </li>
                <li class="city-state-zip">
                    <p>
                        <span class="input_name"><i>*</i>City：</span>
                        <input class="user_city" name="city" type="text">
                    </p>
                    <p>
                        <span class="input_name"><i>*</i>State/Province/Region：</span>
                        {{--<input class="user_province" name="province" type="text">--}}
                        {{-- 省份联动 --}}
                        <select name="province" id="user_province" class="user_province"></select>
                    </p>
                    <p>
                        <span class="input_name"><i>*</i>Zipcode：</span>
                        <input class="user_zip" name="zip" type="text">
                    </p>
                </li>
                <li class="contact-number">
                    <p>
                        <span class="input_name"><i>*</i>@lang('basic.address.Contact')：</span>
                        <input class="user_tel" name="phone" type="text">
                    </p>
                </li>
                <li class="dis_ni">
                    <p class="default_address_set">
                        <label>
                            <input type="checkbox" name="is_default" class="setas_default" value="1">
                            <span>@lang('basic.address.Set to the default')</span>
                        </label>
                    </p>
                </li>
            </ul>
        </form>
    </div>
    <!--编辑地址新版-->
    <div id="editNewAddress" class="dis_n address-info-form">
        <form method="POST" action=""
              enctype="multipart/form-data" id="edit-form">
              {{ csrf_field() }}
              {{ method_field('PUT') }}
            <ul class="edit_harvest_address">
                <li>
                    <p>
                        <span class="input_name"><i>*</i>Country：</span>
                        <input class="user_country" name="country" type="text">
                    </p>
                </li>
                <li>
                    <p>
                        <span class="input_name"><i>*</i>@lang('basic.address.The consignee')：</span>
                        <input class="user_name" name="name" type="text">
                    </p>
                </li>
                <li>
                    <p>
                        <span class="input_name"><i>*</i>@lang('basic.address.Detailed address')：</span>
                        <input class="user_detailed" name="address" placeholder="@lang('basic.address.Detailed_address')">
                    </p>
                </li>
                <li class="city-state-zip">
                    <p>
                        <span class="input_name"><i>*</i>City：</span>
                        <input class="user_city" name="city" type="text">
                    </p>
                    <p>
                        <span class="input_name"><i>*</i>State/Province/Region：</span>
                        <input class="user_province" name="province" type="text">
                    </p>
                    <p>
                        <span class="input_name"><i>*</i>Zipcode：</span>
                        <input class="user_zip" name="zip" type="text">
                    </p>
                </li>
                <li class="contact-number">
                    <p>
                        <span class="input_name"><i>*</i>@lang('basic.address.Contact')：</span>
                        <input class="user_tel" name="phone" type="text">
                    </p>
                </li>
                <li class="dis_ni">
                    <p class="default_address_set">
                        <label>
                            <input type="checkbox" name="is_default" class="setas_default" value="1">
                            <span>@lang('basic.address.Set to the default')</span>
                        </label>
                    </p>
                </li>
            </ul>
        </form>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".user_address").addClass("active");
            
            //新建收货地址时进行表单验证
            $("#creat-form").validate({
                rules: {
                    name: {
                        required: true
                    },
                    phone: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "@lang('Please enter the consignee name')"
                    },
                    phone: {
                        required: "@lang('Please enter the consignee contact information')"
                    },
                    address: {
                        required: "@lang('Please enter the detailed shipping address')"
                    },
                },
            });
            
            //点击新建收货地址
            $(".new_address").on("click", function () {
                layer.open({
                  title: ["The new address","font-size: 18px;"],
                  type: 1,
                  btn: ['Confirm', 'Cancel'],
                  area: ['900px', '500px'],
                  content: $('#addNewAddress'),
                  yes: function(index, layero){
                    if ($("#creat-form").valid()) {
                      $('#creat-form').submit();
                    }
                  }
                });
//              if ($(".residual").html() != 0) {
//                  $(".new_receipt_address").show();
//              } else {
//                  $(".confirm_residual").show();
//              }
            });
//          $(".new_receipt_address").on("click", ".success", function () {
//              if ($("#creat-form").valid()) {
//                  $('#creat-form').submit();
//              }
//          });
            //点击表格中的编辑
            $(".address_list table").on("click", ".edit_address", function () {
                $("#edit-form").prop("action", $(this).attr("url"));
                $(".edit_harvest_address").find(".user_name").val($(this).parents("tr").find(".address_name").html());
                $(".edit_harvest_address").find(".user_tel").val($(this).parents("tr").find(".address_tel").html());
                $(".edit_harvest_address").find(".user_detailed").val($(this).parents("tr").find(".address_detail").html());
                //address_country
                $(".edit_harvest_address").find(".user_country").val($(this).parents("tr").find(".address_country").html());
                $(".edit_harvest_address").find(".user_city").val($(this).parents("tr").find(".address_city").html());
                $(".edit_harvest_address").find(".user_province").val($(this).parents("tr").find(".address_province").html());
                $(".edit_harvest_address").find(".user_zip").val($(this).parents("tr").find(".address_zip").html());
                var isdefault = $(this).parents("tr").find(".setDefaultAddress ").hasClass("haddefault");
                if (isdefault == true) {
                    $(".edit_harvest_address").find("#edit_default").attr("checked", true);
                } else {
                    $(".edit_harvest_address").find("#edit_default").attr("checked", false);
                }
                layer.open({
                  title: ["The new address","font-size: 18px;"],
                  type: 1,
                  btn: ['Confirm', 'Cancel'],
                  area: ['900px', '500px'],
                  content: $('#editNewAddress'),
                  yes: function(index, layero){
                    if ($("#edit-form").valid()) {
                       $('#edit-form').submit();
                    }
                  }
                });
//              $(".edit_harvest_address").show();
            });
            //编辑收货地址时进行表单验证
            $("#edit-form").validate({
                rules: {
                    name: {
                        required: true
                    },
                    phone: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                },
                messages: {
                    name: {
                        required: "@lang('Please enter the consignee name')"
                    },
                    phone: {
                        required: "@lang('Please enter the consignee contact information')"
                    },
                    address: {
                        required: "@lang('Please enter the detailed shipping address')"
                    },
                },
            });
            //编辑收货地址弹窗中的确定按钮
//          $(".edit_harvest_address").on("click", ".success", function () {
//              if ($("#edit-form").valid()) {
//                  $('#edit-form').submit();
//              }
//          });
            //点击表格中的设为默认按钮
            $(".address_list table").on("click", ".setDefaultAddress", function () {
                if (!$(this).hasClass('haddefault')) {
                    var data = {
                        _method: "PATCH",
                        _token: "{{ csrf_token() }}",
                        is_default: 1
                    };
                    var url = $(this).attr('url');
                    $.ajax({
                        type: "post",
                        url: url,
                        data: data,
                        success: function (data) {
                            window.location.reload();
                        },
                        error: function (err) {
                            console.log(err);
                        },
                    });
                }
            });
            //点击表格中的删除
            $(".address_list table").on("click", ".delete_address", function () {
                $(".textarea_content span").attr('url', $(this).attr('url'));
                $(".confirm_delete").show();
            });
            //点击确定删除按钮
            $(".confirm_delete").on("click", ".success", function () {
                var data = {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}",
                };
                var url = $(".textarea_content span").attr('url');
                $.ajax({
                    type: "post",
                    url: url,
                    data: data,
                    success: function (data) {
                        window.location.reload();
                    },
                    error: function (err) {
                        console.log(err);
                    },
                });
            });
        //     省份二级联动
        //    省份假数据
            // 设置二级联动中的选项数组
            var provience_array=['请选择省份','北京市','上海市','天津市','河北省','山西省','内蒙古省','辽宁省','吉林省','黑龙江省'];
            var city_array=[
                ['请选择城市'],
                ["东城区", "西城区", "崇文区", "宣武区", "朝阳区", "丰台区", "石景山区", "海淀区", "门头沟区", "房山区", "通州区", "顺义区", "昌平区", "大兴区", "怀柔区", "平谷区", "密云县", "延庆县"],
                ["黄浦区", "卢湾区", "徐汇区", "长宁区", "静安区", "普陀区", "虹口区", "杨浦区", "闵行区", "宝山区", "嘉定区", "浦东新区", "金山区", "松江区", "青浦区", "南汇区", "奉贤区", "崇明县"],
                ["和平区", "河东区", "河西区", "南开区", "河北区", "红桥区", "塘沽区", "汉沽区", "大港区", "东丽区", "西青区", "津南区", "北辰区", "武清区", "宝坻区", "宁河县", "静海县", "蓟县"],
                ["石家庄市","张家口市","承德市","秦皇岛市","唐山市","廊坊市","保定市","衡水市","沧州市","邢台市","邯郸市"],
                ["太原市","朔州市","大同市","阳泉市","长治市","晋城市","忻州市","晋中市","临汾市","吕梁市","运城市"],
                ["呼和浩特市","包头市","乌海市","赤峰市","通辽市","呼伦贝尔市","鄂尔多斯市","乌兰察布市","巴彦淖尔市","兴安盟","锡林郭勒盟","阿拉善盟"],
                ["沈阳市","朝阳市","阜新市","铁岭市","抚顺市","本溪市","辽阳市","鞍山市","丹东市","大连市","营口市","盘锦市","锦州市","葫芦岛市"],
                ["长春市","白城市","松原市","吉林市","四平市","辽源市","通化市","白山市","延边州"],
                ["哈尔滨市","齐齐哈尔市","七台河市","黑河市","大庆市","鹤岗市","伊春市","佳木斯市","双鸭山市","鸡西市","牡丹江市","绥化市","大兴安岭地区"]
            ];
            // 获取页面中的选项卡
            var provience=document.getElementById('user_country');
            var city=document.getElementById('user_province');

            // 给第一个选项卡中的option赋值
            provience.options.length=provience_array.length;
            for(var i=0;i<provience.options.length;i++){
                provience.options[i].text=provience_array[i];
                provience.options[i].value=provience_array[i];
            }

            // 初始化第二个选项卡，默认显示"请选择城市"
            city.options.length=1;
            city.options[0].text=city_array[0][0];
            city.options[0].value=city_array[0][0];

            // 通过onchange监视函数，一旦第一个选项卡发生变化，第二个选项卡中的内容也跟着变化
            provience.onchange=function(){
                city.options.length=city_array[this.selectedIndex].length;
                for(var j=0;j<city.options.length;j++){
                    city.options[j].text=city_array[this.selectedIndex][j];
                    city.options[j].value=city_array[this.selectedIndex][j];
                }
            }
        });
    </script>
@endsection
