<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/modals/CredentialModal.php";

    if(isset($_POST['User_Email']) && isset($_POST['User_Name']) && isset($_POST['Bank_Code']) && isset($_POST['Amount'])) {
        $credentialsModal = new CredentialModal();

        echo $credentialsModal->createBill($_POST['User_Email'], $_POST['User_Name'], $_POST['Bank_Code'], $_POST['Amount'])['data'];
    }

