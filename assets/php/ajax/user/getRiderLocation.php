<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Rider_ID'])) {
        $responseLNG = $Aider->getUserModal()->getRiderModal()->getRiderInformationByID($_POST['Rider_ID'], 'Loc_LNG');
        $responseLAT = $Aider->getUserModal()->getRiderModal()->getRiderInformationByID($_POST['Rider_ID'], 'Loc_LAT');

        if (!$responseLNG['error'] && !$responseLAT['error']) {
            echo $responseLAT['data'] . "," . $responseLNG['data'];
        } else {
            echo "ERROR";
        }
    }