<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Status']) && isset($_POST['T_Type']) && isset($_POST['T_ID']) && isset($_POST['Email'])) {
        $response = $Aider->getUserModal()->getRiderModal()->updateRiderStatus($_POST['Status'], $_POST['T_Type'], $_POST['T_ID'], $_POST['Email']);

        if (!$response['error']) {
            echo "NO-ERROR";
        } else {
            echo $response['message'];
        }
    }