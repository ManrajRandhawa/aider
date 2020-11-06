<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Order_Type']) && isset($_POST['Order_ID']) && isset($_POST['Rating'])) {
        $response = $Aider->getUserModal()->getOrderModal()->addRatingToOrder($_POST['Order_Type'], $_POST['Order_ID'], $_POST['Rating']);

        if($response['error']) {
            echo $response['message'];
        } else {
            echo "NO-ERROR";
        }
    }