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
        <style type="text/css">
            #map {
                height: 100%;
            }

            div.map-row,
            div.map-col-12
            {
                height: 100vh;
                margin: 0;
                padding: 0;
            }

            .pac-container {
                height: 3.7rem;
                overflow-y: scroll;
                z-index: 9999;
            }

            .mb-6 {
                margin-bottom: 5.5rem !important;
            }

            .mb-7 {
                margin-bottom: 7rem !important;
            }
        </style>
    </head>

    <body class="bg-light">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <!-- Main Content -->

        <div class="map-row">
            <div class="map-col-12">
                <div id="map"></div>
            </div>
        </div>


        <!-- START: Order Layout -->
        <div class="container mb-7 fixed-bottom d-none" style="z-index: 0;" id="order-container">
            <div class="row">
                <div class="col-12 ml-2" style="max-width: 96vw;">
                    <div class="row rounded bg-white pt-3 pb-2">
                        <div class="col-12 text-break">
                            <!-- Type of Delivery -->
                            <h6 class="font-weight-bold text-center" id="delivery-type">Food/Parcel Delivery</h6>

                            <!-- Restaurant Address -->
                            <h6 class="mt-4 font-weight-bold" id="pickUpLoc-primary"></h6>
                            <h6 class="mb-3" id="pickUpLoc-secondary"></h6>

                            <hr/>

                            <!-- Travel Distance -->
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="font-weight-bold float-left">Travel Distance</h5>
                                </div>
                                <div class="col-6">
                                    <h5 class="float-right text-muted" id="travel-distance"></h5>
                                </div>
                            </div>

                            <!-- Pick-up Timer -->
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="font-weight-bold float-left">Pick-up in</h5>
                                </div>
                                <div class="col-6">
                                    <h5 class="float-right text-success">
                                        <i class="far fa-clock"></i>
                                        <span id="time-remaining"></span>
                                    </h5>
                                </div>
                            </div>

                            <hr/>

                            <!-- Order Details -->
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="font-weight-bold float-left">Order</h6>
                                </div>
                                <div class="col-6">
                                    <h6 class="float-right text-muted">
                                        <span id="order-details">

                                        </span>
                                    </h6>
                                </div>
                            </div>

                            <hr/>

                            <!-- Pricing Details -->
                            <div class="row">
                                <div class="col-6">
                                    <h6 class="font-weight-bold float-left">Total Price</h6>
                                </div>
                                <div class="col-6">
                                    <h6 class="float-right text-muted">
                                        <span id="total-price"></span>
                                        <span id="payment-info"></span>
                                    </h6>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="row mt-4 mb-3">
                                <div class="col-4 pr-1">
                                    <button class="btn btn-outline-dark btn-lg w-100">Cancel</button>
                                </div>
                                <div class="col-8 pl-1">
                                    <button class="btn btn-success btn-lg w-100">Accept Order in <span id="time-left">00:30</span></button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Order Layout -->

        <!-- START: Bottom Navigation -->
        <div class="container mb-4 fixed-bottom" style="z-index: 0;" id="container-location">
            <!--
            <div class="row mb-2">
                <div class="col-9"></div>
                <div class="col-1">
                    <i class="far fa-compass fa-3x bg-white rounded-circle p-2"></i>
                </div>
                <div class="col-1"></div>
            </div>
            -->
            <div class="row">
                <div class="col-12 ml-2" style="max-width: 96vw;">
                    <div class="row rounded bg-white pt-3 pb-2">
                        <div class="col-3 text-center">
                            <span>
                                <i class="fas fa-coins fa-lg text-primary"></i>
                                <span style="font-size: 11pt;" class="text-dark">Earnings</span>
                            </span>
                        </div>
                        <div class="col-6 text-center">
                            <!-- Inactive Button -->
                            <div class="btn btn-outline-dark w-100" id="btn-inactive">
                                <span>Inactive Mode</span>
                            </div>

                            <!-- Active Button -->
                            <div class="btn btn-outline-success w-100 d-none" id="btn-active">
                                <span>Active Mode</span>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <span>
                                <i class="far fa-user-circle fa-lg text-primary"></i>
                                <span style="font-size: 11pt;" class="text-dark">Account</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Bottom Navigation -->

        <!-- START: Deliver Button -->
        <div class="container mb-3 fixed-bottom" id="container-btn-deliver" style="display: none;">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10 p-0">
                    <button class="btn btn-light rounded w-100 bg-white" id="btn-continue">
                        Continue
                    </button>
                    <button class="btn btn-light rounded w-100 bg-white" id="btn-deliver" style="display: none;">
                        Deliver Now <h6 id="text-price">RM 0.00 <span id='text-distance'>(0 km)</span></h6>
                    </button>
                </div>
                <div class="col-1"></div>
            </div>
        </div>
        <!-- END: Deliver Button -->


        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Rider.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQHLdxkQcezk4kKWaX219nHla2xUHJ274&libraries=places&callback=initMap"></script>

        <script>
            function initMap() {
                let map, pickUp, autocompletePickUp, dropOff, autocompleteDropOff;

                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 3.139, lng: 101.547},
                    zoom: 10,
                    disableDefaultUI: true

                });
            }

            $(document).ready(function() {
                getRiderDashboardJS();
                getHomeJS();
            });

        </script>
    </body>

</html>