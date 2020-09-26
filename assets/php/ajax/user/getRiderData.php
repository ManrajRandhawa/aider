<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Rider_ID']) && $_POST['Data']) {
        $response = $Aider->getUserModal()->getRiderModal()->getRiderInformationByID($_POST['Rider_ID'], $_POST['Data']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo "ERROR";
        }
    }