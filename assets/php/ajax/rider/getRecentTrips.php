<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['User_Email']) && isset($_POST['Trip_Type'])) {
        $response = $Aider->getUserModal()->getOrderModal()->getRecentTrips($_POST['User_Email'], $_POST['Trip_Type']);

        echo $response['data'];
    }