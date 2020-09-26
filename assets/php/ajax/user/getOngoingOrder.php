<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_Email'])) {
        $response = $Aider->getUserModal()->getCustomerModal()->getOngoingOrder($_POST['User_Email']);

        if (!$response['error']) {
            $data = json_encode(array($response['count'], array($response['data'])));
            echo $data;
        } else {
            echo "ERROR";
        }
    }