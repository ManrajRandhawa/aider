<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Type']) && isset($_POST['Name'])) {
        $response = $Aider->getUserModal()->getFoodModal()->addCategory($_POST['Type'], $_POST['Name']);
        if(!$response['error']) {
            echo "OK";
        } else {
            echo $response['message'];
        }
    }