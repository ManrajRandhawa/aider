<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Team_Name']) && isset($_POST['Team_Members'])) {
        $response = $Aider->getUserModal()->getRiderModal()->createTeam($_POST['Team_Name'], $_POST['Team_Members']);
        if(!$response['error']) {
            echo "OK";
        } else {
            echo "ERROR";
        }
    }