<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    $response = $Aider->getUserModal()->getFoodModal()->searchRestaurantList($_POST['Search_Args']);
    echo $response['data'];