<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Address'])) {
        $address = $Aider->getUserModal()->getOrderModal()->getCoordinates($_POST['Address']);

        echo $address['lat'] . "," . $address['lng'];
    }