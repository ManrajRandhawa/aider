<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['paid_amount'])) {
        if($_POST['paid'] === 'true') {
            $amount = ($_POST['paid_amount'] / 100);
            $Aider->getUserModal()->getCustomerModal()->addMoneyToWallet($_POST['email'], $_POST['id'], $amount, $_POST['paid_at']);
        }
    }