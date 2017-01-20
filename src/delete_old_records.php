<?php

require_once "btc.php";

$btc = new BTC();

try{
    $btc->deleteOldDbRecords();
} catch (Exception $e){
    echo $e->getMessage();
}


