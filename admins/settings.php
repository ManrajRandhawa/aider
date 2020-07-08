<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Admin | Settings</title>

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
        <div class="container">
            <div class="row">
                <div class="col-12 mt-4">
                    <h3 class="font-weight-bold">Settings</h3>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="border border-dark rounded pt-3 pr-3 pl-3 pb-5">
                        <h5 class="font-weight-bold mb-3">Pricing</h5>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="base-fare">Base Fare (RM)</span>
                            </div>
                            <input type="number" id="base-fare-price" class="form-control" aria-describedby="base-fare" />
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="per-km">Price per KM (RM)</span>
                            </div>
                            <input type="number" id="per-km-price" class="form-control" aria-describedby="per-km" />
                        </div>
                        <button class="btn btn-dark float-right" id="save-pricing">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <?php
            $Aider->getUI()->getDashboard()->getAdminBottomNavigation(3);
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
                getSettingsDashboard();
            });

        </script>
    </body>

</html>