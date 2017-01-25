<?php

require_once "user.php";
require_once "btc.php";

$btc = new BTC();
$user = new User(null, null, null);


$currentValue = $btc->getBTCValue();
$users = $user->getUsersWithNotifications();

if (!$users) exit();

foreach ($users as $usr){
    $notificationBTC = $user->getNotificationBTCValue($usr['id']);

    $change = abs(($currentValue - $notificationBTC)/$notificationBTC)*100;


    if($change >= $usr['percentage']){
        try {
            $user->sendMail($usr['email'], $usr['firstName'], $usr['lastName'], $change, $currentValue, $notificationBTC);
            file_put_contents('mail.log', 'Successfully sent email to ' . $usr['email'] . '\r\n', FILE_APPEND);
        } catch (Exception $e){
            file_put_contents('mail.log', 'Error sending email to ' . $usr['email'] . '\r\n', FILE_APPEND);
        }

        try {
            $user->resetNotifications($usr['id']);
        } catch (Exception $e){
            echo $e->getMessage();
        }
    }
}

