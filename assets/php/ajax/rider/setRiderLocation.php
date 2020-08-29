<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Loc_LNG']) && isset($_POST['Loc_LAT']) && isset($_POST['Email'])) {
        $response = $Aider->getUserModal()->getRiderModal()->updateRiderLocation($_POST['Loc_LNG'], $_POST['Loc_LAT'], $_POST['Email']);

        if (!$response['error']) {
            echo "NO-ERROR";
        } else {
            echo $response['message'];
        }
    }