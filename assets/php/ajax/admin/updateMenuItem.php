<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['m_id_edit']) && isset($_POST['m_name_edit']) && isset($_POST['m_cat_edited']) && isset($_POST['m_desc_edit']) && isset($_POST['m_price_edit']) && isset($_FILES['m_image_edit'])) {
        $response = $Aider->getUserModal()->getFoodModal()->updateMenuItem($_POST['m_id_edit'], $_POST['m_name_edit'], $_POST['m_cat_edited'], $_POST['m_desc_edit'], $_POST['m_price_edit'], addslashes(file_get_contents($_FILES['m_image_edit']['tmp_name'])));
        if(!$response['error']) {
            echo "OK";
        } else {
            echo $response['message'];
        }
    }