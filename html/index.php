<!DOCTYPE html>

<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require "../src/user.php";

session_start();

if (isset($_SESSION['user_id'])){
    $user = new User($_SESSION['user_id'], null, null);
    try {
        $currentUser = $user->getUserById($_SESSION['user_id']);
    } catch (Exception $e){
        $exception = $e->getMessage();
    }
}
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
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">
                <img alt="Logo" src="img/logo.svg" width="50" height="50">
            </a>
        </div>
        <?php
        if(isset($currentUser)){
            ?>
            <form class="navbar-form navbar-right">
                <div class="form-group">
                    <p class="navbar-text">Welcome <?php echo $currentUser['firstName'] . " " . $currentUser['lastName']; ?> </p>
                    <a class="btn btn-default" href="logout.php?user_id=<?php echo $currentUser['id']; ?>">Logout</a>
                </div>
            </form>

            <?php
        } else {
            ?>
            <form class="navbar-form navbar-right" method="POST" action="login.php">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email" name="email">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                </div>
                <button type="submit" class="btn btn-default">Login</button>
                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#registerModal">Register</button>
            </form>
            <?php
        }
        ?>
    </div>

</nav>
<?php
    if(isset($_GET['m']) || isset($exception)){
        if(isset($_GET['m'])) $message = $_GET['m'];
        if(isset($exception)) $message = $exception;
        ?>
        <div class="contanier">
            <div id="message">
                <div class="alert alert-info alert-dismissable">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <strong><?php echo $message; ?></strong>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
<div class="container">
    <div id="current_value">
        <p>Current value of BTC is <strong><span id="BTC_value"> </span> USD</strong></p>
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
            <form method='POST' action='register.php'>
                <div class="modal-body">
                    <fieldset class="form-group">
                        <input type="text" class="form-control" placeholder="First Name" name="firstName">
                    </fieldset>
                    <fieldset class="form-group">
                        <input type="text" class="form-control" placeholder="Last Name" name="lastName">
                    </fieldset>
                    <fieldset class="form-group">
                        <input type="text" class="form-control" placeholder="Email" name="email">
                    </fieldset>
                    <fieldset class="form-group">
                        <input type="password" class="form-control" placeholder="Password" name="password">
                    </fieldset>
                    <fieldset class="form-group">
                        <input type="password" class="form-control" placeholder="Repeat Password" name="passwordRepeat">
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
        <p class="text-muted">Created by Moreno Mušković, Matko Smoljan</p>
    </div>
</footer>



<script>
    $(document).ready(function(){
        function getCurrentBTCValue(){
            $.ajax({
               url: "get_current_BTC_value.php",
                method: "GET",
                success: function(data){
                    $('#BTC_value').html(data);
                }
            });
            setTimeout(getCurrentBTCValue, 60000);
        }
        function updateGraph() {
            $.ajax({
                url: "get_graph_data.php",
                method: "GET",
                success: function (result) {
                    var data = jQuery.parseJSON(result);
                    var value = [];
                    var timestamp = [];

                    for (var i = 0; i < data.length; i++) {
                        value.push(data[i][0]);
                        timestamp.push(data[i][1]);
                    }

                    var chartdata = {
                        labels: timestamp,
                        datasets: [
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
                        options: {
                            scales: {
                                xAxes: [{
                                    display: false
                                }]
                            }
                        }
                    });
                }
            });
            setTimeout(updateGraph, 60000);
        }
        getCurrentBTCValue();
        updateGraph();
    });

</script>

</body>

</html>
