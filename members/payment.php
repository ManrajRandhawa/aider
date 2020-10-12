<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/modals/CredentialModal.php";

    $Aider = new Aider();
    $credentialsModal = new CredentialModal();


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Payment</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="bg-white">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <div class="bg-light">
            <?php
                $Aider->getUI()->getDashboard()->getDashboardHeader();
            ?>
            <!-- Header: 1st View -->
            <div class="container bg-light" id="payment-container-top-v1">
                <div class="row">
                    <div class="col-12 mt-3">
                        <h3 class="font-weight-bold">Payment</h3>
                    </div>

                    <div class="col-12 mt-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-primary font-weight-bold">Wallet Balance</h5>
                                <h6>RM <span class="h3 font-weight-bold" id="wallet-balance">0.00</span></h6>
                            </div>
                            <div class="card-footer bg-white" id="top-up-wallet">
                                    <span class="card-text text-dark h6">Top-up wallet balance</span>
                                    <span class="float-right"><i class="fas fa-chevron-right"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header: 2nd View -->
            <div class="container bg-light min-vh-100 d-none" id="payment-container-top-v2">
                <div class="row">
                    <div class="col-12 mt-3">
                        <i class="fas fa-chevron-left fa-lg mb-1" id="btn-back"></i>
                        <span class="h3 font-weight-bold ml-4">Payment <span class="h5 font-weight-bold">> Top-Up</span></span>
                    </div>

                    <div class="col-12 mt-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-primary font-weight-bold">Enter a top-up value (RM)</h5>
                                <div class="input-group input-group-lg mb-3">
                                    <input type="number" class="form-control" placeholder="Enter an amount (RM)" id="transaction-amount" />
                                </div>

                                <select title="Select a bank" class="selectpicker w-100" id="bank-code">
                                    <option selected disabled>Select a bank</option>
                                    <?php echo $credentialsModal->getBanks()['data']; ?>
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-primary btn-lg w-100 mt-4" id="confirm-transaction-btn">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content: 1st View -->
        <div class="container" id="payment-container-bottom-v1">
            <div class="row">
                <div class="col-12">
                    <h5 class="font-weight-bold mt-4">Recent transactions</h5>

                    <!-- Transaction Container -->
                    <div id="transaction-container">

                    </div>

                </div>
            </div>
        </div>

        <br/>

        <?php
            $Aider->getUI()->getDashboard()->getBottomNavigation(2);
        ?>

        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Dashboard.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                getDashboardJS();
                getPaymentDashboardJS();
            });

        </script>
    </body>

</html>