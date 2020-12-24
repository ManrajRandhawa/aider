<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['r_id_edit']) && isset($_POST['r_cat_edited']) && isset($_POST['r_name_edit']) && isset($_POST['r_email_edit']) && isset($_POST['r_hp_edit']) && isset($_POST['r_tele_username_edit']) && isset($_POST['r_loc_edit']) && isset($_FILES['r_image_edit'])) {
        $response = $Aider->getUserModal()->getFoodModal()->updateRestaurant($_POST['r_id_edit'], $_POST['r_cat_edited'], $_POST['r_name_edit'], $_POST['r_email_edit'], $_POST['r_hp_edit'], $_POST['r_tele_username_edit'], $_POST['r_loc_edit'], addslashes(file_get_contents($_FILES['r_image_edit']['tmp_name'])));
        if(!$response['error']) {
            echo "OK";
        } else {
            echo $response['message'];
        }
    }