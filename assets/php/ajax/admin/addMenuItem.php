<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['m_id']) && isset($_POST['m_name']) && isset($_POST['m_cat_edited']) && isset($_POST['m_desc']) && isset($_POST['m_price']) && isset($_FILES['m_image'])) {
        $response = $Aider->getUserModal()->getFoodModal()->addMenuItem($_POST['m_id'], $_POST['m_name'], $_POST['m_cat_edited'], $_POST['m_desc'], $_POST['m_price'], addslashes(file_get_contents($_FILES['m_image']['tmp_name'])));
        if(!$response['error']) {
            echo "OK";
        } else {
            echo $response['message'];
        }
    }