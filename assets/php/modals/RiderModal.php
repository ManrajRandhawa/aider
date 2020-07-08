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

    function approveRider($email, $v_model, $v_pn) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $Register = new Register();

        // START: Generate Temporary Password
        $pswd = $Register->generatePassword();
        $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);
        // END: Generate Temporary Password

        $sql = "UPDATE aider_user_rider SET Password_Hash='" . $pswdHash . "', Approval_Status='APPROVED', Vehicle_Model='" . $v_model . "', Vehicle_Plate_Number='" . $v_pn . "', First_Login='YES' WHERE Email_Address='" . $email . "'";
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
}