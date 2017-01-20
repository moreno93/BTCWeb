<?php

require_once "../src/btc.php";

$btc = new BTC();

$value = $btc->getBTCValue();

echo $value;
