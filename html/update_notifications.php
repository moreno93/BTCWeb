<?php
require_once "../src/user.php";

$user_id = $_POST['id'];
$percentage = trim($_POST['percentage'], "%");

$user = new User($user_id, null, null);

try{
    $user->updateNotifications($user_id, $percentage);
    $user->redirect('index.php');
} catch (Exception $e){
    $user->redirectWithMessage('index.php', $e->getMessage());
}
