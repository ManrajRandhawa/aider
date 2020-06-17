<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Email']) && isset($_POST['Pickup_Location']) && isset($_POST['Dropoff_Location']) && isset($_POST['Pickup_Details_Name']) && isset($_POST['Pickup_Details_PhoneNum']) && isset($_POST['Dropoff_Details_Name']) && isset($_POST['Dropoff_Details_PhoneNum']) && isset($_POST['Price'])) {

        $pickUpDate = date("Y-m-d H:i:s");

        $responseDeliver = $Aider->getUserModal()->getParcelModal()->deliver($_POST['Email'], $_POST['Pickup_Location'],$_POST['Dropoff_Location'], $_POST['Pickup_Details_Name'], $_POST['Pickup_Details_PhoneNum'], $_POST['Dropoff_Details_Name'], $_POST['Dropoff_Details_PhoneNum'], $pickUpDate, $_POST['Price']);

        if($responseDeliver['error']) {
            echo $responseDeliver['message'];
        } else {
            echo "NO-ERROR";
        }
    }