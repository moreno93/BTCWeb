<?php

require_once "btc.php";

$btc = new BTC();

$BTCvalue = $btc->getBTCValue();

$btc->deleteOldDbRecords();
