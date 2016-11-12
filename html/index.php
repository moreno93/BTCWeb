<!DOCTYPE html>

<?php
error_reporting(-1);
ini_set('display_errors', 'On');
?>

<html>
<head>
    <title>Bitcoins</title>
    <meta charset="UTF-8">
    <meta lang="EN">

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.min.js"></script>
</head>

<body>
<div id="current_value">

</div>

<div id="graph">
    <canvas id="BTCchart" width="800" height="350"></canvas>
</div>

<script>
    $(document).ready(function(){
        $.ajax({
            url: "get_graph_data.php",
            method: "GET",
            success: function(result) {
                var data = jQuery.parseJSON(result);
                var value = [];
                var timestamp = [];

                for(var i=0; i<data.length; i++) {
                    value.push(data[i][0]);
                    timestamp.push(data[i][1]);
                }

                var chartdata = {
                    labels: timestamp,
                    datasets : [
                        {
                            label: 'BTC value',
                            fill: false,
                            lineTension: 0.5,
                            backgroundColor: "rgba(75,192,192,0.4)",
                            borderColor: "rgba(75,192,192,1)",
                            borderCapStyle: 'butt',
                            borderDash: [],
                            borderDashOffset: 0.0,
                            borderJoinStyle: 'miter',
                            pointBorderColor: "rgba(75,192,192,1)",
                            pointBackgroundColor: "#fff",
                            pointBorderWidth: 1,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(75,192,192,1)",
                            pointHoverBorderColor: "rgba(220,220,220,1)",
                            pointHoverBorderWidth: 2,
                            pointRadius: 1,
                            pointHitRadius: 10,
                            data: value
                        }
                    ]
                };

                var ctx = $("#BTCchart");

                var BTCGraph = new Chart(ctx, {
                    type: 'line',
                    data: chartdata,
                    options:{
                        scales:{
                            xAxes:[{
                                display: false
                            }]
                        }
                    }
                });
            }
        });
    });

</script>

</body>

</html>
