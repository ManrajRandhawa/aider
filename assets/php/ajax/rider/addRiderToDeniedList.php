<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Order_Type']) && isset($_POST['Order_ID']) && isset($_POST['Rider_ID'])) {
        $response = $Aider->getUserModal()->getOrderModal()->addRiderToDeniedList($_POST['Order_Type'], $_POST['Order_ID'], $_POST['Rider_ID']);
        $responseSort = $Aider->getUserModal()->getOrderModal()->addRiderToSortingDeniedList($_POST['Order_Type'], $_POST['Order_ID'], $_POST['Rider_ID']);

        if(!$response['error'] && !$responseSort['error']) {
            echo "FALSE";
        } else {
            echo "TRUE";
        }
    }