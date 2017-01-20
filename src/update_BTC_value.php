<?php

require_once "btc.php";

$btc = new BTC();

$BTCvalue = $btc->getBTCValue();

try{
    $btc->updateDatabase($BTCvalue);
} catch (Exception $e){
    echo $e->getMessage();
}
