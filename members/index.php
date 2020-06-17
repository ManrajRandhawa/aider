<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Login</title>

        <script src="../assets/js/Credentials.js"></script>
        <script>
            getLoginJS();
        </script>

        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300&display=swap" rel="stylesheet">
        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="container-fluid">
        <!-- Main Content -->
        <div class="row">
            <div class="col-12 bg-primary" style="height: 90vh;">
                <img src="../assets/images/aider-logo.png" class="d-block ml-auto mr-auto bg-primary rounded p-3" style="width: 150px; height: 150px;" />
                <h1 class=" text-center text-light text-uppercase" style="font-family: 'Barlow', sans-serif;"><?php echo SITE_NAME ?></h1>

                <div class="row">
                    <div class="col-12 mt-5">
                        <h3 class="text-center mt-5 font-weight-bold text-light" style="font-family: 'Barlow', sans-serif;">FOOD. PARCEL DELIVERY.</h3>
                        <h3 class="text-center font-weight-bold text-light" style="font-family: 'Barlow', sans-serif;">WE GOT IT!</h3>

                        <h4 class="text-center mt-5 text-light" style="font-family: 'Barlow', sans-serif;">Join us now.</h4>
                    </div>

                </div>

            </div>
            <div class="col-12 bg-light" style="height: 10vh;">
                <div class="row mt-2">
                    <div class="col-6">
                        <button class="btn btn-lg btn-outline-primary w-100" id="sign-in-btn">SIGN IN</button>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-lg btn-primary w-100" id="sign-up-btn">SIGN UP</button>
                    </div>
                </div>
            </div>
        </div>


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script>
            $(document).ready(function(){
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);

                $('#sign-in-btn').click(function() {
                    window.location.href = "signin.php";
                });

                $('#sign-up-btn').click(function() {
                    window.location.href = "signup.php";
                });
            });
        </script>
    </body>

</html>