<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

    $AM = new AdminModal();
    $AM->printFinanceStatement($_POST['Start_Date'], $_POST['End_Date']);
?>

<!DOCTYPE html>
<html lang="en">

    <head></head>

    <body class="bg-light">
        <script>
            window.open('home.php');
        </script>
    </body>

</html>