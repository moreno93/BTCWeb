<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once "../src/btc.php";

$btc = new BTC();

$value = $btc->getBTCValue();

echo $value;
