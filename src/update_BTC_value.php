<?php

require_once "db_handler.php";

$db = new DbHandler();

$BTCvalue = $db->getBTCValue();

$db->updateDatabase($BTCvalue);