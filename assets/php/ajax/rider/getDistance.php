<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['coordsAddOneLAT']) && isset($_POST['coordsAddOneLNG']) && isset($_POST['addressTwo'])) {
        $coordsAddTwo = $Aider->getUserModal()->getOrderModal()->getCoordinates($_POST['addressTwo']);

        $distance = $Aider->getUserModal()->getOrderModal()->getDrivingDistance($_POST['coordsAddOneLAT'], $coordsAddTwo['lat'], $_POST['coordsAddOneLNG'], $coordsAddTwo['lng']);

        echo $distance['distance'];
    }