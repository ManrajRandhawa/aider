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

    private function crosscheckPassword($pswd, $cpswd) {
        if($pswd === $cpswd) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
        }

        return $response;
    }

    function bookParcelDelivery() {
        // RM 5 (Base Fare) + RM 1.50 every km

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

}