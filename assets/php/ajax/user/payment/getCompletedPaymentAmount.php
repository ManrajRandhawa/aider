<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_Email']) && isset($_POST['Ref'])) {
        $response = $Aider->getUserModal()->getBillModal()->getBillInformationByReference($_POST['User_Email'], $_POST['Ref'], 'Bill_Amount');

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo "ERROR";
        }
    }