<?php


class StudentModal {

    function getStudentInformation($info) {
        if(isset($_SESSION["User_Type"]) && isset($_SESSION["User_Email"])) {
            if($_SESSION["User_Type"] === "STUDENT") {
                $DatabaseHandler = new DatabaseHandler();
                $connection = $DatabaseHandler->getMySQLiConnection();

                $sql = "SELECT * FROM qbank_users_students WHERE Email_Address='" . $_SESSION['User_Email'] . "'";
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
            } else {
                $response['error'] = true;
                $response['message'] = "You're not a student. What are you? :o";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "It seems like there was an issue with the sessions. Try to log in again :)";
        }

        return $response;
    }

}