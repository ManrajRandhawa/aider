<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php echo SITE_NAME; ?> | Register</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="container-fluid bg-light">

        <?php
            if(isset($_POST['btn-reg'])) {
                $first_name = $_POST['user-firstname'];
                $last_name = $_POST['user-lastname'];
                $email = $_POST['user-email'];
                $phone_num = $_POST['user-phone-num'];

                $response = $Aider->getCredentials()->getRegister()->registerCustomer($first_name, $last_name, $email, $phone_num, 0);

                if(!$response['error']) {
                    echo $Aider->getAlerts()->sendSuccessMessage("You've been registered successfully. A confirmation email has been sent to " . $response['email']);
                } else {
                    echo $Aider->getAlerts()->sendErrorMessage($response['message']);
                }
            }
        ?>

        <!-- Main Content -->
        <div class="row mt-n4 mt-lg-0">
            <div class="col col-lg-4"></div>
            <div class="col-12 col-lg-4 mt-5">
                <div class="card">
                    <img src="../assets/images/aider-logo.png" class="card-img-top" />
                    <div class="card-body">
                        <form method="post">
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" name="user-firstname" placeholder="First Name" required />
                                <input type="text" class="form-control" name="user-lastname" placeholder="Last Name" required />
                            </div>
                            <div class="input-group mt-3">
                                <input type="email" class="form-control" name="user-email" placeholder="Email Address" required />
                            </div>
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" name="user-phone-num" placeholder="Phone Number (e.g. 0122233445)" required />
                            </div>
                            <button name="btn-reg" class="btn btn-outline-primary btn-block mt-5">Register</button>
                        </form>

                        <h6 class="text-center mt-3">Already have an account? <a href="index.php" class="text-primary">Log in.</a></h6>
                    </div>
                </div>
            </div>
            <div class="col col-lg-4"></div>
        </div>


        <?php
            echo $Aider->getUI()->getBootstrapScripts();

        ?>
        <script>
            $(document).ready(function(){
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);
            });
        </script>
    </body>

</html>