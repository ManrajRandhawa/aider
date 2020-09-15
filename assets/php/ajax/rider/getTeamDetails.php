<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Team_ID']) && isset($_POST['Team_Data'])) {
        $response = $Aider->getUserModal()->getRiderModal()->getTeamInformationByID($_POST['Team_ID'], $_POST['Team_Data']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo "ERROR";
        }
    }