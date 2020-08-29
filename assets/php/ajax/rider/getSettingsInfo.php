<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Info'])) {
        $response = $Aider->getUserModal()->getAdminModal()->getSettingsInformation($_POST['Info']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo $response['message'];
        }
    }