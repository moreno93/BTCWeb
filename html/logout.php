<?php
session_start();
error_reporting(-1);
ini_set('display_errors', 'On');

require_once "../src/user.php";

$user = new User($_GET['user_id'], null, null);
$user->logout();
$user->redirect('index.php');
