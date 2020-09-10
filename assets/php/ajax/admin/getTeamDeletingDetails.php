<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Team_ID'])) {
        $response = $Aider->getUserModal()->getRiderModal()->getTeamDetailsForDeleting($_POST['Team_ID']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo "ERROR";
        }
    }