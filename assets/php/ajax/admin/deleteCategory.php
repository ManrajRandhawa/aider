<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Type']) && isset($_POST['ID'])) {
        $response = $Aider->getUserModal()->getFoodModal()->deleteCategory($_POST['Type'], $_POST['ID']);
        if(!$response['error']) {
            echo "OK";
        } else {
            echo $response['message'];
        }
    }