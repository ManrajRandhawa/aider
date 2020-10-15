<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Rider | Sign In</title>

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
                        <img src="../assets/images/aider-r-logo.png" class="d-block ml-auto mr-auto p-3" style="width: auto; height: 180px;" />
                    </div>
                </div>
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

                        <h6 class="text-center mt-3">Want to become a myAider Rider? <a href="apply.php" class="text-primary">Apply now.</a></h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="toast-container">
            <?php
                if(isset($_POST['btn-login'])) {
                    $email = $_POST['user-email'];
                    $pswd = $_POST['user-pswd'];

                    $response = $Aider->getUserModal()->getRiderModal()->loginRider($email, $pswd);

                    if(!$response['error']) {
                        echo "
                            <script>
                                window.localStorage.clear();
                                window.localStorage.setItem('User_Email', '" . $response['email'] . "');
                                window.location.href = 'home.php';
                            </script>";
                    } else {
                        echo $Aider->getAlerts()->sendToastCredentials("Oops! Something went wrong.", $response['message']);
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
            });
        </script>
    </body>

</html>