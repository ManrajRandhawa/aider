<?php


class Sessions {

    function setSession($sessionName, $sessionValue) {
        if(isset($_SESSION[$sessionName])) {
            $response['error'] = true;
            $response['message'] = "This session has already been set.";
        } else {
            $_SESSION[$sessionName] = $sessionValue;
            $response['error'] = false;
        }
    }
}