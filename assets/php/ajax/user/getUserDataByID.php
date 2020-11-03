<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_ID']) && isset($_POST['User_Info'])) {
        $response = $Aider->getUserModal()->getCustomerModal()->getCustomerInformationByID($_POST['User_ID'], $_POST['User_Info']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo $response['message'];
        }
    }