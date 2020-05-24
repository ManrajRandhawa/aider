<?php


class CustomerModal {

    function addNewCustomer($name, $email, $pswd, $hp) {
        $dupEmails = $this->checkDuplicateEmails($email);

        if($dupEmails['error']) {
            $response['error'] = true;
            $response['message'] = $dupEmails['message'];
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sql = "INSERT INTO aider_user_customer(Name, Email_Address, Password_Hash, Phone_Number, Credit) VALUES (?,?,?,?,?)";

            $statement = $connection->prepare($sql);

            $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);

            $statement->bind_param("ssssd",$name, $email, $pswdHash, $hp, 0);

            if($statement->execute()) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
                $response['message'] = "There was an error while registering you with us.";
            }

            $statement->close();
            $connection->close();
        }

        return $response;
    }

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

    private function checkDuplicateEmails($email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_customer WHERE Email_Address = '$email'";

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = true;
            $response['message'] = "This email has already been used.";
        } else {
            $response['error'] = false;
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function updatePassword($pswd, $cpswd, $email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if(!($this->crosscheckPassword($pswd, $cpswd)['error'])) {
            $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);

            $sql = "UPDATE aider_user_customer SET Password_Hash = '$pswdHash', First_Login = 'NO' WHERE Email_Address = '$email'";

            $statement = $connection->query($sql);

            if($statement) {
                $response['error'] = false;
                $response['message'] = "Your password has been updated.";
            } else {
                $response['error'] = true;
                $response['message'] = "There was an error while trying to change your password.";
            }

            $statement->close();
            $connection->close();
        } else {
            $response['error'] = true;
            $response['message'] = "The passwords do not match.";
        }

        return $response;
    }

    private function crosscheckPassword($pswd, $cpswd) {
        if($pswd === $cpswd) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "The passwords do not match";
        }

        return $response;
    }

    function bookParcelDelivery() {
        // RM 5 (Base Fare) + RM 1.50 every km

    }

}