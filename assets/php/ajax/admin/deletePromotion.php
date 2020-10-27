<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Promo_ID'])) {
        $response = $Aider->getUserModal()->getPromoModal()->deletePromo($_POST['Promo_ID']);
        if (!$response['error']) {
            echo "OK";
        } else {
            echo "ERROR";
        }
    }