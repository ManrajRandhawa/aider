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

        <!-- Cash Out Modal -->
        <div class='modal fade' id="cashout-modal" tabindex='-1' role='dialog' aria-labelledby='cashOutModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='cashOutModalLabel'>Earnings > Cash Out</h5>
                    </div>
                    <div class='modal-body'>
                        <form method='post'>
                            <div class='form-group'>
                                <label for='bank' class='col-form-label'>Bank:</label>
                                <input type='text' class='form-control' name='bank' id='bank' required>
                            </div>
                            <div class='form-group'>
                                <label for='bank-acc' class='col-form-label'>Account Number (numbers only):</label>
                                <input type='number' class='form-control' name='bank-acc' id='bank-acc' required>
                            </div>
                            <div class='form-group'>
                                <label for='cashout-amt' class='col-form-label'>Amount (RM):</label>
                                <input type='number' class='form-control' name='cashout-amt' id='cashout-amt' required>
                            </div>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='cashout-btn'>Cash Out</button>
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
                                <h6>RM <span class="h3 font-weight-bold" id="wallet-balance"></span></h6>
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
                        <h4 class="font-weight-bold">Driver Trips <span class="h6">(Last 3 trips)</span></h4>
                    </div>

                    <!-- Driver Trips -->
                    <div class="col-12">
                        <div id="driver-trips-container">

                        </div>
                    </div>
                </div>
            </div>

            <div class="container mt-4">
                <div class="row">
                    <div class="col-12">
                        <h4 class="font-weight-bold">Other Trips <span class="h6">(Last 3 trips)</span></h4>
                    </div>

                    <!-- Other Trips -->
                    <div class="col-12">
                        <div id="other-trips-container">

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
                    <div class="row rounded bg-white pt-3 pb-2 shadow-lg" id="rider-navigation-main">
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
        <div class="toast-container shadow-lg">

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
                getEarningsDashboardJS();
            });

        </script>
    </body>

</html>