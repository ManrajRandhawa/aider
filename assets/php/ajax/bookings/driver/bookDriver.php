<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Email']) && isset($_POST['Pickup_Location']) && isset($_POST['Dropoff_Location']) && isset($_POST['Price'])) {

        $responseDeliver = $Aider->getUserModal()->getAiderDriverModal()->book($_POST['Email'], $_POST['Pickup_Location'],$_POST['Dropoff_Location'], $_POST['Price']);

        if($responseDeliver['error']) {
            echo $responseDeliver['message'];
        } else {
            echo "NO-ERROR";
        }
    }