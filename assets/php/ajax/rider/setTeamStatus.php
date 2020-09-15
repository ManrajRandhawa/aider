<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['ID']) && isset($_POST['Status'])) {
        $response = $Aider->getUserModal()->getRiderModal()->setTeamStatus($_POST['ID'], $_POST['Status']);

        if (!$response['error']) {
            echo "NO-ERROR";
        } else {
            echo $response['message'];
        }
    }