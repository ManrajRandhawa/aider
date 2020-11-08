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

            .circle-loader {
                margin-bottom: 1rem;
                border: 2px solid rgba(0, 0, 0, 0.2);
                border-left-color: #5cb85c;
                animation: loader-spin 0.87s infinite linear;
                position: relative;
                display: inline-block;
                vertical-align: top;
                border-radius: 50%;
                width: 80px;
                height: 80px;
            }
            .load-complete {
                -webkit-animation: none;
                animation: none;
                border-color: #5cb85c;
                transition: border 500ms ease-out;
            }
            .checkmark {
                display: none;
            }
            .checkmark.draw:after {
                animation-duration: 800ms;
                animation-timing-function: ease;
                animation-name: checkmark;
                transform: scaleX(-1) rotate(135deg);
            }
            .checkmark:after {
                opacity: 1;
                height: 40px;
                width: 20px;
                transform-origin: left top;
                border-right: 3px solid #5cb85c;
                border-top: 3px solid #5cb85c;
                content: '';
                left: 18px;
                top: 40px;
                position: absolute;
            }
            @keyframes loader-spin {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
            @keyframes checkmark {
                0% {
                    height: 0;
                    width: 0;
                    opacity: 1;
                }
                20% {
                    height: 0;
                    width: 20px;
                    opacity: 1;
                }
                40% {
                    height: 40px;
                    width: 20px;
                    opacity: 1;
                }
                100% {
                    height: 40px;
                    width: 20px;
                    opacity: 1;
                }
            }

        </style>
    </head>

    <body class="bg-light">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <!-- Main Content -->

        <div class="map-row" id="rider-home">
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
                            <h6 class="font-weight-bold text-white text-center bg-primary rounded pt-2 pb-2 ml-5 mr-5" id="delivery-type">Food/Parcel Delivery</h6>
                            <hr class="ml-5 mr-5"/>

                            <!-- Restaurant Address -->
                            <div class="text-center">
                                <h6 class="mt-4 font-weight-bold text-success" id="pickUpLoc-primary"></h6>
                                <h6 class="mb-3" id="pickUpLoc-secondary"></h6>

                                <i class="fas fa-angle-double-down fa-lg text-success"></i>

                                <h6 class="mt-4 font-weight-bold text-success" id="dropOffLoc-primary"></h6>
                                <h6 class="mb-3" id="dropOffLoc-secondary"></h6>
                            </div>


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
                                    <h5 class="font-weight-bold float-left">ETA</h5>
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
                            <div class="row" id="order-details">

                            </div>

                            <hr class="d-none" id="food-divider"/>

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
                            <div class="row mt-4 mb-3" id="btn-row">
                                <div class="col-4 pr-1">
                                    <button class="btn btn-outline-dark btn-lg w-100" id="reject-order">Cancel</button>
                                </div>
                                <div class="col-8 pl-1" id="accept-order-container">
                                    <button class="btn btn-success btn-lg w-100" id="accept-order" value="">Accept in <span id="time-left">00:30</span></button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Order Layout -->

        <!-- START: Mode: Heading to Pickup Location Layout -->
        <div class="container mb-6 fixed-bottom d-none" style="z-index: 0;" id="rider-navigation-riding-1-content">
            <div class="row">
                <div class="col-12 ml-2" style="max-width: 96vw;">
                    <div class="row rounded bg-white pt-3 pb-2">
                        <div class="col-12 text-break">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <i class="far fa-id-card fa-lg text-success"></i>
                                            <span class="ml-2 text-success">Customer's Information</span>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-6 text-left">
                                            <a class="text-muted">Name</a><br/>
                                            <a class="text-muted">Phone Number</a>
                                        </div>
                                        <div class="col-6 text-right" id="cust-info-heading-to-pickup">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="font-weight-bold float-left text-success" id="riding-1-content-loc-1"></h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-muted" id="riding-1-content-loc-2"></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2" id="riding-1-content-loc-btn">
                                    <a href="">
                                        <i class="mt-2 mr-3 fas fa-location-arrow fa-lg text-success"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Mode: Heading to Pickup Location Layout -->

        <!-- START: Mode: Heading to Destination Location Layout -->
        <div class="container mb-6 fixed-bottom d-none" style="z-index: 0;" id="rider-navigation-riding-2-content">
            <div class="row">
                <div class="col-12 ml-2" style="max-width: 96vw;">
                    <div class="row rounded bg-white pt-3 pb-2">
                        <div class="col-12 text-break">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <i class="far fa-id-card fa-lg text-success"></i>
                                            <span class="ml-2 text-success">Customer's Information</span>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-6 text-left">
                                            <a class="text-muted">Name</a><br/>
                                            <a class="text-muted">Phone Number</a>
                                        </div>
                                        <div class="col-6 text-right" id="cust-info-heading-to-destination">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="font-weight-bold float-left text-success" id="riding-2-content-loc-1"></h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="text-muted" id="riding-2-content-loc-2"></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2" id="riding-2-content-loc-btn">
                                    <a href="">
                                        <i class="mt-2 mr-3 fas fa-location-arrow fa-lg text-success"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Mode: Heading to Destination Location Layout -->

        <!-- START: Select Active Modes -->
        <div class="container mb-7 fixed-bottom d-none" style="z-index: 0;" id="rider-active-modes-list">
            <div class="row">
                <div class="col-12 ml-2" style="max-width: 96vw;">
                    <div class="row rounded bg-white pt-3 pb-3">
                        <div class="col-12 text-break">
                            <i class="fas fa-times fa-lg float-right text-dark mt-n1" id="close-active-modes-selection"></i>
                            <h4 class="card-title text-center">Choose a Mode</h4>

                            <hr/>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-outline-dark w-100" id="btn-others">
                                        <span>Parcel</span>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-primary w-100" id="btn-driver">
                                        <span>myAider Driver</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Select Active Modes -->

        <!-- START: Bottom Navigation -->
        <div class="container mb-4 fixed-bottom" style="z-index: 0;" id="container-location">
            <div class="row">
                <div class="col-12 ml-2" style="max-width: 96vw;">
                    <div class="row rounded bg-white pt-3 pb-2 shadow-lg" id="rider-navigation-main">
                        <div class="col-3 text-center">
                            <a href="earnings.php" class="text-decoration-none">
                                <span>
                                    <i class="fas fa-coins fa-lg text-primary"></i>
                                    <span style="font-size: 11pt;" class="text-dark">Earnings</span>
                                </span>
                            </a>
                        </div>
                        <div class="col-6 text-center p-0">
                            <!-- Inactive Button -->
                            <div class="btn btn-outline-dark w-100" id="btn-inactive">
                                <span>Inactive Mode</span>
                            </div>

                            <!-- Active Button -->
                            <div class="btn btn-outline-success w-100 d-none" id="btn-active">
                                <span>Active Mode</span>
                            </div>

                            <!-- Active Button for Teams -->
                            <div class="btn btn-outline-success w-100 d-none" id="btn-active-team">
                                <span>Team <span id="team_name_nav"></span> - Active</span>
                            </div>
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
                    <div class="row rounded bg-white pt-2 pb-2 d-none" id="rider-navigation-riding-1">
                        <div class="col-12 text-center">
                            <!-- Arrived at Pickup Location Button -->
                            <button class="btn btn-success w-100" id="btn-arrived-pickup" disabled>
                                <span>I have arrived at the Pickup Location</span>
                            </button>
                        </div>
                    </div>
                    <div class="row rounded bg-white pt-2 pb-2 d-none" id="rider-navigation-riding-2">
                        <div class="col-12 text-center">
                            <!-- Arrived at Pickup Location Button -->
                            <button class="btn btn-success w-100" id="btn-arrived-destination" disabled>
                                <span>I have arrived at the Destination Location</span>
                            </button>
                        </div>
                    </div>
                    <div class="row rounded bg-white pt-2 pb-2 d-none" id="rider-navigation-riding-3">
                        <div class="col-12 text-center">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="font-weight-bold text-center">
                                        <i class="fas fa-map-marked-alt fa-lg text-primary mr-3 mt-2"></i>
                                        Order Completed
                                    </h5>

                                    <hr class="mr-3 ml-3"/>

                                    <div class="circle-loader">
                                        <div class="checkmark draw"></div>
                                    </div>

                                    <h5 class="">You earned <span class="h5 font-weight-bold text-success">RM </span><span class="h4 font-weight-bold text-success" id="earned-amount"></span> on that trip!</h5>


                                    <button class="btn btn-primary btn-lg w-100 mt-2" id="rider-navigation-riding-3-btn">Continue</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END: Bottom Navigation -->


        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Rider.js"></script>
        <script src="../assets/js/Rider/RiderLogic.js"></script>

        <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAP_API_KEY; ?>&libraries=places&callback=initMap"></script>

        <script>
            function initMap() {
                let map;

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