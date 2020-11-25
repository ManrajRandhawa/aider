<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Admin | Rides</title>

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

        <!-- Modal: Cancel Ride -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' id="cancelRide" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='approveModalLabel'>Cancel Ride - <span class="text-danger" id="ride_details_header"></span></h5>
                    </div>
                    <div class='modal-body'>
                        <h6>You are about to cancel the following ride.</h6>
                        <h6><span class="text-danger" id="ride_details_body">asdasdas</span></h6>
                        <h6 class="font-weight-bold">Are you sure?</h6>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='cancel-ride-btn-confirm'>Cancel Ride</button>
                        <button type='button' class='btn btn-outline-primary' data-dismiss="modal" id='cancel-ride-btn-cancel'>Cancel</button>
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
                    <h3 class="font-weight-bold mb-4">Rides</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-9">
                    <input type="text" class="form-control form-control-lg" id="user-search" placeholder="Enter at least 3 characters to search" aria-label="Search">
                </div>
                <div class="col-3">
                    <a class="float-right btn btn-outline-primary btn-lg text-primary" onclick="$('#cancelRide').modal();"><i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="row" id="rides-container">

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
                getCancelRidesDashboard();
            });

        </script>
    </body>

</html>