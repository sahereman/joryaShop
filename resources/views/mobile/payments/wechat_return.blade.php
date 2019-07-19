@extends('layouts.mobile')
@section('title', (App::isLocale('zh-CN') ? '支付成功' : 'Payment Success') . ' - ' . \App\Models\Config::config('title'))
@section('content')
@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            layer.open({
                shadeClose: false,
                shade: 'background-color: rgba(0,0,0,0.4)', //自定义遮罩的透明度
                type: 2,
                success: function (elem) {
                    _fresh();
                    var sh = setInterval(_fresh, 2000);
                }
            });
            function _fresh() {
                $.ajax({
                    type: "get",
                    url: "{{ route('payments.is_completed', ['payment' => $payment->id]) }}",
                    success: function (json) {
                        console.log(json);
                        if (json.code == 200) {
                            window.location.href = "{{ route('mobile.payments.success', ['payment' => $payment->id]) }}";
                        }
                    }
                });
            }
        });
    </script>
@endsection
