@extends('layouts.mobile')
@section('title', App::isLocale('zh-CN') ? '切换语言' : 'switch language')
@section('content')
    <div class="headerBar">
        @if(!is_wechat_browser())
        <img src="{{ asset('static_m/img/icon_backtop.png') }}" class="backImg" onclick="javascript:history.back(-1);"/>
        <span>@lang('app.switch language')</span>
        @endif
    </div>
    <div class="langBox">
        <div class="langItem">
            <div class="langLeft">
                <img src="{{ asset('static_m/img/chinese.png') }}"/>
                <span>中文</span>
            </div>
            <div class="langRight">
                <input type="radio" name="lang" data-url="{{route('locale.update','zh-CN')}}" id="" value="1"/>
                <span></span>
            </div>
        </div>
        <div class="langItem">
            <div class="langLeft">
                <img src="{{ asset('static_m/img/English.png') }}"/>
                <span>English</span>
            </div>
            <div class="langRight">
                <input type="radio" name="lang" data-url="{{route('locale.update','en')}}" id="" value="0"/>
                <span></span>
            </div>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script type="text/javascript">
        // 页面单独JS写这里
        $(function () {
            $("input").on("click", function () {
                $.ajax({
                    type: "get",
                    url: $(this).attr("data-url"),
                    success: function () {
                        window.location.href = "{{ route('mobile.root') }}";
                    },
                });
            });
            //获取url参数
            function getUrlVars() {
                var vars = [], hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                return vars["language_type"];
            }
            var action = "";
            $(document).ready(function () {
                $("input[value=" + getUrlVars() + "]").attr("checked", true);
            });
        });
    </script>
@endsection
