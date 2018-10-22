@extends('layouts.app')
@section('title', '个人中心-收货地址')
@section('content')
    @include('common.error')
    <div class="User_addresses">
        <div class="m-wrapper">
            <div>
                <p class="Crumbs">
                    <a href="{{ route('root') }}">首页</a>
                    <span>></span>
                    <a href="{{ route('users.home') }}">个人中心</a>
                    <span>></span>
                    <a href="{{ route('user_addresses.index') }}">收货地址</a>
                </p>
            </div>
            <!--左侧导航栏-->
            @include('users._left_navigation')
                    <!--右侧内容-->
            <div class="user_addresses_content">
                <!--当没有收获地址列表时显示,如需显示当前内容需要调整一下样式-->
                <div class="no_addressList">
                    <img src="{{ asset('img/location.png') }}">
                    <p>您还没有收货地址</p>
                    <a class="new_address">新建收货地址</a>
                </div>
                <!--存在收获地址列表-->
                <div class="receive_address">
                    <div class="address_note">
                        <div class="pull-left">
                            <p>已保存收货地址（地址最多{{ $max }}条，还能保存<span class="residual">{{ $max - $count }}</span>条）</p>
                        </div>
                        <div class="pull-right">
                            <a class="new_address">+新建地址</a>
                        </div>
                    </div>
                    <!--地址列表-->
                    <div class="address_list">
                        <table>
                            <thead>
                            <tr>
                                <th class="address_name">收货人</th>
                                <th class="address_info">地址</th>
                                <th class="address_tel">联系方式</th>
                                <th class="address_operation">操作</th>
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
                                        <a class="edit_address">编辑</a>
                                        <a class="delete_address">删除</a>
                                    </td>
                                    <td class="default_address">
                                        <!--两种情况，正式情况只能显示一种，且默认地址只有一个-->
                                        @if($address->is_default)
                                            <a class="setDefaultAddress haddefault">默认地址</a>
                                        @else
                                            <a class="setDefaultAddress">设为默认地址</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
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
                    <span>提示</span>
                </div>
                <div class="textarea_content">
                    <p>
                        <img src="{{ asset('img/warning.png') }}">
                        <span>确定要删除此地址？</span>
                    </p>
                </div>
            </div>
            <div class="btn_area">
                <a class="cancel">取消</a>
                <a class="success">确定</a>
            </div>
        </div>
    </div>
    <!--新建收获地址弹出层-->
    <div class="dialog_popup new_receipt_address">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>新建地址</span>
                </div>
                <div class="textarea_content">
                    <form method="POST" action="{{ route('user_addresses.create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <ul>
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>收货人：</span>
                                    <input class="user_name" type="text" required placeholder="输入收货人姓名">
                                </p>
                                <p>
                                    <span class="input_name"><i>*</i>手机号码：</span>
                                    <input class="user_tel" type="text" required placeholder="输入真实有效的手机号">
                                </p>
                            </li>
                            <li>
                                <span class="input_name"><i>*</i>详细地址：</span>
                                <textarea required placeholder="详细地址，街道、门牌号等"></textarea>
                            </li>
                            <li>
                                <p class="default_address_set">
                                    <label>
                                        <input type="checkbox" class="setas_default">
                                        <span>设为默认地址</span>
                                    </label>
                                </p>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div class="btn_area">
                <a class="cancel">取消</a>
                <a class="success">确定</a>
            </div>
        </div>
    </div>
    <!--编辑收获地址弹出层-->
    <div class="dialog_popup edit_harvest_address">
        <div class="dialog_content">
            <div class="close">
                <i></i>
            </div>
            <div class="dialog_textarea">
                <div class="textarea_title">
                    <span>编辑收获地址</span>
                </div>
                <div class="textarea_content">
                    <!--这个表单的userAddress值是不确定的，需要根据点击的按钮对应的那一行数据的值-->
                    <form method="POST" action="{{ route('user_addresses.edit',['userAddress'=>1]) }}"
                          enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <ul>
                            <li>
                                <p>
                                    <span class="input_name"><i>*</i>收货人：</span>
                                    <input class="user_name" type="text" required placeholder="输入收货人姓名">
                                </p>
                                <p>
                                    <span class="input_name"><i>*</i>手机号码：</span>
                                    <input class="user_tel" type="text" required placeholder="输入真实有效的手机号">
                                </p>
                            </li>
                            <li>
                                <span class="input_name"><i>*</i>详细地址：</span>
                                <textarea required placeholder="详细地址，街道、门牌号等"></textarea>
                            </li>
                            <li>
                                <p class="default_address_set">
                                    <label>
                                        <input type="checkbox" class="setas_default">
                                        <span>设为默认地址</span>
                                    </label>
                                </p>
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
            <div class="btn_area">
                <a class="cancel">取消</a>
                <a class="success">确定</a>
            </div>
        </div>
    </div>
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            $(".navigation_left ul li").removeClass("active");
            $(".user_address").addClass("active");
            //点击新建收获地址
            $(".new_address").on("click", function () {
                $(".new_receipt_address").show();
            });
            //点击表格中的删除
            $(".address_list table").on("click", ".delete_address", function () {
                $(".confirm_delete").show();
            });
            //点击表格中的编辑
            $(".address_list table").on("click", ".edit_address", function () {
                $(".edit_harvest_address").show();
            });
            //点击表格中的设为默认按钮
            $(".address_list table").on("click", ".setDefaultAddress", function () {
                if ($(this).hasClass('haddefault')) {
                } else {
                    $(".address_list table").find(".setDefaultAddress").removeClass("haddefault");
                    $(".address_list table").find(".setDefaultAddress").html("设为默认地址");
                    $(this).addClass('haddefault');
                    $(this).html("默认地址")
                }
            });
        });
    </script>
@endsection
