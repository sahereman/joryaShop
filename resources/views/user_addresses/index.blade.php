@extends('layouts.app')
@section('title', App::isLocale('en') ? 'Personal Center-shipping Address' : '个人中心-收货地址')
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
                                    <td class="address_info">{{ $address->address }}</td>
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
    <div class="dialog_popup new_receipt_address">
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
                          id="creat-form">
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
                                <span class="input_name"><i>*</i>@lang('basic.address.Detailed address')：</span>
                                <textarea name="address"
                                          placeholder="@lang('basic.address.Detailed_address')"></textarea>
                            </li>
                            <li>
                                <p class="default_address_set">
                                    <label>
                                        <input type="checkbox" name="is_default" class="setas_default">
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
                        <input type="hidden" name="_method" value="PUT">
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
                                <span class="input_name"><i>*</i>@lang('basic.address.Detailed address')：</span>
                                <textarea name="address"
                                          placeholder="@lang('basic.address.Detailed_address')"></textarea>
                            </li>
                            <li>
                                <p class="default_address_set">
                                    <label>
                                        <input type="checkbox" name="is_default" class="setas_default"
                                               id="edit_default">
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
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".user_address").addClass("active");
            //点击新建收货地址
            $(".new_address").on("click", function () {
                if ($(".residual").html() != 0) {
                    $(".new_receipt_address").show();
                } else {
                    $(".confirm_residual").show();
                }
            });
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
                    }
                }
            });
            $(".new_receipt_address").on("click", ".success", function () {
                if ($("#creat-form").valid()) {
                    $('#creat-form').submit();
                }
            });


            //点击表格中的编辑
            $(".address_list table").on("click", ".edit_address", function () {
                $("#edit-form").prop("action", $(this).attr("url"));
                $(".edit_harvest_address").find(".user_name").val($(this).parents("tr").find(".address_name").html());
                $(".edit_harvest_address").find(".user_tel").val($(this).parents("tr").find(".address_tel").html());
                $(".edit_harvest_address").find("textarea").val($(this).parents("tr").find(".address_info").html());
                var isdefault = $(this).parents("tr").find(".setDefaultAddress ").hasClass("haddefault");
                if (isdefault == true) {
                    $(".edit_harvest_address").find("#edit_default").attr("checked", true);
                } else {
                    $(".edit_harvest_address").find("#edit_default").attr("checked", false);
                }
                $(".edit_harvest_address").show();
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
                    }
                }
            });
            //编辑收货地址弹窗中的确定按钮
            $(".edit_harvest_address").on("click", ".success", function () {
                if ($("#edit-form").valid()) {
                    $('#edit-form').submit();
                }
            });
            //点击表格中的设为默认按钮
            $(".address_list table").on("click", ".setDefaultAddress", function () {
                if (!$(this).hasClass('haddefault')) {
                    var data = {
                        _method: "PATCH",
                        _token: "{{ csrf_token() }}",
                        is_default: 1
                    }
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
                        }
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
                    _token: "{{ csrf_token() }}"
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
                    }
                });
            });
        });
    </script>
@endsection
