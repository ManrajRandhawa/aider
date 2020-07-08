<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_Email']) && isset($_POST['V_Model']) && isset($_POST['V_Plate_Num'])) {
        $response = $Aider->getUserModal()->getRiderModal()->approveRider($_POST['User_Email'], $_POST['V_Model'], $_POST['V_Plate_Num']);
        echo $response['message'];
    }
