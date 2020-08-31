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
                    <div class="col-12 mt-3">
                        <h3 class="font-weight-bold">Earnings</h3>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-12 mt-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-primary font-weight-bold">Wallet Balance</h5>
                                <h6>RM <span class="h3 font-weight-bold" id="wallet-balance">28.50</span></h6>
                            </div>
                            <div class="card-footer bg-white" id="top-up-wallet">
                                <span class="card-text text-dark h6">Cash out</span>
                                <span class="float-right"><i class="fas fa-chevron-right"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mt-4">
                <div class="row">
                    <div class="col-12">
                        <h4 class="font-weight-bold">Trips</h4>
                    </div>

                    <!-- Trips -->
                    <div class="col-12 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-7">
                                        <h4 class="card-title text-success font-weight-bold mt-1">Trip #1</h4>
                                    </div>
                                    <div class="col-5">
                                        <h6 class="text-right">RM <span class="h3 text-success font-weight-bold">12.50</span></h6>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-center">123, Jalan Kebun 25, Taman Jasmani, Selangor.</h6>
                                    </div>
                                    <div class="col-12 text-center">
                                        <i class="fas fa-angle-double-down fa-lg text-success mt-1 mb-2"></i>
                                    </div>
                                    <div class="col-12">
                                        <h6 class="text-center">53, Jalan Industri, Taman Kebun, Selangor.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-7">
                                        <h4 class="card-title text-success font-weight-bold mt-1">Trip #2</h4>
                                    </div>
                                    <div class="col-5">
                                        <h6 class="text-right">RM <span class="h3 text-success font-weight-bold">9.50</span></h6>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-center">123, Jalan Kebun 25, Taman Jasmani, Selangor.</h6>
                                    </div>
                                    <div class="col-12 text-center">
                                        <i class="fas fa-angle-double-down fa-lg text-success mt-1 mb-2"></i>
                                    </div>
                                    <div class="col-12">
                                        <h6 class="text-center">53, Jalan Industri, Taman Kebun, Selangor.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-7">
                                        <h4 class="card-title text-success font-weight-bold mt-1">Trip #3</h4>
                                    </div>
                                    <div class="col-5">
                                        <h6 class="text-right">RM <span class="h3 text-success font-weight-bold">6.50</span></h6>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-center">123, Jalan Kebun 25, Taman Jasmani, Selangor.</h6>
                                    </div>
                                    <div class="col-12 text-center">
                                        <i class="fas fa-angle-double-down fa-lg text-success mt-1 mb-2"></i>
                                    </div>
                                    <div class="col-12">
                                        <h6 class="text-center">53, Jalan Industri, Taman Kebun, Selangor.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <br/>
        <br/>
        <br/>
        <br/>
        <br/>


        <!-- START: Bottom Navigation -->
        <div class="container mb-4 fixed-bottom" style="z-index: 0;" id="container-location">
            <div class="row">
                <div class="col-12 ml-2" style="max-width: 96vw;">
                    <div class="row rounded bg-white pt-3 pb-2" id="rider-navigation-main">
                        <div class="col-3 text-center">
                            <span>
                                <i class="fas fa-coins fa-lg text-primary"></i>
                                <span style="font-size: 11pt;" class="text-dark">Earnings</span>
                            </span>
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
                            <a href="account.php" class="text-decoration-none">
                                <span>
                                    <i class="far fa-user-circle fa-lg text-primary"></i>
                                    <span style="font-size: 11pt;" class="text-dark">Account</span>
                                </span>
                            </a>
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