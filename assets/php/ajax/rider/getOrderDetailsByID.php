<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Order_Type']) && isset($_POST['Order_ID']) && isset($_POST['Order_Data'])) {
        $response = $Aider->getUserModal()->getOrderModal()->getOrderDetailsByID($_POST['Order_Type'], $_POST['Order_ID'], $_POST['Order_Data']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo "ERROR";
        }
    }