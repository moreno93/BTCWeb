<?php

require_once "../src/btc.php";

$btc = new BTC();

try{
    $data = $btc->getGraphData();
    echo json_encode($data);
} catch (Exception $e){
    $exception = $e->getMessage();
}

