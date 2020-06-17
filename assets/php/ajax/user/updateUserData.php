<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_Email']) && isset($_POST['Key']) && isset($_POST['Value'])) {
        $response = $Aider->getUserModal()->getCustomerModal()->updateCustomerInformationByEmail($_POST['User_Email'], $_POST['Key'], $_POST['Value']);
        if (!$response['error']) {
            echo "FALSE";
        } else {
            echo "TRUE";
        }
    }