<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Admin | Waiting List</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="bg-light">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <!-- Modal: Add Details to Approved Rider -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' id="riderApproval" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='approveModalLabel'>Approval for Rider (<span id="approve_email"></span>)</h5>
                    </div>
                    <div class='modal-body'>
                        <form method='post'>
                            <div class='form-group'>
                                <label for='v_model' class='col-form-label'>Vehicle Model:</label>
                                <input type='text' class='form-control' name='v_model' id='v_model'>
                            </div>
                            <div class='form-group'>
                                <label for='v_plate_num' class='col-form-label'>Vehicle Plate Number:</label>
                                <input type='text' class='form-control' name='v_plate_num' id='v_plate_num'>
                            </div>
                            <div class='form-group'>
                                <label for="rider_type" class='col-form-label'>Type:</label>
                                <select class="form-control" id="rider_type">
                                    <option>Rider</option>
                                    <option>Driver</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='approve-btn'>Approve</button>
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
                    <h3 class="font-weight-bold">Waiting List</h3>
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <div class="col-12 mt-3" id="waiting-list-container">

                </div>
            </div>
        </div>

        <?php
            $Aider->getUI()->getDashboard()->getAdminBottomNavigation(2);
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
                getWaitingListDashboard();
            });

        </script>
    </body>

</html>