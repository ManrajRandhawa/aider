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
                    <a class="text-decoration-none" href="finance.php">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-money-check-alt mr-1"></i> Finance</h5>
                                <p class="card-text">View your financial statement.</p>
                            </div>
                        </div>
                    </a>
                </div>

            </div>
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