<?php


class AdminModal {

    function loginCustomer($email, $pswd) {
        $pswdHash = "";

        $Aider = new Aider();

        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_admin WHERE Email_Address = '$email'";

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

        $statement->close();
        $connection->close();

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

        $statement->close();
        $connection->close();

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

    function getSettingsInformation($info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_settings WHERE ID=1";
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

    function updateSettingsInformation($info, $value, $isNumber) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($isNumber === true) {
            $sql = "UPDATE aider_settings SET $info = $value WHERE ID=1";
        } else {
            $sql = "UPDATE aider_settings SET $info = '$value' WHERE ID=1";
        }

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
        }

        return $response;
    }

    function getCashOutRequests() {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider WHERE Withdraw_Amount!=0.00";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $num = 0;

            $response['error'] = false;

            $response['data'] = "<table class='table table-responsive'><thead class=\"text-center\">
                            <tr>
                                <th scope=\"col\">ID</th>
                                <th scope=\"col\">Name</th>
                                <th scope=\"col\">Phone</th>
                                <th scope=\"col\">Bank</th>
                                <th scope=\"col\">Account Number</th>
                                <th scope=\"col\">Amount</th>
                                <th scope=\"col\">Option</th>
                            </tr>
                        </thead>

                        <tbody class=\"text-center\">";

            while($row = $statement->fetch_assoc()) {
                $num++;

                $response['data'] .= "<tr>
                                <td scope=\"row\">" . $num . "</td>
                                <td>" . $row["Name"] . "</td>
                                <td><a href=\"tel:+6" . $row["Phone_Number"] . "\">" . $row["Phone_Number"] . "</a></td>
                                <td>" . $row["Bank"] . "</td>
                                <td>" . $row["Account_Number"] . "</td>
                                <td>" . $row["Withdraw_Amount"] . "</td>
                                <td>
                                    <div class=\"dropdown\">
                                        <button class=\"btn btn-sm btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Option
                                        </button>
                                        <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                            <a class=\"dropdown-item btn-approve\" id='" . $row["Email_Address"] . "' onclick='approveCashOutClick();'>Approve</a>
                                            <a class=\"dropdown-item btn-deny\" id='" . $row["Email_Address"] . "' onclick='denyCashOutClick();'>Deny</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>";
            }

            $response['data'] .= "</tbody></table>";
        } else {
            $response['error'] = true;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Any cash out requests would appear here.</h6>
                    </div>";
        }

