<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Sign In</title>

        <script src="../assets/js/Credentials.js"></script>
        <script>
            getLoginJS();
        </script>

        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300&display=swap" rel="stylesheet">
        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="container-fluid bg-light">

        <!-- Main Content -->

        <div class="row">
            <div class="col-12" style="height: 100vh;">
                <div class="row">
                    <div class="col-2 mt-4 text-center">
                        <i class="fas fa-long-arrow-alt-left fa-2x ml-2 text-primary" id="back-btn"></i>
                    </div>
                    <div class="col-10"></div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <img src="../assets/images/aider-logo-alt.png" class="d-block ml-auto mr-auto p-3" style="width: auto; height: 180px;" />
                    </div>
                </div>
                <div class="" id="view-1">
                    <h3 class="text-center font-weight-bold mt-5 text-primary" style="font-family: 'Barlow', sans-serif;">SIGN IN</h3>

                    <div class="row">
                        <div class="col-12">
                            <form method="post">
                                <div class="input-group mt-3">
                                    <input type="email" class="form-control" name="user-email" placeholder="Email Address" required />
                                </div>
                                <div class="input-group mt-3">
                                    <input type="password" class="form-control" name="user-pswd" placeholder="Password" required />
                                </div>
                                <button name="btn-login" class="btn btn-outline-primary btn-block mt-5">Sign In</button>
                            </form>

                            <h6 class="text-center mt-3">New to myAider? <a href="signup.php" class="text-primary text-decoration-none">Create an account.</a></h6>

                            <div class="fixed-bottom mb-2">
                                <div class="w-75 d-block ml-auto mr-auto">
                                    <button class="btn btn-outline-primary w-100" id="forgot-password-btn-bottom"><i class="fas fa-lock mr-2"></i> Forgot your password?</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-none" id="view-2">
                    <h3 class="text-center font-weight-bold mt-5 mb-5 text-primary" style="font-family: 'Barlow', sans-serif;">RESET PASSWORD</h3>

                    <div class="row">
                        <div class="col-12">
                            <form method="post">
                                <div class="input-group mt-3">
                                    <input type="email" class="form-control" name="user-email-reset" placeholder="Email Address" required />
                                </div>
                                <button name="btn-reset" class="btn btn-outline-primary btn-block mt-3">Reset Password</button>
                            </form>

                            <div class="fixed-bottom mb-2">
                                <div class="w-75 d-block ml-auto mr-auto">
                                    <button class="btn btn-outline-primary w-100" id="sign-in-btn-bottom"><i class="fas fa-sign-in-alt mr-2"></i> Sign In</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="toast-container">
            <?php
                if(isset($_POST['btn-login'])) {
                    $email = $_POST['user-email'];
                    $pswd = $_POST['user-pswd'];

                    $response = $Aider->getCredentials()->getLogin()->loginCustomer($email, $pswd);

                    if(!$response['error']) {
                        echo "
                            <script>
                                window.localStorage.setItem('User_Email', '" . $response['email'] . "');
                                window.location.href = 'index.php';
                            </script>";
                    } else {
                        echo $Aider->getAlerts()->sendToastCredentials("Oops! Something went wrong.", $response['message']);
                    }
                }

                if(isset($_POST['btn-reset'])) {
                    $email = $_POST['user-email-reset'];

                    $responseReset = $Aider->getUserModal()->getCustomerModal()->resetPassword($email);

                    if($responseReset['error']) {
                        echo $Aider->getAlerts()->sendToastCredentials('Oops! Something went wrong.', $responseReset['message']);
                    } else {
                        echo $Aider->getAlerts()->sendToastCredentials('Reset successful.', 'If the email given is associated with any account, an email would be sent with the new password.');
                    }
                }
            ?>
        </div>

        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script>
            $(document).ready(function(){
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);

                $('#back-btn').click(function() {
                    window.location.href = "index.php";
                });

                $('.toast').toast('show');

                setTimeout(function() {
                    $('.toast-container').html("");
                }, 5000);


                $('#forgot-password-btn-bottom').on('click', function() {
                    if(!$('#view-1').hasClass('d-none')) {
                        $('#view-1').addClass('d-none');
                        $('#view-2').removeClass('d-none');
                    }
                });

                $('#sign-in-btn-bottom').on('click', function() {
                    if(!$('#view-2').hasClass('d-none')) {
                        $('#view-2').addClass('d-none');
                        $('#view-1').removeClass('d-none');
                    }
                });

            });
        </script>
    </body>

</html>