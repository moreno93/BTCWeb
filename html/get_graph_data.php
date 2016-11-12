<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once "../src/db_handler.php";

$db = new DbHandler();

$data = $db->getGraphData();

echo json_encode($data);

