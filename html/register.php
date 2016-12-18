<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require_once "../src/user.php";

$user = new User(null, $_POST['email'], $_POST['password']);

$result = $user->register($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'], $_POST['passwordRepeat']);

$user->redirect('index.php');
