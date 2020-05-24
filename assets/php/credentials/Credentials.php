<?php

include_once 'Login.php';
include_once 'Register.php';
include_once 'Sessions.php';

class Credentials {

    function getLogin() {
        return new Login();
    }

    function getRegister() {
        return new Register();
    }

    function getSessions() {
        return new Sessions();
    }

    function getEmail() {
        return new Email();
    }

}