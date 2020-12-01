<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Admin | Wallet</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" integrity="sha512-ARJR74swou2y0Q2V9k0GbzQ/5vJ2RBSoCWokg4zkfM29Fb3vZEQyv0iWBMW/yvKgyHSR/7D64pFMmU8nYmbRkg==" crossorigin="anonymous" />
    </head>

    <body class="bg-light">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <!-- Modal: Add Money -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' id="addMoney" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='approveModalLabel'>Update Wallet</h5>
                    </div>
                    <div class='modal-body'>
                        <input type="number" class="form-control" id="wallet-amt" placeholder="Enter an amount">
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='user-btn-confirm' onclick="updateMoney();">Update</button>
                        <button type='button' class='btn btn-outline-primary' data-dismiss="modal" id='user-btn-cancel'>Cancel</button>
                    </div>
                </div>
                <!-- START: Toast Messages Area -->
                <div class='toast-container-modal' style='z-index: 9999;'>

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>


        <!-- Main Content -->
        <div class="container">
            <div class="row">
                <div class="col-12 mt-4">
                    <h3 class="font-weight-bold mb-4">Update Wallet</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <input type="text" class="form-control form-control-lg" id="user-search" placeholder="Enter at least 3 characters to search" aria-label="Search">
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="row" id="wallet-container">

                    </div>
                </div>
            </div>
        </div>

        <br/>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous"></script>
        <script src="../assets/js/Admin.js"></script>
        <script>

            $(document).ready(function() {
                getAddMoneyDashboard();
            });

        </script>
    </body>

</html>