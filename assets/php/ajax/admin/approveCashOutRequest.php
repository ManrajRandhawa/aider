<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_Email'])) {
        $response = $Aider->getUserModal()->getAdminModal()->approveCashOutRequest($_POST['User_Email']);

        if($response['error']) {
            echo "ERROR";
        } else {
            echo "NO-ERROR";
        }
    }
