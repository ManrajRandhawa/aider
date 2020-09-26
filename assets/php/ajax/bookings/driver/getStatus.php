<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Order_ID']) && isset($_POST['Order_Info'])) {
        $response = $Aider->getUserModal()->getOrderModal()->getOrderDetailsByID("DRIVER", $_POST['Order_ID'], $_POST['Order_Info']);

        if($response['error']) {
            echo "ERROR";
        } else {
            echo $response['data'];
        }
    }