<?php


class ParcelModal {

    function deliver($customerEmail, $Pickup_Location, $Dropoff_Location, $Pickup_Details_Name, $Pickup_Details_PhoneNum, $Dropoff_Details_Name, $Dropoff_Details_PhoneNum, $Pickup_Datetime, $Price) {

        $Aider = new Aider();
        $responseCustomerID = $Aider->getUserModal()->getCustomerModal()->getCustomerInformationByEmail($customerEmail, 'ID');

        if($responseCustomerID['error']) {
            $response['error'] = true;
            $response['message'] = "The email used for this account is not registered with us. Try re-logging.";
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $priceAmount = implode($Price);

            // Subtract Funds
            $responseFunds = $this->checkAndSubtractFunds($customerEmail, $priceAmount);

            if($responseFunds['error']) {
                $response['error'] = true;
                $response['message'] = $responseFunds['message'];
            } else {
                $sql = "INSERT INTO aider_transaction_parcel(Customer_ID, Pickup_Location, Dropoff_Location, Pickup_Details_Name, Pickup_Details_PhoneNum, Dropoff_Details_Name, Dropoff_Details_PhoneNum, Pickup_Datetime, Price, Transaction_Datetime, Status)" .
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $transactionDate = date("Y-m-d H:i:s");
                $status = "FINDING-RIDER";

                $statement = $connection->prepare($sql);
                $statement->bind_param("isssssssdss",$responseCustomerID['data'], $Pickup_Location, $Dropoff_Location, $Pickup_Details_Name, $Pickup_Details_PhoneNum, $Dropoff_Details_Name, $Dropoff_Details_PhoneNum, $Pickup_Datetime, $priceAmount, $transactionDate, $status);

                if($statement->execute()) {
                    $response['error'] = false;
                } else {
                    $response['error'] = true;
                    $response['message'] = "There was an error while setting up the delivery.";
                }
                $statement->close();
                $connection->close();
            }
        }
        return $response;
    }

    private function checkAndSubtractFunds($Email, $Amount) {
        $Aider = new Aider();
        $responseCustomerModal = $Aider->getUserModal()->getCustomerModal()->getCustomerInformationByEmail($Email, "Credit");

        if($responseCustomerModal['error']) {
            $response['error'] = true;
            $response['message'] = "There was an error wile trying to fetch your wallet.";
        } else {
            if($responseCustomerModal['data'] < $Amount) {
                $response['error'] = true;
                $response['message'] = "You've insufficient funds. Top up to continue." . " - " . $Amount;
            } else {
                if(is_numeric($responseCustomerModal['data'])) {
                    $newPrice = ((double)$responseCustomerModal['data'] - (double)$Amount);
                    $responseUpdateWallet = $Aider->getUserModal()->getCustomerModal()->updateCustomerInformationByEmail($Email, "Credit", $newPrice);

                    if($responseUpdateWallet['error']) {
                        $response['error'] = true;
                        $response['message'] = "There was an issue while trying to update your wallet.";
                    } else {
                        $response['error'] = false;
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "There was an internal error with value exchanging.";
                }


            }
        }

        return $response;
    }

}