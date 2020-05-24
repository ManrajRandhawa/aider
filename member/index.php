<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php echo SITE_NAME; ?> | Login</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="container-fluid bg-light">

        <?php
            if(isset($_POST['btn-login'])) {
                $email = $_POST['user-email'];
                $pswd = $_POST['user-pswd'];

                $response = $Aider->getCredentials()->getLogin()->loginCustomer($email, $pswd);

                if(!$response['error']) {
                    echo "
                        <script>
                            window.localStorage.setItem('User_Email', '" . $response['email'] . "');
                        </script>";
                } else {
                    echo $Aider->getAlerts()->sendErrorMessage($response['message']);
                }
            }
        ?>
        <!-- Main Content -->
        <div class="row mt-3">
            <div class="col col-lg-4"></div>
            <div class="col-12 col-lg-4 mt-5">
                <div class="card">
                    <img src="../assets/images/aider-logo.png" class="card-img-top" />
                    <div class="card-body">
                        <form method="post">
                            <div class="input-group mt-3">
                                <input type="email" class="form-control" name="user-email" placeholder="Email Address" required />
                            </div>
                            <div class="input-group mt-3">
                                <input type="password" class="form-control" name="user-pswd" placeholder="Password" required />
                            </div>
                            <button name="btn-login" class="btn btn-outline-primary btn-block mt-5">Log In</button>
                        </form>

                        <h6 class="text-center mt-3">New to Aider? <a href="register.php" class="text-primary">Create an account.</a></h6>
                    </div>
                </div>
            </div>
            <div class="col col-lg-4"></div>
        </div>


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Credentials.js"></script>
        <script>
            $(document).ready(function(){
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);

                getLoginJS();
            });
        </script>
    </body>

</html>