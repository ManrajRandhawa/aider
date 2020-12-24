<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    $response = $Aider->getUserModal()->getFoodModal()->getCategoryList($_POST['Type']);
    echo $response['data'];