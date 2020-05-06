<?php


class Login {

    function __construct() {}

    function login($email, $pswd) {
        $pswdHash = "";

        $Whiz = new Aider();

        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sqlS = "SELECT * FROM qbank_users_students WHERE Email_Address = '$email'";

        $statementS = $connection->query($sqlS);

        if($statementS->num_rows > 0) {

            while($row = $statementS->fetch_assoc()) {
                $pswdHash = $row["Password_Hash"];
            }

            if(password_verify($pswd, $pswdHash)) {
                $response['error'] = false;

                $Whiz->getCredentials()->getSessions()->setSession("User_Type", "STUDENT");
                $Whiz->getCredentials()->getSessions()->setSession("User_Email", $email);
            } else {
                $response['error'] = true;
                $response['message'] = "The password entered is incorrect.";
            }
        } else {
            $sqlP = "SELECT * FROM qbank_users_parents WHERE Email_Address = '$email'";

            $statementP = $connection->query($sqlP);

            if($statementP->num_rows > 0) {

                while($row = $statementP->fetch_assoc()) {
                    $pswdHash = $row["Password_Hash"];
                }

                if(password_verify($pswd, $pswdHash)) {
                    $response['error'] = false;

                    $Whiz->getCredentials()->getSessions()->setSession("User_Type", "PARENT");
                    $Whiz->getCredentials()->getSessions()->setSession("User_Email", $email);
                } else {
                    $response['error'] = true;
                    $response['message'] = "The password entered is incorrect.";
                }
            } else {
                $response['error'] = true;
                $response['message'] = "There's no account with that email address.";
            }

            $statementP->close();
        }

        $statementS->close();
        $connection->close();

        return $response;
    }
}