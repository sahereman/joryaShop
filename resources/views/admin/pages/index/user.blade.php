<canvas id="userChart" height="200"></canvas>

<script>
    $(function () {
        var ctx = document.getElementById("userChart").getContext('2d');
        var labels = [];
        var counts = [];

        @foreach($user_counts as $date => $count)
        labels.push("{{$date}}");
        counts.push("{{$count}}");
        @endforeach

            userChart = new Chart(ctx, {
            type: 'horizontalBar',
            data: {
                datasets: [{
                    label: '今日新增用户',
                    backgroundColor: 'rgba(75, 192, 192, 0.3)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    data: counts,
                    borderWidth: 1
                }],
                labels: labels,

            },
            options: {
                scales: {
                    xAxes: [{
                        ticks: {
                            suggestedMax: 10
                        }
                    }]
                }
            }
        });
    });
</script>