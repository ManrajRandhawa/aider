<?php
    error_reporting(0);

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    $response = $Aider->getUserModal()->getAdminModal()->getCancellableRides($_POST['User_Search']);
    echo $response['data'];