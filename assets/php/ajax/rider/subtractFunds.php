<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    if(isset($_POST['Customer_ID']) && isset($_POST['Price_Amount'])) {
        $Aider = new Aider();

        $responseEmail = $Aider->getUserModal()->getCustomerModal()->getCustomerInformationByID($_POST['Customer_ID'], "Email_Address");

        if(!$responseEmail['error']) {
            // Subtract Funds
            $responseFunds = $Aider->getUserModal()->getAiderDriverModal()->checkAndSubtractFunds($responseEmail['data'], $_POST['Price_Amount']);

            if($responseFunds['error']) {
                $response['error'] = true;
                $response['message'] = $responseFunds['message'];
            } else {
                $response['error'] = false;
            }
        } else {
            $response['error'] = true;
            $response['message'] = $responseEmail['message'];
        }

        if(!$response['error']) {
            echo "NO-ERROR";
        } else {
            echo $response['message'];
        }

    }