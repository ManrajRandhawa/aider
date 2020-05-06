<?php


class Register {

    function __construct() {}

    function registerStudent($name, $email, $pswd, $dob) {
        // Check Duplicate Emails
        $responseDuplicateEmail = $this->checkDuplicateEmails("STUDENT", $email);

        if($responseDuplicateEmail['error']) {
            $response['error'] = true;
            $response['message'] = $responseDuplicateEmail['message'];
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sql = "INSERT INTO qbank_users_students(Full_Name, Email_Address, Password_Hash, DOB) VALUES (?, ?, ?, ?)";

            $statement = $connection->prepare($sql);

            $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);

            $statement->bind_param("ssss",$name, $email, $pswdHash, $dob);

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

    function registerParent($childID, $name, $email, $pswd) {
        // Check Duplicate Emails
        $responseDuplicateEmail = $this->checkDuplicateEmails("PARENT", $email);

        if($responseDuplicateEmail['error']) {
            $response['error'] = true;
            $response['message'] = $responseDuplicateEmail['message'];
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sql = "INSERT INTO qbank_users_parents(Child_ID, Full_Name, Email_Address, Password_Hash) VALUES (?, ?, ?, ?)";

            $statement = $connection->prepare($sql);

            $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);

            $statement->bind_param("isss", $childID, $name, $email, $pswdHash);

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

    function crosscheckPassword($pswd, $cpswd) {
        if($pswd === $cpswd) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "The passwords do not match";
        }

        return $response;
    }

    function getStudentID($email) {

        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM qbank_users_students WHERE Email_Address = '$email'";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            while($row = $statement->fetch_assoc()) {
                $response['studentID'] = $row["ID"];
            }
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "The Student ID could not be found.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    private function checkDuplicateEmails($type, $email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($type === "PARENT") {
            $sql = "SELECT * FROM qbank_users_parents WHERE Email_Address = '$email'";
        } else {
            $sql = "SELECT * FROM qbank_users_students WHERE Email_Address = '$email'";
        }

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = true;
            if($type === "PARENT") {
                $response['message'] = "This parent email has already been used.";
            } else {
                $response['message'] = "This student email has already been used.";
            }
        } else {
            $response['error'] = false;
        }

        $statement->close();
        $connection->close();

        return $response;
    }

}