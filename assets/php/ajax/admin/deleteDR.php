<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['ID'])) {
        $response = $Aider->getUserModal()->getAdminModal()->deleteDriverRider($_POST['ID']);

        if($response["error"]) {
            echo "ERROR";
        }
    }

