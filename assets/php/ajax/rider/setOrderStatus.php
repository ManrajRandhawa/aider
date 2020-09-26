<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['Order_Type']) && isset($_POST['Order_ID']) && isset($_POST['Status'])) {
        $response = $Aider->getUserModal()->getOrderModal()->setOrderStatus($_POST['Order_Type'], $_POST['Order_ID'], $_POST['Status']);

        echo $response['error'];
    }