<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    if(isset($_POST['r_name']) && isset($_POST['r_cat_val']) && isset($_POST['r_email']) && isset($_POST['r_hp']) && isset($_POST['r_tele_username']) && isset($_POST['r_loc']) && isset($_FILES['r_image'])) {
        $response = $Aider->getUserModal()->getFoodModal()->addRestaurant($_POST['r_name'], $_POST['r_cat_val'], $_POST['r_email'], $_POST['r_hp'], $_POST['r_tele_username'], $_POST['r_loc'], addslashes(file_get_contents($_FILES['r_image']['tmp_name'])));
        if(!$response['error']) {
            echo "OK";
        } else {
            echo $response['message'];
        }
    }