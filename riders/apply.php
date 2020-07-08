<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Apply Now</title>

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
                    <div class="col-3"></div>
                    <div class="col-6 mt-2">
                        <img src="../assets/images/aider-logo.png" class="d-block ml-auto mr-auto mt-4 bg-primary rounded p-3" style="width: 150px; height: 150px;" />
                    </div>
                    <div class="col-3"></div>
                </div>
                <h3 class="text-center font-weight-bold mt-5 text-primary" style="font-family: 'Barlow', sans-serif;">APPLY NOW</h3>

                <div class="row">
                    <div class="col-12">
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
                            <button name="btn-reg" id="btn-reg" class="btn btn-outline-primary btn-block mt-5">Apply</button>
                        </form>

                        <h6 class="text-center mt-3">Already a rider? <a href="signin.php" class="text-primary">Sign in.</a></h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="toast-container">
            <?php
                if(isset($_POST['btn-reg'])) {
                    $first_name = $_POST['user-firstname'];
                    $last_name = $_POST['user-lastname'];
                    $email = $_POST['user-email'];
                    $phone_num = $_POST['user-phone-num'];

                    $name = $first_name . " " . $last_name;

                    $response = $Aider->getUserModal()->getRiderModal()->applyRider($name, $email, $phone_num);

                    if(!$response['error']) {
                        echo $Aider->getAlerts()->sendToastCredentials("You've applied to become an Aider Rider", "Your application has been sent to us. You'll hear from us soon.");
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