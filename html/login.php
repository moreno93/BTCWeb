<?php
session_start();
error_reporting(-1);
ini_set('display_errors', 'On');

require_once "../src/user.php";

$user = new User(null, $_POST['email'], $_POST['password']);

try {
    $userID = $user->login();
} catch (Exception $e){
    $user->redirectWithMessage('index.php', $e->getMessage());
}

$user->redirect('index.php');
