<?php


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
                                            <a class=\"dropdown-item btn-approve\" id='" . $row["Email_Address"] . "' onclick='approveButtonClick();'>Approve</a>
                                            <a class=\"dropdown-item btn-deny\" id='" . $row["Email_Address"] . "' onclick='denyButtonClick();'>Deny</a>
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
            $response['error'] = false;
            $response['message'] = "The rider has been approved.";

            // Send Confirmation Email
            $to = $email;
            $subject = "Aider Rider | Confirmation Email for " . $first_name;

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: Aider<info@aider.my>' . "\r\n";

            $content = $Register->getCustomerConfirmationEmailContent($name, $email, $pswd, "aider.my/riders/", "", "");

            if(mail($to, $subject, $content, $headers)) {
                $response['error'] = false;
                $response['email'] = $email;
            } else {
                $response['error'] = true;
                $response['message'] = "You've been registered but there was an issue while sending your confirmation email.";
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
}
