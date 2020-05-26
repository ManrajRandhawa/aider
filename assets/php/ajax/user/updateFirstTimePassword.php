<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_Email']) && isset($_POST['Password']) && isset($_POST['Confirm_Password'])) {
        $response = $Aider->getUserModal()->getCustomerModal()->updatePassword($_POST['Password'], $_POST['Confirm_Password'], $_POST['User_Email']);

        echo $response['reason'];
    }