        return $response;
    }

    function approveCashOutRequest($email) {
        $RiderModal = new RiderModal();
        $responseWithdrawAmount = $RiderModal->getRiderInformationByEmail($email, 'Withdraw_Amount');
        $responseWalletBalance = $RiderModal->getRiderInformationByEmail($email, 'Wallet_Balance');
        $responseTotalEarnings = $RiderModal->getRiderInformationByEmail($email, 'Total_Earnings');

        if(!$responseWithdrawAmount['error'] && !$responseWalletBalance['error'] && !$responseTotalEarnings['error']) {
            $responseUpdateWithdrawAmount = $RiderModal->updateRiderInformationNumberByEmail($email, 'Withdraw_Amount', 0.00);

            $responseUpdateWalletBalance = $RiderModal->updateRiderInformationNumberByEmail($email, 'Wallet_Balance', doubleval(doubleval($responseWalletBalance['data']) - doubleval($responseWithdrawAmount['data'])));

            $responseUpdateTotalEarnings = $RiderModal->updateRiderInformationNumberByEmail($email, 'Total_Earnings', doubleval(doubleval($responseTotalEarnings['data']) + doubleval($responseWithdrawAmount['data'])));

            if(!$responseUpdateTotalEarnings['error'] && !$responseUpdateWithdrawAmount['error'] && !$responseUpdateWalletBalance['error']) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }
        } else {
            $response['error'] = true;
        }

        return $response;
    }

    function denyCashOutRequest($email) {
        $RiderModal = new RiderModal();

        $responseUpdateWithdrawAmount = $RiderModal->updateRiderInformationNumberByEmail($email, 'Withdraw_Amount', 0.00);

        if($responseUpdateWithdrawAmount['error']) {
            $response['error'] = true;
        } else {
            $response['error'] = false;
        }

        return $response;
    }

    function getRiderReport() {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider LEFT JOIN aider_driver_team ON aider_user_rider.Team_ID = aider_driver_team.ID WHERE aider_user_rider.Team_ID != 0 ORDER BY aider_driver_team.Team_Name ASC";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {

            $response['error'] = false;

            $response['data'] = "<table border='3' class=\"table table-responsive\">
                        <thead class=\"text-center\">
                        <tr>
                            <th scope=\"col\">Team</th>
                            <th scope=\"col\">Name</th>
                            <th scope=\"col\">Email</th>
                            <th scope=\"col\">Phone</th>
                            <th scope=\"col\">Type</th>
                            <th scope=\"col\">Wallet</th>
                            <th scope=\"col\">Earnings</th>
                            <th scope=\"col\">Bank</th>
                            <th scope=\"col\">AC NO.</th>
                        </tr>
                        </thead>

                        <tbody class=\"text-center\">";

            while($row = $statement->fetch_assoc()) {

                $response['data'] .= "<tr>
                                <td scope=\"row\">" . $row['Team_Name'] . "</td>
                                <td>" . $row["Name"] . "</td>
                                <td>" . $row["Email_Address"] . "</td>
                                <td>" . $row["Phone_Number"] . "</td>
                                <td>" . $row["Rider_Type"] . "</td>
                                <td>" . $row["Wallet_Balance"] . "</td>
                                <td>" . $row["Total_Earnings"] . "</td>
                                <td>" . $row["Bank"] . "</td>
                                <td>" . $row["Account_Number"] . "</td>
                            </tr>";
            }

            $response['data'] .= "</tbody></table>";
        } else {
            $response['error'] = true;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Any reports would appear here.</h6>
                    </div>";
        }

        return $response;
    }

    function printFinanceStatement($startDate, $endDate) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sqlResults = "SELECT * FROM aider_transaction_driver";

        $statement = $connection->query($sqlResults);

        $filename = "Aider-Statement.xls"; // File Name
        // Download file
        header("Content-Disposition: attachment; filename=" . $filename);
        header("Content-Type: application/vnd.ms-excel");

        // Write data to file
        $flag = false;
        while ($row = $statement->fetch_assoc()) {

            $dateUnformatted = explode(' ', $row['Transaction_Datetime']);

            if(((strtotime($dateUnformatted[0]) > strtotime($startDate)) && (strtotime($dateUnformatted[0]) < strtotime($endDate))) || (strtotime($dateUnformatted[0]) == strtotime($startDate) || strtotime($dateUnformatted[0]) == $endDate)) {
                if (!$flag) {
                    // display field/column names as first row
                    echo implode("\t", array_keys($row)) . "\r\n";
                    $flag = true;
                }
                echo implode("\t", array_values($row)) . "\r\n";
            }
        }
        die();
    }

    function pushMessage($title, $message, $url) {
        $content = array(
            "en" => $message
        );

        $headings = array(
            "en" => $title
        );

        if(empty($url) || $url === '') {
            $fields = array(
                'app_id' => "e3ef4587-f257-45e7-9641-2e1f888e845a",
                'included_segments' => array('Test'),
                'headings' => $headings,
                'contents' => $content
            );
        } else {
            $fields = array(
                'app_id' => "e3ef4587-f257-45e7-9641-2e1f888e845a",
                'included_segments' => array('Customers'),
                'headings' => $headings,
                'url' => $url,
                'contents' => $content
            );
        }

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic NTk3YmVkMGUtYTU2Ni00NTBlLWJkNDEtYzZmYTc0NDY2NGFl'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_encode($response);
    }

    function getCancellableRides($searchArgs) {
        $Aider = new Aider();

        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        // Customer Name/Email Search
        $sqlSearchCustomer = "SELECT * FROM aider_user_customer WHERE (Name LIKE '%" . $searchArgs . "%') OR (Email_Address LIKE '%" . $searchArgs . "%') LIMIT 1";
        $statementSearchCustomer = $connection->query($sqlSearchCustomer);

        if($statementSearchCustomer->num_rows > 0) {
            while($rowCustomer = $statementSearchCustomer->fetch_assoc()) {
                $sql = "SELECT * FROM aider_transaction_sorting WHERE Customer_ID = " . $rowCustomer['ID'] . " AND Transaction_Status!='COMPLETED' AND Transaction_Status!='COMPLETED_RATED' AND Transaction_Status!='CANCELLED' ORDER BY ID DESC LIMIT 3";

                $statement = $connection->query($sql);

                if($statement->num_rows > 0) {
                    $num = 0;

                    $response['error'] = false;

                    $response['data'] = "<table class='table table-responsive mt-3'><thead class=\"text-center\">
                            <tr>
                                <th scope=\"col\">ID</th>
                                <th scope=\"col\">Type</th>
                                <th scope=\"col\">Customer</th>
                                <th scope=\"col\">Partner</th>
                                <th scope=\"col\">Pickup</th>
                                <th scope=\"col\">Dropoff</th>
                                <th scope=\"col\">Time</th>
                                <th scope=\"col\">Price</th>
                                <th scope=\"col\">Status</th>
                                <th scope=\"col\">Option</th>
                            </tr>
                            </thead>

                            <tbody class=\"text-center\">";

                    while($row = $statement->fetch_assoc()) {
                        $num++;
                        $partner = "";

                        $responseGetCustomerName = $Aider->getUserModal()->getCustomerModal()->getCustomerInformationByID(intval($row['Customer_ID']), 'Name');
                        $responseGetOrderDetails = $Aider->getUserModal()->getOrderModal()->getOrderDetails($row['Transaction_Type'], intval($row['Transaction_ID']));

                        if(!is_null($row['RT_ID'])) {

                            if($row['Transaction_Type'] === "DRIVER") {
                                $responseGetTeamMembers = $Aider->getUserModal()->getRiderModal()->getTeamInformationByID(intval($row['RT_ID']), 'Team_Members');

                                if(!$responseGetTeamMembers['error']) {
                                    $tm = explode(",", $responseGetTeamMembers['data']);

                                    $tmOne = $Aider->getUserModal()->getRiderModal()->getRiderInformationByID(intval($tm[0]), 'Name');
                                    $tmTwo = $Aider->getUserModal()->getRiderModal()->getRiderInformationByID(intval($tm[1]), 'Name');

                                    if(!$tmOne['error'] && !$tmTwo['error']) {
                                        $partner = $tmOne['data'] . "," . $tmTwo['data'];
                                    }
                                }
                            } else {
                                $responseGetPartnerName = $Aider->getUserModal()->getRiderModal()->getRiderInformationByID(intval($row['RT_ID']), 'Name');

                                if(!$responseGetPartnerName['error']) {
                                    $partner = $responseGetPartnerName['data'];
                                }
                            }
                        } else {
                            $responseGetPartnerName['error'] = false;
                            $responseGetPartnerName['data'] = "NULL";
                        }

                        if(!$responseGetCustomerName['error'] && !$responseGetOrderDetails['error']) {

                            $pickUpLoc = "";
                            $dropOffLoc = "";
                            $time = "";
                            $price = "";

                            if($row['Transaction_Type'] === "PARCEL") {
                                $pickUpLoc = $responseGetOrderDetails['data'][1];
                                $dropOffLoc = $responseGetOrderDetails['data'][2];
                                $time = $responseGetOrderDetails['data'][9];
                                $price = $responseGetOrderDetails['data'][8];
                            } elseif($row['Transaction_Type'] === "DRIVER") {
                                $pickUpLoc = $responseGetOrderDetails['data'][1];
                                $dropOffLoc = $responseGetOrderDetails['data'][2];
                                $time = $responseGetOrderDetails['data'][4];
                                $price = $responseGetOrderDetails['data'][3];
                            }

                            if($row['Transaction_Status'] === "FINDING-RIDER") {
                                $status = "Finding a Rider";
                            } elseif($row['Transaction_Status'] === "HEADING_TO_PICKUP") {
                                $status = "Heading to Pickup";
                            } elseif($row['Transaction_Status'] === "HEADING_TO_DESTINATION") {
                                $status = "Heading to Destination";
                            } else {
                                $status = "Unknown";
                            }

                            $response['data'] .= "<tr>
                                    <td scope=\"row\">" . $num . "</td>
                                    <td>" . $row['Transaction_Type'] . "</td>
                                    <td>" . $responseGetCustomerName['data'] . "</td>
                                    <td>" . $partner . "</td>
                                    <td>" . $pickUpLoc . "</td>
                                    <td>" . $dropOffLoc . "</td>
                                    <td>" . $time . "</td>
                                    <td>" . $price . "</td>
                                    <td>" . $status . "</td>
                                    <td>
                                        <div class=\"dropdown\">
                                            <button class=\"btn btn-sm btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Option
                                            </button>
                                            <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                                <a class=\"dropdown-item btn-cancel\" id='" . $row['Transaction_Type'] . "-" . $row['Transaction_ID'] . "' onclick='cancelRide();'>Cancel Ride</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>";
                        }
                    }

                    $response['data'] .= "</tbody></table>";
                } else {
                    $response['error'] = true;
                    $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Any cancellable rides would appear here.</h6>
                    </div>";
                }
            }
        } else {
            $response['error'] = true;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Refine your search (Customer Names/Emails) for better results.</h6>
                    </div>";
        }

        return $response;
    }

    function getAddMoneyList($searchArgs) {
        $Aider = new Aider();

        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        // Customer Name/Email Search
        $sqlSearchCustomer = "SELECT * FROM aider_user_customer WHERE (Name LIKE '%" . $searchArgs . "%') OR (Email_Address LIKE '%" . $searchArgs . "%') LIMIT 1";
        $statementSearchCustomer = $connection->query($sqlSearchCustomer);

        if($statementSearchCustomer->num_rows > 0) {
            $response['error'] = false;

            while($rowCustomer = $statementSearchCustomer->fetch_assoc()) {
                $num = 0;

                $response['data'] = "<table class='table table-responsive mt-3'><thead class=\"text-center\">
                            <tr>
                                <th scope=\"col\">ID</th>
                                <th scope=\"col\">Type</th>
                                <th scope=\"col\">Name</th>
                                <th scope=\"col\">Email</th>
                                <th scope=\"col\">Amount</th>
                                <th scope=\"col\">Option</th>
                            </tr>
                            </thead>

                            <tbody class=\"text-center\">";

                $response['data'] .= "<tr>
                                    <td scope=\"row\">" . $num . "</td>
                                    <td>Customer</td>
                                    <td>" . $rowCustomer['Name'] . "</td>
                                    <td>" . $rowCustomer['Email_Address'] . "</td>
                                    <td>" . $rowCustomer['Credit'] . "</td>
                                    <td>
                                        <div class=\"dropdown\">
                                            <button class=\"btn btn-sm btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Option
                                            </button>
                                            <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                                <a class=\"dropdown-item btn-cancel\" id='CUSTOMER" . "-" . $rowCustomer['ID'] . "' onclick='addMoney();'>Update Wallet</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>";

                }
            } else {
                $response['error'] = true;
                $response['data'] = "<div class=\"col-12 text-center mt-5\">
                            <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                            <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                            <h6 class=\"text-black-50\">Refine your search (Customer Names/Emails) for better results.</h6>
                        </div>";
            }

        return $response;
    }

    function cancelRide($rideType, $rideTypeID) {
        // SET STATUS TO -> CANCELLED

        $Aider = new Aider();

        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($rideType === "PARCEL") {
            $riderTable = "Rider_ID";
        } elseif($rideType === "DRIVER") {
            $riderTable = "Team_ID";
        }
        $responseGetRider = $Aider->getUserModal()->getOrderModal()->getOrderDetailsByID($rideType, $rideTypeID, $riderTable);


        if(!$responseGetRider['error']) {
            // Set all statuses
            $sqlUpdateSorting = "UPDATE aider_transaction_sorting SET Transaction_Status = 'CANCELLED' WHERE Transaction_ID = " . intval($rideTypeID) . " AND Transaction_Type = '" . $rideType . "'";

            $statement = $connection->query($sqlUpdateSorting);

            if($statement) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }

            $connection->close();

            if(!$response['error']) {
                $responseRideTypeStatusUpdate = $Aider->getUserModal()->getOrderModal()->setOrderStatus($rideType, $rideTypeID, 'CANCELLED');

                if(!is_null($responseGetRider['data'])) {
                    if($rideType === "PARCEL") {
                        $responseRiderStatusUpdate = $Aider->getUserModal()->getRiderModal()->updateRiderStatusByID('ACTIVE', '', 0, intval($responseGetRider['data']));
                    } elseif($rideType === "DRIVER") {
                        $responseTeamStatusUpdate = $Aider->getUserModal()->getRiderModal()->setTeamStatus(intval($responseGetRider['data']), 'ACTIVE');
                        $responseGetTeamMembers = $Aider->getUserModal()->getRiderModal()->getTeamInformationByID(intval($responseGetRider['data']), 'Team_Members');

                        if(!$responseTeamStatusUpdate['error'] && !$responseGetTeamMembers['error']) {
                            $teamMembers = explode(",", $responseGetTeamMembers['data']);

                            $responseRiderStatusUpdateMOne = $Aider->getUserModal()->getRiderModal()->updateRiderStatusByID('ACTIVE', '', 0, intval($teamMembers[0]));
                            $responseRiderStatusUpdateMTwo = $Aider->getUserModal()->getRiderModal()->updateRiderStatusByID('ACTIVE', '', 0, intval($teamMembers[1]));

                            if(!$responseRiderStatusUpdateMOne['error'] && !$responseRiderStatusUpdateMTwo['error']) {
                                $responseRiderStatusUpdate['error'] = false;
                            } else {
                                $responseRiderStatusUpdate['error'] = true;
                            }
                        } else {
                            if($responseTeamStatusUpdate['error']) {
                                $response['message'] .= "0</br>";
                            }

                            if($responseGetTeamMembers['error']) {
                                $response['message'] .= "1-" . $responseGetRider['data'] . "-" . $riderTable;
                            }
                        }
                    }
                } else {
                    $responseRiderStatusUpdate['error'] = false;
                }

                if(!$responseRideTypeStatusUpdate['error'] && !$responseRiderStatusUpdate['error']) {
                    $response['error'] = false;
                } else {
                    $response['error'] = true;
                    $response['message'] = "There was an issue in updating the rider(s) information.";
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Could not update the database.";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an issue in getting the rider information.";
        }

        return $response;
    }
}