<?php
session_start();

require_once "../src/user.php";

$user = new User($_GET['user_id'], null, null);
$user->logout();
$user->redirect('index.php');
