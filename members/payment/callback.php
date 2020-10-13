<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";
    include_once $_SERVER['DOCUMENT_ROOT'] . ROOT_DIR . '/assets/php/modals/CredentialModal.php';

    $Aider = new Aider();

    // Receive $_POST request
if(isset($_GET)) {
    echo "<pre>";
    echo $_GET['billplz']['id'];
    echo "</pre>";
}
