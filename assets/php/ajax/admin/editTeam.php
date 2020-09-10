<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Team_ID']) && isset($_POST['Team_Name']) && isset($_POST['Team_Member_One']) && isset($_POST['Team_Member_Two'])) {
        $response = $Aider->getUserModal()->getRiderModal()->editTeam($_POST['Team_ID'], $_POST['Team_Name'], $_POST['Team_Member_One'], $_POST['Team_Member_Two']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo "ERROR";
        }
    }