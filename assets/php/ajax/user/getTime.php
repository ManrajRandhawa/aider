<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['riderLat']) && isset($_POST['riderLng']) && isset($_POST['addressTwo'])) {
        $coordsAddTwo = $Aider->getUserModal()->getOrderModal()->getCoordinates($_POST['addressTwo']);

        $distance = $Aider->getUserModal()->getOrderModal()->getDrivingDistance($_POST['riderLat'], $coordsAddTwo['lat'], $_POST['riderLng'], $coordsAddTwo['lng']);

        echo $distance['time'];
    }