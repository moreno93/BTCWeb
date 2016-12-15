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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

</head>

<body style="padding-top: 65px;">
<nav class="navbar navbar-default navbar-fixed-top">
    <form class="navbar-form navbar-right" role="login">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Email">
            <input type="text" class="form-control" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-default">Login</button>
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#registerModal">Register</button>
    </form>
</nav>
<div class="container">
    <div id="current_value">
        <p>Current value of btc</p>
    </div>

    <div id="graph">
        <canvas id="BTCchart" width="800" height="350"></canvas>
    </div>
</div>

<div class="modal fade" id='registerModal'>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Register</h4>
            </div>
            <form method='POST' action=''>
                <div class="modal-body">
                    <fieldset class="form-group">
                        <input type="text" class="form-control" placeholder="First Name">
                    </fieldset>
                    <fieldset class="form-group">
                        <input type="text" class="form-control" placeholder="Last Name">
                    </fieldset>
                    <fieldset class="form-group">
                        <input type="text" class="form-control" placeholder="Email">
                    </fieldset>
                    <fieldset class="form-group">
                        <input type="text" class="form-control" placeholder="Password">
                    </fieldset>
                    <fieldset class="form-group">
                        <input type="text" class="form-control" placeholder="Repeat Password">
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Register</button>
                    <button type="button" class="btn btn-neutral" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="footer" style="padding-top: 15px;">
    <div class="container">
        <p class="text-muted">Created by</p>
    </div>
</footer>



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
