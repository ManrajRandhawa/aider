<?php

class MerchantModal {

    function addNewMerchant($name, $email, $pswd, $loc) {
        $dupEmails = $this->checkDuplicateEmails($email);

        if($dupEmails['error']) {
            $response['error'] = true;
            $response['message'] = $dupEmails['message'];
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sql = "INSERT INTO aider_user_merchant(Name, Email_Address, Password_Hash, Location) VALUES (?,?,?,?)";

            $statement = $connection->prepare($sql);

            $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);

            $statement->bind_param("ssss",$name, $email, $pswdHash, $loc);

            if($statement->execute()) {
                $response['error'] = false;
                $response['studentEmail'] = $email;
            } else {
                $response['error'] = true;
                $response['message'] = "There was an error while registering you with us.";
            }

            $statement->close();
            $connection->close();
        }

        return $response;
    }

    function getMerchantInformationByID($id, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_merchant WHERE ID=$id";
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

        return $response;
    }

    function getMerchantInformationByEmail($email, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_merchant WHERE Email_Address='" . $email . "'";
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

        return $response;
    }

    private function checkDuplicateEmails($email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_merchant WHERE Email_Address = '$email'";

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


    // Specialized Methods
    function addProductToMerchant($m_ID, $name, $price) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "INSERT INTO aider_merchant_product(Merchant_ID, Name, Price) VALUES (?,?,?)";

        $statement = $connection->prepare($sql);

        $statement->bind_param("sss",$m_ID, $name, $price);

        if($statement->execute()) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while adding the product.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

}