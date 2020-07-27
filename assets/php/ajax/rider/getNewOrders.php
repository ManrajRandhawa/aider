<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Rider_ID'])) {
        $response = $Aider->getUserModal()->getOrderModal()->getParcelOrdersThatRequireARider($_POST['Rider_ID']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo "ERROR";
        }
    }