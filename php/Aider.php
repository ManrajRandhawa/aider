<?php

// Defines
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/settings/Database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/settings/General.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/settings/Messages.php';

// Modals
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/modals/UserModal.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/modals/StudentModal.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/modals/ParentModal.php';

// UI
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/ui/Alerts.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/ui/Dashboard.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/ui/UI.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/DatabaseHandler.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/credentials/Credentials.php';

include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/modals/ParentModal.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/aider/php/modals/StudentModal.php';

class Aider {

    function __construct() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    function getAlerts() {
        return new Alerts();
    }

    function getUI() {
        return new UI();
    }

    function getDatabaseHandler() {
        return new DatabaseHandler();
    }

    function getCredentials() {
        return new Credentials();
    }

    function getUserModal() {
        return new UserModal();
    }



}