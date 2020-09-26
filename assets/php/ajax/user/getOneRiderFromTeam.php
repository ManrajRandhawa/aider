<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Team_ID'])) {
        $responseTeamMembers = $Aider->getUserModal()->getRiderModal()->getTeamInformationByID($_POST['Team_ID'], "Team_Members");

        $teamMemberOne = explode(",", $responseTeamMembers['data']);

        if (!$responseTeamMembers['error']) {
            echo $teamMemberOne[0];
        } else {
            echo "ERROR";
        }
    }