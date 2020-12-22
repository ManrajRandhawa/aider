<?php

include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

if(isset($_POST['title']) && isset($_POST['body']) && isset($_POST['url'])) {
    $Aider = new Aider();

    return $Aider->getUserModal()->getAdminModal()->pushMessage($_POST['title'], $_POST['body'], $_POST['url']);
}
