<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['ID']) && isset($_POST['Key']) && isset($_POST['Value'])) {
        $response = $Aider->getUserModal()->getCustomerModal()->updateCustomerInformationByID($_POST['ID'], $_POST['Key'], $_POST['Value'], true);
        if (!$response['error']) {
            echo "FALSE";
        } else {
            echo "TRUE";
        }
    }