<?php


class BillModal {

    function getBillInformationByReference($userEmail, $ref, $info) {
        $Aider = new Aider();
        $responseID = $Aider->getUserModal()->getCustomerModal()->getCustomerInformationByEmail($userEmail, 'ID');

        if($responseID['error']) {
            $response['error'] = true;
            $response['message'] = $responseID['message'];
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sql = "SELECT * FROM aider_bill_payment WHERE Bill_Reference='" . $ref . "' AND User_ID = " . intval($responseID['data']);
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
        }

        return $response;
    }
}