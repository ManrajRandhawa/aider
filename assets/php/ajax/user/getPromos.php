<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    echo $Aider->getUserModal()->getPromoModal()->getPromos();