<?php


class Login {

    function __construct() {}

    function loginCustomer($email, $pswd) {
        $pswdHash = "";

        $Aider = new Aider();

        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_customer WHERE Email_Address = '$email'";

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {

            while($row = $statement->fetch_assoc()) {
                $pswdHash = $row["Password_Hash"];
            }

            if(password_verify($pswd, $pswdHash)) {
                $response['error'] = false;
                $response['email'] = $email;
            } else {
                $response['error'] = true;
                $response['message'] = "The password entered is incorrect.";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There is no user registered with that email.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }
}