<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Rest_ID'])) {
        $response = $Aider->getUserModal()->getFoodModal()->deleteRestaurant($_POST['Rest_ID']);
        if(!$response['error']) {
            echo "OK";
        } else {
            echo $response['message'];
        }
    }