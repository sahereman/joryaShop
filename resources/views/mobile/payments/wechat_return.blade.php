@extends('layouts.app')
@section('title', App::isLocale('en') ? 'Payment success' : '支付成功')
@section('content')



@endsection
@section('scriptsAfterJs')
    <script type="text/javascript">
        $(function () {
            layer.open({
                type: 2
                , content: '加载中'
            });


            function _fresh() {
                $.ajax({
                    type: "get",
                    url: "{{ route('orders.is_paid', ['order' => $order->id]) }}",
                    success: function (json) {
                        console.log(json);
                        if (json.code == 200) {
                            // clearInterval(sh);
                            window.location.href = "{{ route('mobile.payments.success', ['order' => $order->id]) }}";
                            // window.location.href = $(".text").attr("data-url");
                        }
                    }
                });
            }

            _fresh();
            var sh = setInterval(_fresh, 2000);
        });
    </script>
@endsection
