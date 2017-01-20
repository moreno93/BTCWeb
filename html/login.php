<?php
session_start();

require_once "../src/user.php";

$user = new User(null, $_POST['email'], $_POST['password']);

try {
    $userID = $user->login();
} catch (Exception $e){
    $user->redirectWithMessage('index.php', $e->getMessage());
}

$user->redirect('index.php');
