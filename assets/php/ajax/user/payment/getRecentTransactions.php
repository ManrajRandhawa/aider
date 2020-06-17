<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_ID'])) {
        $response = $Aider->getUserModal()->getCustomerModal()->getRecentTransactions($_POST['User_ID']);

        if (!$response['error']) {
            echo $response['data'];
        }
    }