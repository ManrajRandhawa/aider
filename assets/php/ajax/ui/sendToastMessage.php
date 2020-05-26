<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Title']) && isset($_POST['Message'])) {
        echo $Aider->getAlerts()->sendToastDashboard($_POST['Title'], $_POST['Message']);
    }