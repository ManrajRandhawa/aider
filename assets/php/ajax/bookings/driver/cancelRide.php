<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['RideID'])) {

        $responseDeliver = $Aider->getUserModal()->getAiderDriverModal()->cancelRide($_POST['RideID']);

        if($responseDeliver['error']) {
            echo "ERROR";
        } else {
            echo "NO-ERROR";
        }
    }