<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Rider | Home</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="bg-light">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <!-- Main Content -->

        <!-- Change Email Modal -->
        <div class='modal fade' id="change-email-modal" tabindex='-1' role='dialog' aria-labelledby='changeEmailModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='changeEmailModalLabel'>Account > Change Email</h5>
                    </div>
                    <div class='modal-body'>
                        <form method='post'>
                            <div class='form-group'>
                                <label for='chg-email' class='col-form-label'>New Email:</label>
                                <input type='email' class='form-control' name='chg-email' id='chg-email'>
                            </div>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='chg-email-btn'>Change Email</button>
                    </div>
                </div>

                <!-- START: Toast Messages Area -->
                <div class="toast-container-modal" style="z-index: 9999;">

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Change Password Modal -->
        <div class='modal fade' id="change-pass-modal" tabindex='-1' role='dialog' aria-labelledby='changePasswordModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='changePasswordModalLabel'>Account > Change Password</h5>
                    </div>
                    <div class='modal-body'>
                        <form method='post'>
                            <div class='form-group'>
                                <label for='chg-pass' class='col-form-label'>New Password:</label>
                                <input type='password' class='form-control' name='chg-pswd' id='chg-pass'>
                            </div>
                            <div class='form-group'>
                                <label for='chg-confirm-pass' class='col-form-label'>Confirm New Password:</label>
                                <input type='password' class='form-control' name='chg-confirm-pswd' id='chg-confirm-pass'>
                            </div>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='chg-pass-btn'>Change Password</button>
                    </div>
                </div>

                <!-- START: Toast Messages Area -->
                <div class="toast-container-modal" style="z-index: 9999;">

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Change Phone Number Modal -->
        <div class='modal fade' id="change-num-modal" tabindex='-1' role='dialog' aria-labelledby='changeNumModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='changeNumModalLabel'>Account > Change Phone Number</h5>
                    </div>
                    <div class='modal-body'>
                        <form method='post'>
                            <div class='form-group'>
                                <label for='chg-num' class='col-form-label'>New Phone Number (e.g. 0122233445):</label>
                                <input type='text' class='form-control' name='chg-num' id='chg-num'>
                            </div>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='chg-num-btn'>Change Phone Number</button>
                    </div>
                </div>

                <!-- START: Toast Messages Area -->
                <div class="toast-container-modal" style="z-index: 9999;">

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Account -->
        <div class="bg-light" id="rider-account">
            <!-- Header -->
            <div class="container bg-light">
                <div class="row">
                    <div class="col-12 mt-3 mb-4">
                        <h3 class="font-weight-bold">Account</h3>
                    </div>
                </div>
            </div>

            <!-- Header: 1st View -->
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <h4 class="font-weight-bold mt-4 text-dark" id="user-name"></h4>
                    </div>
                    <div class="col-6"></div>
                </div>
            </div>

            <hr/>

            <div class="container mt-5">
                <a class="text-decoration-none" data-toggle="modal" data-target="#change-email-modal">
                    <div class="row">
                        <div class="col-12">
                            <span class="h6 text-black-50">Change Email</span>
                            <i class="fas fa-chevron-right text-black-50 float-right"></i>
                        </div>
                    </div>
                </a>

                <hr/>

                <a class="text-decoration-none" data-toggle="modal" data-target="#change-pass-modal">
                    <div class="row">
                        <div class="col-12">
                            <span class="h6 text-black-50">Change Password</span>
                            <i class="fas fa-chevron-right text-black-50 float-right"></i>
                        </div>
                    </div>
                </a>

                <hr/>

                <a class="text-decoration-none" data-toggle="modal" data-target="#change-num-modal">
                    <div class="row">
                        <div class="col-12">
                            <span class="h6 text-black-50">Change Phone Number</span>
                            <i class="fas fa-chevron-right text-black-50 float-right"></i>
                        </div>
                    </div>
                </a>

                <hr/>

                <a class="text-decoration-none" id="logout">
                    <div class="row mt-5">
                        <div class="col-12">
                            <span class="h6 text-danger">Logout</span>
                            <i class="fas fa-chevron-right text-black-50 float-right"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        <!-- START: Bottom Navigation -->
        <div class="container mb-4 fixed-bottom" style="z-index: 0;" id="container-location">
            <div class="row">
                <div class="col-12 ml-2" style="max-width: 96vw;">
                    <div class="row rounded bg-white pt-3 pb-2" id="rider-navigation-main">
                        <div class="col-3 text-center">
                            <a href="earnings.php" class="text-decoration-none">
                                <span>
                                    <i class="fas fa-coins fa-lg text-primary"></i>
                                    <span style="font-size: 11pt;" class="text-dark">Earnings</span>
                                </span>
                            </a>
                        </div>
                        <div class="col-6 text-center">
                            <!-- Home Button -->
                            <a href="home.php" class="text-decoration-none">
                                <div class="btn btn-outline-success w-100" id="btn-home">
                                    <span>Home</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <span>
                                <i class="far fa-user-circle fa-lg text-primary"></i>
                                <span style="font-size: 11pt;" class="text-dark">Account</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Bottom Navigation -->


        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Rider.js"></script>
        <script src="../assets/js/Rider/RiderLogic.js"></script>

        <script>
            $(document).ready(function() {
                getRiderDashboardJS();
                getAccountDashboardJS();
            });

        </script>
    </body>

</html>