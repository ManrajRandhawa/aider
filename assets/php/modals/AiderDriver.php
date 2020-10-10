<?php


class AiderDriver {

    function book($customerEmail, $Pickup_Location, $Dropoff_Location, $Price) {

        $Aider = new Aider();
        $responseCustomerID = $Aider->getUserModal()->getCustomerModal()->getCustomerInformationByEmail($customerEmail, 'ID');

        if($responseCustomerID['error']) {
            $response['error'] = true;
            $response['message'] = "The email used for this account is not registered with us. Try re-logging.";
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $responseCheckFunds = $this->checkFunds($customerEmail, doubleval($Price));

            if(!$responseCheckFunds['error']) {
                $priceAmount = implode($Price);

                $insert_id = null;
                $t_type = "DRIVER";

                // [START] Add to Delivery Listing
                $sql = "INSERT INTO aider_transaction_driver(Customer_ID, Pickup_Location, Dropoff_Location, Price, Transaction_Datetime, Status)" .
                    "VALUES (?, ?, ?, ?, ?, ?)";

                $transactionDate = date("Y-m-d H:i:s");
                $status = "FINDING-RIDER";

                $statement = $connection->prepare($sql);
                $statement->bind_param("issdss",$responseCustomerID['data'], $Pickup_Location, $Dropoff_Location, $priceAmount, $transactionDate, $status);

                if($statement->execute()) {
                    $response['error'] = false;

                    $insert_id = $connection->insert_id;
                } else {
                    $response['error'] = true;
                    $response['message'] = "There was an error while setting up the delivery.";
                }
                $statement->close();
                // [END] Add to Delivery Listing

                if(!$response['error']) {
                    // [START] Add to Delivery Sorting
                    $sqlSort = "INSERT INTO aider_transaction_sorting(Transaction_ID, Transaction_Type, Customer_ID, Transaction_Datetime, Transaction_Status) VALUES (?, ?, ?, ?, ?)";

                    $statementSort = $connection->prepare($sqlSort);
                    $statementSort->bind_param("isiss", $insert_id, $t_type, $responseCustomerID['data'], $transactionDate, $status);

                    if($statementSort->execute()) {
                        $response['error'] = false;
                        $response['message'] = $insert_id;
                    } else {
                        $response['error'] = true;
                        $response['message'] = "There was an error while pushing the data to the sorting service: " . $statementSort->error;
                    }
                    $statementSort->close();
                    // [END] Add to Delivery Sorting
                }
            } else {
                $response['error'] = true;
                $response['message'] = $responseCheckFunds['message'];
            }

            $connection->close();
        }
        return $response;
    }

    function cancelRide($rideID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sqlDriver = "UPDATE aider_transaction_driver SET Status='CANCELLED_BY_CUSTOMER' WHERE ID=" . intval($rideID);
        $sqlSorting = "UPDATE aider_transaction_sorting SET Transaction_Status='CANCELLED_BY_CUSTOMER' WHERE Transaction_Type='DRIVER' AND Transaction_ID=" . intval($rideID);

        $queryDriver = $connection->query($sqlDriver);
        $querySorting = $connection->query($sqlSorting);

        if($queryDriver && $querySorting) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
        }

        return $response;
    }

    function checkAndSubtractFunds($Email, $Amount) {
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

    function checkFunds($Email, $Amount) {
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
                $response['error'] = false;
            }
        }

        return $response;
    }

}