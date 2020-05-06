<?php


class UserModal {

    function getName() {
        if($_SESSION['User_Type'] === 'STUDENT') {
            $StudentModal = new StudentModal();
            $responseStudent = $StudentModal->getStudentInformation('Full_Name');

            if(!$responseStudent['error']) {
                $response['error'] = false;
                $response['data'] = $responseStudent['data'];
            } else {
                $response['error'] = true;
                $response['message'] = $responseStudent['message'];
            }
        } elseif($_SESSION['User_Type'] === 'PARENT') {
            $ParentModal = new ParentModal();
            $responseParent = $ParentModal->getParentInformation('Full_Name');

            if(!$responseParent['error']) {
                $response['error'] = false;
                $response['data'] = $responseParent['data'];
            } else {
                $response['error'] = true;
                $response['message'] = $responseParent['message'];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Your user type is incorrect. Something must be wrong at our side.";
        }

        return $response;
    }

}