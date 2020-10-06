<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_Email']) && isset($_POST['Trip_Type']) && isset($_POST['Trip_ID'])) {
        $response = $Aider->getUserModal()->getRiderModal()->getTripCut($_POST['User_Email'], $_POST['Trip_Type'], $_POST['Trip_ID']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo $response['message'];
        }
    }