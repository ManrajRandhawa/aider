<?php


class CustomerModal {

    function getCustomerInformationByID($id, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_customer WHERE ID=$id";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {
                $response['data'] = $row[$info];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to connect to the database.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function getCustomerInformationByEmail($email, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_customer WHERE Email_Address='" . $email . "'";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {
                $response['data'] = $row[$info];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to connect to the database.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function updateCustomerInformationByEmail($email, $key, $value) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "UPDATE aider_user_customer SET $key = '$value' WHERE Email_Address='" . $email . "'";
        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to update the database.";
        }

        $connection->close();

        return $response;
    }

    function updatePassword($pswd, $cpswd, $email) {

        $checkPassResponse = $this->crosscheckPassword($pswd, $cpswd);

        if(!($checkPassResponse['error'])) {

            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();
            $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);

            $sql = "UPDATE aider_user_customer SET Password_Hash = '$pswdHash', First_Login = 'NO' WHERE Email_Address = '$email'";

            $statement = $connection->query($sql);

            if($statement) {
                $response['error'] = false;
                $response['reason'] = 0;
                $response['message'] = "Your password has been updated.";
            } else {
                $response['error'] = true;
                $response['reason'] = 1;
                $response['message'] = "There was an error while trying to change your password.";
            }

            $connection->close();
        } else {
            $response['error'] = true;
            $response['reason'] = 2;
            $response['message'] = "The passwords do not match.";
        }

        return $response;
    }

    function crosscheckPassword($pswd, $cpswd) {
        if($pswd === $cpswd) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
        }

        return $response;
    }

    function getRecentTransactions($cust_id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_transaction_parcel WHERE Customer_ID=" . $cust_id;
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['data'] = "
            <table class=\"table table-responsive mt-4\">
                        <thead>
                            <tr>
                                <th scope=\"col\">#</th>
                                <th scope=\"col\">Type</th>
                                <th scope=\"col\">Pickup</th>
                                <th scope=\"col\">Destination</th>
                                <th scope=\"col\">Price</th>
                            </tr>
                        </thead>
                        <tbody>";
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {
                $pickUpLoc = explode(",", $row['Pickup_Location']);
                $dropOffLoc = explode(",", $row['Dropoff_Location']);

                $response['data'] .= "<tr>
                                <th scope=\"row\">" . $row['ID'] . "</th>
                                <th>Parcel</th>
                                <th>" . $pickUpLoc[0] . "</th>
                                <th>" . $dropOffLoc[0] . "</th>
                                <th>" . $row['Price'] . "</th>
                            </tr>";
            }
            $response['data'] .= "</tbody>
                    </table>";
        } else {
            $response['error'] = false;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">You recent transactions will appear here.</h6>
                    </div>";
        }

        $statement->close();
        $connection->close();

        return $response;
    }


    // TODO: Only Driver Module works for Ongoing Order -> Fix this function to include all modules to work seamlessly
    function getOngoingOrder($email) {
        $responseUserID = $this->getCustomerInformationByEmail($email, "ID");

        if($responseUserID['error']) {
            $response['error'] = true;
        } else {
            $response['error'] = false;

            $userID = $responseUserID['data'];

            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sqlSortingSelect = "SELECT * FROM aider_transaction_sorting WHERE 
                                              (Transaction_Status!='FINDING-RIDER' AND Transaction_Status!='COMPLETED'
                                                  AND Transaction_Status!='CANCELLED_BY_CUSTOMER') AND Customer_ID=$userID";

            $resultsSortingSelect = $connection->query($sqlSortingSelect);
            if($resultsSortingSelect->num_rows > 0) {
                $i = 0;
                while($row = $resultsSortingSelect->fetch_array()) {
                    $response['data'][$i] = $row;
                    $i++;
                }

                $response['count'] = $i;
            } else {
                $response['error'] = true;
            }
        }

        return $response;
    }

    function addMoneyToWallet($userEmail, $billRef, $amount, $timestamp) {
        $Aider = new Aider();

        $responseUserID = $Aider->getUserModal()->getCustomerModal()->getCustomerInformationByEmail($userEmail, 'ID');
        $responseUserCredit = $Aider->getUserModal()->getCustomerModal()->getCustomerInformationByEmail($userEmail, 'Credit');

        if(!$responseUserID['error'] && !$responseUserCredit['error']) {
            $userID = intval($responseUserID['data']);

            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sql = "INSERT INTO aider_bill_payment(Bill_Reference, User_ID, Bill_Amount, Bill_Timestamp) VALUES (?,?,?,?)";

            $statement = $connection->prepare($sql);

            $statement->bind_param("sids",$billRef, $userID, $amount, $timestamp);

            if($statement->execute()) {
                $response['error'] = false;

                $newCredit = doubleval($responseUserCredit['data']) + doubleval($amount);

                $responseUpdateWallet = $Aider->getUserModal()->getCustomerModal()->updateCustomerInformationByEmail($userEmail, 'Credit', doubleval($newCredit));

                if($responseUpdateWallet['error']) {
                    $response['error'] = true;
                    $response['message'] = $responseUpdateWallet['message'];
                } else {
                    $response['error'] = false;
                }
            } else {
                $response['error'] = true;
                $response['message'] = "There was an issue in updating your wallet.";
            }

            $statement->close();
            $connection->close();
        } else {
            $response['error'] = true;
            $response['message'] = $responseUserID['message'];
        }

        return $response;
    }

}