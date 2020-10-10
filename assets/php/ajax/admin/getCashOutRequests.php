<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    $response = $Aider->getUserModal()->getAdminModal()->getCashOutRequests();
    echo $response['data'];