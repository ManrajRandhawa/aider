<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Team_ID'])) {
        $response = $Aider->getUserModal()->getRiderModal()->getTeamDetailsForEditing($_POST['Team_ID']);

        if (!$response['error']) {
            echo json_encode($response['data'], JSON_PRETTY_PRINT);
        } else {
            echo "ERROR";
        }
    }