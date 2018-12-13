<canvas id="orderChart" height="200"></canvas>
<script>
    $(function () {
        var ctx = document.getElementById("orderChart").getContext('2d');
        var labels = [];
        var paying_counts = [];
        var shiping_counts = [];
        var receiving_counts = [];
        var refunding_counts = [];

        @foreach($paying_counts as $date => $count)
        labels.unshift("{{$date}}");
        paying_counts.unshift("{{$count}}");
        @endforeach

        @foreach($shiping_counts as $count)
        shiping_counts.unshift("{{$count}}");
        @endforeach

        @foreach($receiving_counts as $count)
        receiving_counts.unshift("{{$count}}");
        @endforeach

        @foreach($refunding_counts as $count)
        refunding_counts.unshift("{{$count}}");
        @endforeach


            orderChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '待付款订单',
                    backgroundColor: 'rgba(255, 99, 132, 0.3)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    data: paying_counts,
                    fill: false,
                },{
                    label: '待发货订单',
                    backgroundColor: 'rgba(54, 162, 235, 0.3)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    data: shiping_counts,
                    fill: false,
                },{
                    label: '待收货订单',
                    backgroundColor: 'rgba(75, 192, 192, 0.3)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    data: receiving_counts,
                    fill: false,
                },{
                    label: '售后订单',
                    backgroundColor: 'rgba(255, 206, 86, 0.3)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    data: refunding_counts,
                    fill: false,
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            suggestedMax: 8
                        }
                    }]
                }
            }
        });
    });
</script>