<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Status']) && isset($_POST['T_Type']) && isset($_POST['T_ID']) && isset($_POST['ID'])) {
        $response = $Aider->getUserModal()->getRiderModal()->updateRiderStatusByID($_POST['Status'], $_POST['T_Type'], $_POST['T_ID'], $_POST['ID']);

        if (!$response['error']) {
            echo "NO-ERROR";
        } else {
            echo $response['message'];
        }
    }