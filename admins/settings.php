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
                    <h2 class="font-weight-bold">Settings</h2>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <h4 class="font-weight-bold">General Settings</h4>
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

                <div class="col-12 mt-3">
                    <div class="border border-dark rounded pt-3 pr-3 pl-3 pb-5">
                        <h5 class="font-weight-bold mb-3">Rider</h5>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="max-rad">Maximum Radius (KM)</span>
                            </div>
                            <input type="number" id="max-rad-km" class="form-control" aria-describedby="max-rad" />
                        </div>
                        <button class="btn btn-dark float-right" id="save-rider">Save</button>
                    </div>
                </div>

                <div class="container mt-5">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="font-weight-bold">Module Settings</h4>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="border border-dark rounded pt-3 pr-3 pl-3 pb-5">
                        <h5 class="font-weight-bold mb-3">Aider Driver</h5>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="primary-driver-cut">Primary Driver's Cut (%)</span>
                            </div>
                            <input type="number" id="primary-driver-cut-per" class="form-control" aria-describedby="primary-driver-cut" min="0" max="100"/>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="secondary-driver-cut">Secondary Driver's Cut (%)</span>
                            </div>
                            <input type="number" id="secondary-driver-cut-per" class="form-control" aria-describedby="secondary-driver-cut" />
                        </div>
                        <button class="btn btn-dark float-right" id="save-aider-driver">Save</button>
                    </div>
                </div>

                <!--
                <div class="col-12 mt-3">
                    <div class="border border-dark rounded pt-3 pr-3 pl-3 pb-5">
                        <h5 class="font-weight-bold mb-3">Aider Parcel</h5>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="parcel-rider-cut">Rider's Cut (%)</span>
                            </div>
                            <input type="number" id="parcel-rider-cut-per" class="form-control" aria-describedby="parcel-rider-cut" min="0" max="100"/>
                        </div>

                        <button class="btn btn-dark float-right" id="save-aider-parcel">Save</button>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="border border-dark rounded pt-3 pr-3 pl-3 pb-5">
                        <h5 class="font-weight-bold mb-3">Aider Food</h5>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="food-rider-cut">Rider's Cut (%)</span>
                            </div>
                            <input type="number" id="food-rider-cut-per" class="form-control" aria-describedby="food-rider-cut" min="0" max="100"/>
                        </div>

                        <button class="btn btn-dark float-right" id="save-aider-food">Save</button>
                    </div>
                </div>
                -->

            </div>
        </div>

        <br/>
        <br/>
        <br/>
        <br/>
        <br/>

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