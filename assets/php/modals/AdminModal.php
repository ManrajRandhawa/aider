<?php


class AdminModal {

    function addNewAdmin($name, $email, $pswd) {
        $dupEmails = $this->checkDuplicateEmails($email);

        if($dupEmails['error']) {
            $response['error'] = true;
            $response['message'] = $dupEmails['message'];
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sql = "INSERT INTO aider_user_admin(Name, Email_Address, Password_Hash) VALUES (?,?,?)";

            $statement = $connection->prepare($sql);

            $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);

            $statement->bind_param("sss",$name, $email, $pswdHash);

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

    function getAdminInformationByID($id, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_admin WHERE ID=$id";
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

    function getAdminInformationByEmail($email, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_admin WHERE Email_Address='" . $email . "'";
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

        $sql = "SELECT * FROM aider_user_admin WHERE Email_Address = '$email'";

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

}