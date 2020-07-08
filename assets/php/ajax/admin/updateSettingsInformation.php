<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Settings_Info']) && isset($_POST['Settings_Value']) && isset($_POST['isNumber'])) {
        $response = $Aider->getUserModal()->getAdminModal()->updateSettingsInformation($_POST['Settings_Info'], $_POST['Settings_Value'], $_POST['isNumber']);

        if($response['error']) {
            echo "YES";
        } else {
            echo "NO";
        }
    }