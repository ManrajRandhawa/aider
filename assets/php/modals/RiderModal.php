<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/phpmailer/vendor/autoload.php";

class RiderModal {

    function applyRider($name, $email, $hp_num) {
        $dupEmails = $this->checkDuplicateEmails($email);

        if($dupEmails['error']) {
            $response['error'] = true;
            $response['message'] = $dupEmails['message'];
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sql = "INSERT INTO aider_user_rider(Name, Email_Address, Phone_Number, Approval_Status) VALUES (?,?,?,?)";

            $statement = $connection->prepare($sql);

            $approval_status = "NOT-APPROVED";

            $statement->bind_param("ssss",$name, $email, $hp_num, $approval_status);

            if($statement->execute()) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
                $response['message'] = "There was an error while applying.";
            }

            $statement->close();
            $connection->close();
        }

        return $response;
    }

    function loginRider($email, $pswd) {
        $pswdHash = "";
        $approval_status = "";

        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider WHERE Email_Address = '$email'";

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {

            while($row = $statement->fetch_assoc()) {
                $pswdHash = $row["Password_Hash"];
                $approval_status = $row["Approval_Status"];
            }

            if($approval_status === "APPROVED") {
                if(password_verify($pswd, $pswdHash)) {
                    $response['error'] = false;
                    $response['email'] = $email;
                } else {
                    $response['error'] = true;
                    $response['message'] = "The password entered is incorrect.";
                }
            } else {
                $response['error'] = true;
                $response['message'] = "Your account is still pending approval.";
            }


        } else {
            $response['error'] = true;
            $response['message'] = "There is no user registered with that email.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function getRiderInformationByID($id, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider WHERE ID=$id";
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

    function getRiderInformationForOngoing($id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider WHERE ID=$id";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {
                $response['data'] = array(
                    $row['Name'],
                    $row['Phone_Number'],
                    $row['Vehicle_Model'],
                    $row['Vehicle_Plate_Number'],
                    $row['Rating'],
                    $row['Rating_Counter']
                );
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to connect to the database.";
        }

        return $response;
    }

    function getRiderInformationByEmail($email, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider WHERE Email_Address='" . $email . "'";
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

    function updateRiderInformationByEmail($email, $key, $value) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "UPDATE aider_user_rider SET $key = '$value' WHERE Email_Address='" . $email . "'";
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

    function updateRiderInformationNumberByEmail($email, $key, $value) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "UPDATE aider_user_rider SET $key = $value WHERE Email_Address='" . $email . "'";
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

    function getWaitingList() {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider WHERE Approval_Status='NOT-APPROVED'";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $num = 0;

            $response['error'] = false;

            $response['data'] = "<table class=\"table table-responsive\">
                        <thead class=\"text-center\">
                            <tr>
                                <th scope=\"col\">ID</th>
                                <th scope=\"col\">Name</th>
                                <th scope=\"col\">Email Address</th>
                                <th scope=\"col\">Phone</th>
                                <th scope=\"col\">Option</th>
                            </tr>
                        </thead>

                        <tbody class=\"text-center\">";

            while($row = $statement->fetch_assoc()) {
                $num++;

                $response['data'] .= "<tr>
                                <td scope=\"row\">" . $num . "</td>
                                <td>" . $row["Name"] . "</td>
                                <td><a href=\"mailto:" . $row["Email_Address"] . "\">" . $row["Email_Address"] . "</a></td>
                                <td><a href=\"tel:+6" . $row["Phone_Number"] . "\">" . $row["Phone_Number"] . "</a></td>
                                <td>
                                    <div class=\"dropdown\">
                                        <button class=\"btn btn-sm btn-primary dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                            Option
                                        </button>
                                        <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                            <a class=\"dropdown-item btn-approve\" id='" . $row["Email_Address"] . "' onclick='approveButtonClick(this);'>Approve</a>
                                            <a class=\"dropdown-item btn-deny\" id='" . $row["Email_Address"] . "' onclick='denyButtonClick(this);'>Deny</a>
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
                        <h6 class=\"text-black-50\">Any new rider that applied recently will appear here.</h6>
                    </div>";
        }

        return $response;
    }

    function setTeamStatus($id, $status) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "UPDATE aider_driver_team SET Team_Status = '$status' WHERE ID = $id";

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
            $response['message'] = "The team's status has been updated.";
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to update the team's status.";
        }

        $connection->close();

        return $response;
    }

    function getTeamInformationByID($id, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_driver_team WHERE ID=$id";
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

    function getTeamsList() {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_driver_team";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $num = 0;

            $response['error'] = false;

            $response['data'] = "";

            while($row = $statement->fetch_assoc()) {
                $num++;

                $members = explode(',', $row['Team_Members']);

                $memberOne = $this->getRiderInformationByID($members[0], "Name");
                $memberTwo = $this->getRiderInformationByID($members[1], "Name");

                if(!$memberOne['error'] && !$memberTwo['error']) {
                    $response['data'] .= "<div class=\"col-6 mt-2\">
                            <div class=\"card\">
                                <div class=\"card-body\">
                                    <h5 class=\"card-title font-weight-bold text-success text-center\">" . $row['Team_Name'] . "</h5>
                                    <hr class=\"my-2\"/>
                                    <h6 class=\"card-text text-center p-0 m-0\">" . $memberOne['data'] . "</h6>
                                    <h6 class=\"card-text text-center p-0 m-0\">&</h6>
                                    <h6 class=\"card-text text-center p-0 mt-0 mb-3\">" . $memberTwo['data'] . "</h6>
                                    <a href=\"#\" onclick='editTeam(this.id);' class=\"btn btn-outline-success float-left\" id='" . $row['ID'] . "'><i class=\"far fa-edit edit-team\"></i></a>
                                    <a href=\"#\" onclick='deleteTeam(this.id);' class=\"btn btn-outline-danger float-right\" id='" . $row['ID'] . "'><i class=\"far fa-trash-alt\"></i></a>
                                </div>
                            </div>
                        </div>";
                }


            }
        } else {
            $response['error'] = true;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Any teams added will appear here.</h6>
                    </div>";
        }

        return $response;
    }

    function getTripCut($riderEmail, $tripType, $tripID) {
        $Aider = new Aider();

        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($tripType === "DRIVER") {
            $TeamResponse = $Aider->getUserModal()->getRiderModal()->getRiderInformationByEmail($riderEmail, "Team_ID");
            $TeamIDResponse = $Aider->getUserModal()->getRiderModal()->getRiderInformationByEmail($riderEmail, "ID");

            if(!$TeamResponse['error'] && !$TeamIDResponse['error']) {
                $OrderResponse = $Aider->getUserModal()->getOrderModal()->getOrderDetailsByID($tripType, $tripID, "Price");
                $SettingsPrimaryResponse = $Aider->getUserModal()->getAdminModal()->getSettingsInformation("Aider_Driver_Primary_Cut");
                $SettingsSecondaryResponse = $Aider->getUserModal()->getAdminModal()->getSettingsInformation("Aider_Driver_Secondary_Cut");

                if(!$OrderResponse['error'] && !$SettingsPrimaryResponse['error'] && !$SettingsSecondaryResponse['error']) {
                    $sqlTeamData = "SELECT * FROM aider_driver_team WHERE ID=" . $TeamResponse['data'];

                    $statementTeamData = $connection->query($sqlTeamData);

                    if($statementTeamData->num_rows > 0) {
                        while($rowTeamData = $statementTeamData->fetch_assoc()) {
                            $teamMembers = $rowTeamData['Team_Members'];
                            $teamMembers = explode(',', $teamMembers);

                            $primaryPercentage = doubleval($SettingsPrimaryResponse['data']);
                            $secondaryPercentage = doubleval($SettingsSecondaryResponse['data']);

                            $primaryCut = doubleval($OrderResponse['data']) * ($primaryPercentage/100);
                            $secondaryCut = doubleval($OrderResponse['data']) * ($secondaryPercentage/100);

                            $sqlDriverUpdate = "UPDATE aider_transaction_driver SET Primary_Rider_Cut=$primaryCut, Secondary_Rider_Cut=$secondaryCut WHERE ID=" . intval($tripID);

                            $sqlTeamMemberPrimary = "UPDATE aider_user_rider SET Wallet_Balance=Wallet_Balance + " . doubleval($primaryCut) . " WHERE ID=" . intval($teamMembers[0]);
                            $sqlTeamMemberSecondary = "UPDATE aider_user_rider SET Wallet_Balance=Wallet_Balance + " . doubleval($secondaryCut) . " WHERE ID=" . intval($teamMembers[1]);

                            $statementDriverUpdate = $connection->query($sqlDriverUpdate);

                            $statementTMP = $connection->query($sqlTeamMemberPrimary);
                            $statementTMS = $connection->query($sqlTeamMemberSecondary);

                            if($statementDriverUpdate && $statementTMP && $statementTMS) {
                                $response['error'] = false;
                                if(intval($teamMembers[0]) === intval($TeamIDResponse['data'])) {
                                    $response['data'] = number_format($primaryCut, 2, '.', '');
                                } else {
                                    $response['data'] = number_format($secondaryCut, 2, '.', '');
                                }
                            } else {
                                $response['error'] = true;
                                $response['message'] = "There was an issue while updating the Riders' cuts and Wallet Balances.";
                            }
                        }
                    } else {
                        $response['error'] = true;
                        $response['data'] = "You are not assigned to a team. How did this happen?";
                    }

                    $statementTeamData->close();
                } else {
                    $response['error'] = true;
                    $response['message'] = "There was an issue while trying to fetch the Order and Settings details.";
                }
            } else {
                $response['error'] = true;
                $response['message'] = $TeamResponse['message'];
            }

        } elseif($tripType === "PARCEL") {
            $RiderIDResponse = $Aider->getUserModal()->getRiderModal()->getRiderInformationByEmail($riderEmail, "ID");
            $OrderResponse = $Aider->getUserModal()->getOrderModal()->getOrderDetailsByID($tripType, $tripID, "Price");
            $SettingsParcelResponse = $Aider->getUserModal()->getAdminModal()->getSettingsInformation("Aider_Parcel_Cut");

            if(!$RiderIDResponse['error'] && !$OrderResponse['error'] && !$SettingsParcelResponse['error']) {
                $orderPrice = doubleval($OrderResponse['data']);
                $parcelCutPercentage = doubleval($SettingsParcelResponse['data']);

                $riderCut = $orderPrice * ($parcelCutPercentage / 100);

                $sqlParcelUpdate = "UPDATE aider_transaction_parcel SET Rider_Cut=" . doubleval($riderCut) . " WHERE ID=" . intval($tripID);
                $sqlRiderUpdate = "UPDATE aider_user_rider SET Wallet_Balance=Wallet_Balance + " . doubleval($riderCut) . " WHERE ID=" . intval($RiderIDResponse['data']);

                $statementParcelUpdate = $connection->query($sqlParcelUpdate);
                $statementRiderUpdate = $connection->query($sqlRiderUpdate);

                if($statementParcelUpdate && $statementRiderUpdate) {
                    $response['error'] = false;
                    $response['data'] = number_format($riderCut, 2, '.', '');
                } else {
                    $response['error'] = false;
                    $response['message'] = "There was an issue while updating the Rider's Cut.";
                }
            } else {
                $response['error'] = true;
                $response['message'] = "There was an issue while trying to fetch the Order and Settings details.";
            }
        } elseif($tripType === "FOOD") {
            $RiderIDResponse = $Aider->getUserModal()->getRiderModal()->getRiderInformationByEmail($riderEmail, "ID");
            $OrderResponse = $Aider->getUserModal()->getOrderModal()->getOrderDetailsByID($tripType, $tripID, "Price");
            $SettingsFoodResponse = $Aider->getUserModal()->getAdminModal()->getSettingsInformation("Aider_Food_Cut");

            if(!$RiderIDResponse['error'] && !$OrderResponse['error'] && !$SettingsFoodResponse['error']) {
                $orderPrice = doubleval($OrderResponse['data']);
                $parcelCutPercentage = doubleval($SettingsFoodResponse['data']);

                $riderCut = $orderPrice * ($parcelCutPercentage / 100);

                $sqlParcelUpdate = "UPDATE aider_transaction_food SET Rider_Cut=" . doubleval($riderCut) . " WHERE ID=" . intval($tripID);
                $sqlRiderUpdate = "UPDATE aider_user_rider SET Wallet_Balance=Wallet_Balance + " . doubleval($riderCut) . " WHERE ID=" . intval($RiderIDResponse['data']);

                $statementParcelUpdate = $connection->query($sqlParcelUpdate);
                $statementRiderUpdate = $connection->query($sqlRiderUpdate);

                if($statementParcelUpdate && $statementRiderUpdate) {
                    $response['error'] = false;
                    $response['data'] = number_format($riderCut, 2, '.', '');
                } else {
                    $response['error'] = false;
                    $response['message'] = "There was an issue while updating the Rider's Cut.";
                }
            } else {
                $response['error'] = true;
                $response['message'] = "There was an issue while trying to fetch the Order and Settings details.";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Incorrect trip type.";
        }

        return $response;
    }

    function getRidersSelectList($type) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider WHERE Rider_Type='" . $type . "' AND Team_ID=0";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $num = 0;

            $response['error'] = false;

            $response['data'] = "";

            while($row = $statement->fetch_assoc()) {
                $num++;

                $response['data'] .= "<option value='" . $row['ID'] . "'>" . $row['Name'] . " (" . $row['Email_Address'] . ")</option>";
            }
        } else {
            $response['error'] = true;
            $response['data'] = "";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function getEditedRidersSelectList($id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $response['dataRider'] = "";
        $response['dataDriver'] = "";

        $sql = "SELECT * FROM aider_user_rider";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;

            while($row = $statement->fetch_assoc()) {
                if($row['ID'] == $id) {
                    if($row['Rider_Type'] === "RIDER") {
                        $response['dataRider'] .= "<option selected value='" . $row['ID'] . "'>" . $row['Name'] . " (" . $row['Email_Address'] . ")</option>";
                    } else {
                        $response['dataDriver'] .= "<option selected value='" . $row['ID'] . "'>" . $row['Name'] . " (" . $row['Email_Address'] . ")</option>";
                    }
                } else {
                    if($row['Rider_Type'] === "RIDER") {
                        $response['dataRider'] .= "<option value='" . $row['ID'] . "'>" . $row['Name'] . " (" . $row['Email_Address'] . ")</option>";
                    } else {
                        $response['dataDriver'] .= "<option value='" . $row['ID'] . "'>" . $row['Name'] . " (" . $row['Email_Address'] . ")</option>";
                    }
                }
            }

            $response['data'] = array($response['dataRider'], $response['dataDriver']);
        } else {
            $response['error'] = true;
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function getTeamDetailsForEditing($team_id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_driver_team WHERE ID=$team_id";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            $response['data'] = "";

            while($row = $statement->fetch_assoc()) {
                $team_members = explode(',', $row['Team_Members']);

                $response['data'] = array(
                    $row['Team_Name'],
                    $team_members[0],
                    $team_members[1]
                );
            }
        } else {
            $response['error'] = true;
            $response['data'] = "";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function getTeamDetailsForDeleting($team_id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_driver_team WHERE ID=$team_id";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;

            while($row = $statement->fetch_assoc()) {
                $response['data'] = $row['Team_Name'];
            }
        } else {
            $response['error'] = true;
            $response['data'] = "";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function approveRider($email, $v_model, $v_pn, $type) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $Register = new Register();

        // START: Generate Temporary Password
        $pswd = $Register->generatePassword();
        $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);
        // END: Generate Temporary Password

        if($type === "Rider") {
            $type = "RIDER";
        } elseif($type === "Driver") {
            $type = "DRIVER";
        }

        $sql = "UPDATE aider_user_rider SET Password_Hash='" . $pswdHash . "', Approval_Status='APPROVED', Vehicle_Model='" . $v_model . "', Vehicle_Plate_Number='" . $v_pn . "', Rider_Type='" . $type . "', First_Login='YES' WHERE Email_Address='" . $email . "'";
        $statement = $connection->query($sql);

        $nameResponse = $this->getRiderInformationByEmail($email, "Name");
        if(!$nameResponse['error']) {
            $name = $nameResponse['data'];
        } else {
            $name = "";
        }

        $first_name = explode(' ', $name)[0];


        if($statement) {
            // Send Confirmation Email
            $mail = new PHPMailer(true);

            $content = $Register->getRiderConfirmationEmailContent($name, $email, $pswd, "https://aider.my/", GOOGLE_PLAY_STORE_LINK, APPLE_APP_STORE_LINK);

            try {
                $mail->isSMTP();
                $mail->Mailer = "smtp";
                $mail->SMTPAuth   = TRUE;
                $mail->SMTPSecure = "tls";
                $mail->Port       = 587;
                $mail->Host       = "smtp.gmail.com";
                $mail->Username   = "aider.delivery@gmail.com";
                $mail->Password   = "Aider@2020";

                $mail->setFrom('aider.delivery@gmail.com', 'myAider');
                $mail->addAddress($email);
                $mail->addReplyTo('info@aider.my', 'myAider');

                $mail->isHTML(true);
                $mail->Subject = 'myAider | Welcome Email for ' . $first_name;
                $mail->MsgHTML($content);
                $mail->AltBody = 'Thank you for registering with us. You can log in with your email in the application with the following password: ' . $pswd;

                if($mail->send()) {
                    $response['error'] = false;
                    $response['email'] = $email;
                    $response['message'] = "The rider has been approved.";
                } else {
                    $response['error'] = true;
                    $response['message'] = "Message could not be sent.";
                }
            } catch (Exception $e) {
                $response['error'] = true;
                $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to update the approval status.";
        }

        return $response;
    }

    function denyRider($email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "DELETE FROM aider_user_rider WHERE Email_Address='" . $email . "'";
        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
            $response['message'] = "The rider's approval status has been denied.";
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to update the approval status.";
        }

        return $response;
    }

    function createTeam($teamName, $teamMembers) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "INSERT INTO aider_driver_team(Team_Name, Team_Members, Team_Status) VALUES (?,?,?)";

        $statement = $connection->prepare($sql);

        $teamStatus = "INACTIVE";

        $statement->bind_param("sss",$teamName, $teamMembers, $teamStatus);

        if($statement->execute()) {
            $response['error'] = false;

            $TeamID = $statement->insert_id;

            $members = explode(',', $teamMembers);
            $memberOne = $this->getRiderInformationByID($members[0], 'Email_Address');
            $memberTwo = $this->getRiderInformationByID($members[1], 'Email_Address');

            if(!$memberOne['error'] && !$memberTwo['error']) {
                $responseOne = $this->updateRiderInformationByEmail($memberOne['data'], "Team_ID", $TeamID);
                $responseTwo = $this->updateRiderInformationByEmail($memberTwo['data'], "Team_ID", $TeamID);

                if(!$responseOne['error'] && !$responseTwo['error']) {
                    $response['error'] = false;
                } else {
                    $response['error'] = true;
                }
            } else {
                $response['error'] = true;
            }

        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while creating the [" . $teamName . "] team.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function editTeam($teamID, $teamName, $teamMemberOne, $teamMemberTwo) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $teamMembers = $teamMemberOne . "," . $teamMemberTwo;

        $updateTeamSQL = "UPDATE aider_driver_team SET Team_Name='$teamName', Team_Members='$teamMembers' WHERE ID=$teamID";
        $statementUpdateTeam = $connection->query($updateTeamSQL);

        $updateRiderTeamSQL = "UPDATE aider_user_rider SET Team_ID=0 WHERE Team_ID=$teamID";
        $statementUpdateRiderTeam = $connection->query($updateRiderTeamSQL);

        $updateRiderTeamNewMemOneSQL = "UPDATE aider_user_rider SET Team_ID=$teamID WHERE ID=$teamMemberOne";
        $statementUpdateRiderTeamNewMemOne = $connection->query($updateRiderTeamNewMemOneSQL);

        $updateRiderTeamNewMemTwoSQL = "UPDATE aider_user_rider SET Team_ID=$teamID WHERE ID=$teamMemberTwo";
        $statementUpdateRiderTeamNewMemTwo = $connection->query($updateRiderTeamNewMemTwoSQL);

        if($statementUpdateTeam && $statementUpdateRiderTeam && $statementUpdateRiderTeamNewMemOne && $statementUpdateRiderTeamNewMemTwo) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
        }

        $statementUpdateTeam->close();
        $statementUpdateRiderTeam->close();
        $statementUpdateRiderTeamNewMemOne->close();
        $statementUpdateRiderTeamNewMemTwo->close();
        $connection->close();

        return $response;
    }

    function deleteTeam($teamID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $deleteTeamSQL = "DELETE FROM aider_driver_team WHERE ID=$teamID";
        $statementDeleteTeam = $connection->query($deleteTeamSQL);

        $updateRiderTeamSQL = "UPDATE aider_user_rider SET Team_ID=0 WHERE Team_ID=$teamID";
        $statementUpdateRiderTeam = $connection->query($updateRiderTeamSQL);

        if($statementDeleteTeam) {
            $response['error'] = false;

            if($statementUpdateRiderTeam) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }
        } else {
            $response['error'] = true;
        }

        $statementDeleteTeam->close();
        $statementUpdateRiderTeam->close();
        $connection->close();

        return $response;
    }

    function getActiveRidersForMap() {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider WHERE Status = 'ACTIVE'";

        $statement = $connection->query($sql);

        $response['data'] = "";

        if($statement->num_rows > 0) {
            while($row = $statement->fetch_assoc()) {

                if(!(empty($row['Loc_LAT']) || empty($row['Loc_LNG']))) {
                    $response['data'] .= "{
                        position: new google.maps.LatLng(" . $row['Loc_LAT'] . ", " . $row['Loc_LNG'] . "),
                        type: 'info',
                    },";
                }
            }
        } else {
            $response['data'] = "";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    private function checkDuplicateEmails($email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_rider WHERE Email_Address = '$email'";

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

        $CustomerModal = new CustomerModal();
        $checkPassResponse = $CustomerModal->crosscheckPassword($pswd, $cpswd);

        if(!($checkPassResponse['error'])) {

            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();
            $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);

            $sql = "UPDATE aider_user_rider SET Password_Hash = '$pswdHash', First_Login = 'NO' WHERE Email_Address = '$email'";

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

    function updateRiderStatus($status, $t_type, $t_id, $email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "UPDATE aider_user_rider SET Status = '$status', Transaction_Type = '$t_type', Transaction_ID = $t_id WHERE Email_Address = '$email'";

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
            $response['message'] = "The rider's status has been updated.";
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to update the rider's status.";
        }

        $connection->close();

        return $response;
    }

    function updateRiderStatusByID($status, $t_type, $t_id, $id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "UPDATE aider_user_rider SET Status = '$status', Transaction_Type = '$t_type', Transaction_ID = $t_id WHERE ID = $id";

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
            $response['message'] = "The rider's status has been updated.";
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to update the rider's status.";
        }

        $connection->close();

        return $response;
    }

    function updateRiderLocation($lng, $lat, $email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "UPDATE aider_user_rider SET Loc_LNG = '$lng', Loc_LAT = '$lat' WHERE Email_Address = '$email'";

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
            $response['message'] = "The rider's location has been updated.";
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to update the rider's location.";
        }

        $connection->close();

        return $response;
    }

    function resetPassword($email) {
        // Check Duplicate Emails
        $responseEmailExists = $this->checkIfEmailExists("RIDER", $email);

        if($responseEmailExists['error']) {
            $response['error'] = true;
            $response['message'] = $responseEmailExists['message'];
        } else {
            $responseName = $this->getRiderInformationByEmail($email, 'Name');

            if(!$responseName['error']) {
                $DatabaseHandler = new DatabaseHandler();
                $connection = $DatabaseHandler->getMySQLiConnection();

                // START: Generate Temporary Password
                $pswd = $this->generatePassword();
                $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);
                // END: Generate Temporary Password

                $sql = "UPDATE aider_user_rider SET Password_Hash='" . $pswdHash . "', First_Login='YES' WHERE Email_Address= '" . $email . "'";
                $statement = $connection->query($sql);

                $name = $responseName['data'];

                if($statement) {
                    // Send Confirmation Email

                    $mail = new PHPMailer(true);

                    $content = $this->getResetPasswordEmail($name, $email, $pswd, "https://aider.my/", GOOGLE_PLAY_STORE_LINK, APPLE_APP_STORE_LINK);

                    try {
                        $mail->isSMTP();
                        $mail->Mailer = "smtp";
                        $mail->SMTPAuth   = TRUE;
                        $mail->SMTPSecure = "tls";
                        $mail->Port       = 587;
                        $mail->Host       = "smtp.gmail.com";
                        $mail->Username   = "aider.delivery@gmail.com";
                        $mail->Password   = "Aider@2020";

                        $mail->setFrom('aider.delivery@gmail.com', 'myAider');
                        $mail->addAddress($email);
                        $mail->addReplyTo('info@aider.my', 'myAider');

                        $mail->isHTML(true);
                        $mail->Subject = 'myAider | Reset Password for ' . $name;
                        $mail->MsgHTML($content);
                        $mail->AltBody = 'You have recently requested a password reset. Here is the new password: ' . $pswd;

                        if($mail->send()) {
                            $response['error'] = false;
                            $response['email'] = $email;
                        } else {
                            $response['error'] = true;
                            $response['message'] = "Message could not be sent.";
                        }
                    } catch (Exception $e) {
                        $response['error'] = true;
                        $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "There was an error while trying to reset your password.";
                }

                $connection->close();
            } else {
                $response['error'] = true;
                $response['message'] = $responseName['message'];
            }
        }

        return $response;
    }

    function generatePassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890?@!&$%#';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 12; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    private function checkIfEmailExists($type, $email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($type === "ADMIN") {
            $sql = "SELECT * FROM aider_user_admin WHERE Email_Address = '$email'";
        } elseif($type === "CUSTOMER") {
            $sql = "SELECT * FROM aider_user_customer WHERE Email_Address = '$email'";
        } elseif($type === "MERCHANT") {
            $sql = "SELECT * FROM aider_user_merchant WHERE Email_Address = '$email'";
        } else {
            $sql = "SELECT * FROM aider_user_rider WHERE Email_Address = '$email'";
        }

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There is no account associated with this email address.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function getResetPasswordEmail($name, $email, $pass, $WebsiteLink, $AndroidLink, $AppleLink) {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html style=\"width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">
 <head> 
  <meta charset=\"UTF-8\"> 
  <meta content=\"width=device-width, initial-scale=1\" name=\"viewport\"> 
  <meta name=\"x-apple-disable-message-reformatting\"> 
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"> 
  <meta content=\"telephone=no\" name=\"format-detection\">
  <!--[if (mso 16)]>
    <style type=\"text/css\">
    a {text-decoration: none;}
    </style>
    <![endif]--> 
  <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--> 
  <!--[if !mso]>-->
  <link href=\"https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i\" rel=\"stylesheet\"> 
  <!--<![endif]--> 
  <style type=\"text/css\">
@media only screen and (max-width:600px) {p, ul li, ol li, a { font-size:16px!important; line-height:150%!important } h1 { font-size:30px!important; text-align:center; line-height:120%!important } h2 { font-size:26px!important; text-align:center; line-height:120%!important } h3 { font-size:20px!important; text-align:center; line-height:120%!important } h1 a { font-size:30px!important } h2 a { font-size:26px!important } h3 a { font-size:20px!important } .es-menu td a { font-size:14px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:14px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:14px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class=\"gmail-fix\"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:inline-block!important } a.es-button { font-size:20px!important; display:inline-block!important } .es-btn-fw { border-width:10px 0px!important; text-align:center!important } .es-adaptive table, .es-btn-fw, .es-btn-fw-brdr, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0px!important } .es-m-p0r { padding-right:0px!important } .es-m-p0l { padding-left:0px!important } .es-m-p0t { padding-top:0px!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } .es-desk-hidden { display:table-row!important; width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } .es-desk-menu-hidden { display:table-cell!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } }
#outlook a {
	padding:0;
}
.ExternalClass {
	width:100%;
}
.ExternalClass,
.ExternalClass p,
.ExternalClass span,
.ExternalClass font,
.ExternalClass td,
.ExternalClass div {
	line-height:100%;
}
.es-button {
	mso-style-priority:100!important;
	text-decoration:none!important;
}
a[x-apple-data-detectors] {
	color:inherit!important;
	text-decoration:none!important;
	font-size:inherit!important;
	font-family:inherit!important;
	font-weight:inherit!important;
	line-height:inherit!important;
}
.es-desk-hidden {
	display:none;
	float:left;
	overflow:hidden;
	width:0;
	max-height:0;
	line-height:0;
	mso-hide:all;
}
</style> 
 </head> 
 <body style=\"width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"> 
  <div class=\"es-wrapper-color\" style=\"background-color:#FFFFFF;\"> 
   <!--[if gte mso 9]>
			<v:background xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"t\">
				<v:fill type=\"tile\" color=\"#ffffff\"></v:fill>
			</v:background>
		<![endif]--> 
   <table class=\"es-wrapper\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"> 
     <tr style=\"border-collapse:collapse;\"> 
      <td valign=\"top\" style=\"padding:0;Margin:0;\"> 
       <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td align=\"center\" style=\"padding:0;Margin:0;\"> 
           <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"padding:0;Margin:0;\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"600\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:3px;background-color:#FCFCFC;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fcfcfc\" role=\"presentation\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td class=\"es-m-txt-l\" align=\"left\" style=\"padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:25px;\"><h2 style=\"Margin:0;line-height:46px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:38px;font-style:normal;font-weight:normal;color:#74A4CC;text-align:left;\">myAider<span style=\"font-size:39px;\"></span></h2></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td class=\"es-m-txt-l\" align=\"left\" style=\"padding:0;Margin:0;padding-top:10px;padding-left:20px;padding-right:20px;\"><h2 style=\"Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:24px;font-style:normal;font-weight:normal;color:#333333;\">Reset Password for " . $name . "</h2></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td bgcolor=\"#fcfcfc\" align=\"left\" style=\"padding:0;Margin:0;padding-top:10px;padding-left:20px;padding-right:20px;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#333333;text-align:justify;\">Your password has been reset. Login in with the current password and you'll be prompted to change it in the myAider application.</p></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
             <tr style=\"border-collapse:collapse;\"> 
              <td style=\"padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;background-color:#FCFCFC;\" bgcolor=\"#fcfcfc\" align=\"left\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"560\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-color:#EFEFEF;border-style:solid;border-width:1px;border-radius:3px;background-color:#FFFFFF;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" role=\"presentation\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;padding-bottom:15px;padding-top:20px;\"><h3 style=\"Margin:0;line-height:22px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:18px;font-style:normal;font-weight:normal;color:#333333;\">Your account information:</h3></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#74A4CC;\">Email: " . $email . "<br>Temporary Password: " . $pass . "</p></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"Margin:0;padding-left:10px;padding-right:10px;padding-top:20px;padding-bottom:20px;\"><span class=\"es-button-border\" style=\"border-style:solid;border-color:transparent;background:#74a4cc;border-width:0px;display:inline-block;border-radius:3px;width:auto;\"><a href=\"" . $WebsiteLink . "\" class=\"es-button\" target=\"_blank\" style=\"mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:17px;color:#333333;border-style:solid;border-color:#74a4cc;border-width:10px 20px 10px 20px;display:inline-block;background:#74a4cc;border-radius:3px;font-weight:normal;font-style:normal;line-height:20px;width:auto;text-align:center;\">Log In Now</a></span></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table> 
       <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td align=\"center\" style=\"padding:0;Margin:0;\"> 
           <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FCFCFC;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fcfcfc\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"Margin:0;padding-left:20px;padding-right:20px;padding-bottom:25px;padding-top:40px;\"> 
               <!--[if mso]><table width=\"560\" cellpadding=\"0\" 
                            cellspacing=\"0\"><tr><td width=\"274\" valign=\"top\"><![endif]--> 
               <table class=\"es-left\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td class=\"es-m-p0r es-m-p20b\" width=\"254\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"left\" style=\"padding:0;Margin:0;\"><h3 style=\"Margin:0;line-height:20px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:17px;font-style:normal;font-weight:normal;color:#333333;text-align:justify;\">Download the app and start your experience with us.</h3></td> 
                     </tr> 
                   </table></td> 
                  <td class=\"es-hidden\" width=\"20\" style=\"padding:0;Margin:0;\"></td> 
                 </tr> 
               </table> 
               <!--[if mso]></td><td width=\"133\" valign=\"top\"><![endif]--> 
               <table class=\"es-left\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td class=\"es-m-p20b\" width=\"133\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;font-size:0;\"><a target=\"_blank\" href=\"" . $AppleLink . "\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:14px;text-decoration:none;color:#F6A1B4;\"><img src=\"https://hhefbb.stripocdn.email/content/guids/CABINET_e48ed8a1cdc6a86a71047ec89b3eabf6/images/92051534250512328.png\" alt=\"App Store\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;\" class=\"adapt-img\" title=\"App Store\" width=\"133\"></a></td> 
                     </tr> 
                   </table></td>
                 </tr> 
               </table> 
               <!--[if mso]></td><td width=\"20\"></td><td width=\"133\" valign=\"top\"><![endif]--> 
               <table class=\"es-right\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"133\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;font-size:0;\"><a target=\"_blank\" href=\"" . $AndroidLink . "\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:14px;text-decoration:none;color:#F6A1B4;\"><img class=\"adapt-img\" src=\"https://hhefbb.stripocdn.email/content/guids/CABINET_e48ed8a1cdc6a86a71047ec89b3eabf6/images/82871534250557673.png\" alt=\"Google Play\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;\" title=\"Google Play\" width=\"133\"></a></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table> 
               <!--[if mso]></td></tr></table><![endif]--></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table> 
       <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td align=\"center\" style=\"padding:0;Margin:0;\"> 
           <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"padding:0;Margin:0;\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"600\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFF4F7;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fff4f7\" role=\"presentation\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td bgcolor=\"#93c9fc\" align=\"center\" style=\"Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;font-size:0;\"> 
                       <table width=\"100%\" height=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                         <tr style=\"border-collapse:collapse;\"> 
                          <td style=\"padding:0;Margin:0px;border-bottom:1px solid #93C9FC;background:none 0% 0% repeat scroll#FFFFFF;height:1px;width:100%;margin:0px;\"></td> 
                         </tr> 
                       </table></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table> 
       <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-footer\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td style=\"padding:0;Margin:0;background-color:#666666;\" bgcolor=\"#666666\" align=\"center\"> 
           <table class=\"es-footer-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#666666;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#666666\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px;\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"560\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td esdev-links-color=\"#999999\" align=\"center\" style=\"padding:0;Margin:0;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#FFFFFF;\">You are receiving this email to reset your account's password at myAider</p></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" class=\"es-m-txt-c\" style=\"Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;font-size:0;\"> 
                       <table border=\"0\" width=\"20%\" height=\"100%\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                         <tr style=\"border-collapse:collapse;\"> 
                          <td style=\"padding:0;Margin:0px;border-bottom:1px solid #CCCCCC;background:none;height:1px;width:100%;margin:0px;\"></td> 
                         </tr> 
                       </table></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td esdev-links-color=\"#999999\" align=\"center\" style=\"padding:0;Margin:0;padding-bottom:5px;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#FFFFFF;\">Copyright &copy; " . date("Y") . " myAider | Powered by iWeb Solutions.</p></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table></td> 
     </tr> 
   </table> 
  </div>  
 </body>
</html>";
    }
}
