<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Ride_Type']) && isset($_POST['Ride_ID'])) {
        $response = $Aider->getUserModal()->getAdminModal()->cancelRide($_POST['Ride_Type'], $_POST['Ride_ID']);

        if($response["error"]) {
            echo "ERROR";
        }
    }

