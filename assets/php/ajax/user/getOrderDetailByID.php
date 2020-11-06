<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Order_Type']) && isset($_POST['Order_ID']) && isset($_POST['Data'])) {
        $response = $Aider->getUserModal()->getOrderModal()->getOrderDetailsByID($_POST['Order_Type'], $_POST['Order_ID'], $_POST['Data']);

        if($response['error']) {
            echo "ERROR";
        } else {
            echo $response['data'];
        }
    }