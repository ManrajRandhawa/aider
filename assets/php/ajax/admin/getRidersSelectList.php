<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Rider_Type'])) {
        $response = $Aider->getUserModal()->getRiderModal()->getRidersSelectList($_POST['Rider_Type']);
        echo $response['data'];
    }