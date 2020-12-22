<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Admin | Home</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="bg-light">

        <?php
            $Aider->getUI()->getDashboard()->getDashboardHeader();
        ?>

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <!-- Finance Modal -->
        <div class='modal fade' id="finance-modal" tabindex='-1' role='dialog' aria-labelledby='changeFinanceModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='changeFinanceModalLabel'>Finance > Select Date Range</h5>
                    </div>
                    <form method='post' action="finance.php">
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label for='Start_Date' class='col-form-label'>From:</label>
                                <input type='date' class='form-control' name='Start_Date' id='Start_Date'>
                            </div>
                            <div class='form-group'>
                                <label for='End_Date' class='col-form-label'>To:</label>
                                <input type='date' class='form-control' name='End_Date' id='End_Date'>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='submit' class='btn btn-primary' id='download-finance-btn'>Download Financial Statement</button>
                        </div>
                    </form>

                </div>

                <!-- START: Toast Messages Area -->
                <div class="toast-container-modal" style="z-index: 9999;">

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Push Modal -->
        <div class='modal fade' id="push-modal" tabindex='-1' role='dialog' aria-labelledby='pushModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='pushModalLabel'>Push Message</h5>
                    </div>
                    <div class='modal-body'>
                        <div class='form-group'>
                            <label for='push_title' class='col-form-label'>Title:</label>
                            <input type='text' class='form-control' name='push_title' id='push_title'>
                        </div>
                        <div class='form-group'>
                            <label for='push_body' class='col-form-label'>Body:</label>
                            <textarea rows="5" class='form-control' name='push_body' id='push_body'></textarea>
                        </div>
                        <div class='form-group'>
                            <label for='push_url' class='col-form-label'>URL:</label>
                            <input type='text' class='form-control' name='push_url' id='push_url'>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='submit' class='btn btn-primary' id='push_btn'>Send</button>
                    </div>
                </div>

                <!-- START: Toast Messages Area -->
                <div class="toast-container-modal" style="z-index: 9999;">

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>


        <!-- Main Content -->
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <span class="h3 text-black-50">Hi, <span class="h3 text-dark font-weight-bold" id="admin-home-hello-name"></span></span>
                </div>
            </div>
        </div>

        <hr/>

        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <a class="text-decoration-none" href="teams.php">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-users mr-1"></i> Teams</h5>
                                <p class="card-text">Manage your drivers' and riders' teams.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 mt-3">
                    <a class="text-decoration-none" href="cashout.php">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-cash-register mr-1"></i> Cash Out Requests</h5>
                                <p class="card-text">Manage your cash out requests.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 mt-3">
                    <a class="text-decoration-none" href="reports.php">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-chart-line mr-1"></i> Report</h5>
                                <p class="card-text">View your daily statistics.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 mt-3">
                    <a class="text-decoration-none" href="#" data-toggle="modal" data-target="#finance-modal">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-money-check-alt mr-1"></i> Finance</h5>
                                <p class="card-text">View your financial statement.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 mt-3">
                    <a class="text-decoration-none" href="promotions.php">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-ad mr-1"></i> Promotions</h5>
                                <p class="card-text">Manage your promotions.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 mt-3">
                    <a class="text-decoration-none" href="#" data-toggle="modal" data-target="#push-modal">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fab fa-telegram-plane mr-1"></i> Push Message</h5>
                                <p class="card-text">Send promo message to customers.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 mt-3">
                    <a class="text-decoration-none" href="rides.php">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="far fa-times-circle mr-1"></i> Cancel Rides</h5>
                                <p class="card-text">Cancel ongoing rides.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 mt-3">
                    <a class="text-decoration-none" href="wallet.php">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-money-check-alt mr-1"></i> Update Wallet</h5>
                                <p class="card-text">Update the wallet amount for users.</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <br/>
            <br/>
            <br/>
        </div>

        <br/>

        <?php
            $Aider->getUI()->getDashboard()->getAdminBottomNavigation(1);
        ?>

        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Admin.js"></script>
        <script>

            $(document).ready(function() {
                getAdminHomeDashboard();
            });

        </script>
    </body>

</html>