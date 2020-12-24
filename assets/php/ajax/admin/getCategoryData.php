<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Type']) && isset($_POST['ID']) && isset($_POST['Data'])) {
        $response = $Aider->getUserModal()->getFoodModal()->getCategoryData($_POST['Type'], $_POST['ID'], $_POST['Data']);

        if (!$response['error']) {
            echo $response['data'];
        } else {
            echo "ERROR: " . $response['message'];
        }
    }