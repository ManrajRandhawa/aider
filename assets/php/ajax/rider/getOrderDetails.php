<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Order_Type']) && isset($_POST['Order_ID'])) {
        $response = $Aider->getUserModal()->getOrderModal()->getOrderDetails($_POST['Order_Type'], $_POST['Order_ID']);

        if (!$response['error']) {
            echo json_encode($response['data'], JSON_PRETTY_PRINT);
        } else {
            echo "ERROR";
        }
    }