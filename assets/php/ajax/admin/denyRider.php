<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_Email'])) {
        $response = $Aider->getUserModal()->getRiderModal()->denyRider($_POST['User_Email']);
        echo $response['message'];
    }
