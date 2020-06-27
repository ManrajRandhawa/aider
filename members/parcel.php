<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Parcel</title>

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
                margin-bottom: 13.0rem !important;
            }
        </style>
    </head>

    <body class="bg-light">

        <!-- Get Header -->
        <div class="container-fluid fixed-top mt-4">
            <div class="row">
                <div class="col-2">
                    <i class="fas fa-chevron-circle-left fa-2x text-light float-right" id="btn-back"></i>
                </div>
                <div class="col-10 bg-transparent"></div>
            </div>
        </div>




        <!-- Modal: First Time Login - Change Password -->
        <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Set a new password.</h5>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="pass" class="col-form-label">Password:</label>
                                <input type="password" class="form-control" name="pswd" id="pass">
                            </div>
                            <div class="form-group">
                                <label for="confirm-pass" class="col-form-label">Confirm Password:</label>
                                <input type="password" class="form-control" name="confirm-pswd" id="confirm-pass">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="change-pass-btn">Change Password</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="map-row">
            <div class="map-col-12">
                <div id="map"></div>
            </div>
        </div>
        <!-- Main Content -->

        <!-- START: Delivery & Parcel Details -->
        <div class="container mb-7 fixed-bottom" id="container-details" style="display: none;">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="row rounded bg-white p-0">
                        <div class="col-12">
                            <h5 class="text-center mt-2 mb-4 text-dark">Contact Details</h5>
                            <div class="row">
                                <div class="col-12">
                                    <h6>Pick Up</h6>
                                    <input id="pickup-details-name" type="text" class="form-control" placeholder="Name (optional)"/>
                                    <input id="pickup-details-phonenum" type="text" class="form-control mt-1" placeholder="Phone Number (optional)"/>
                                </div>
                            </div>
                            <hr class="mt-2 mb-2"/>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6>Drop Off</h6>
                                    <input id="dropoff-details-name" type="text" class="form-control" placeholder="Name (optional)"/>
                                    <input id="dropoff-details-phonenum" type="text" class="form-control mt-1" placeholder="Phone Number (optional)"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
        </div>
        <!-- END: Delivery & Parcel Details -->

        <!-- START: Location Search -->
        <div class="container mb-5 fixed-bottom" style="z-index: 0;" id="container-location">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="row rounded bg-white p-0">
                        <div class="col-12">
                            <div class="row mb-n2">
                                <div class="col-2 mt-3 text-center">
                                    <i class="far fa-dot-circle fa-lg text-primary"></i>
                                </div>
                                <div class="col-10">
                                    <input id="pickUpSearch" type="text" class="form-control mt-2 border-0 shadow-none" placeholder="Pick Up Location" required/>
                                </div>
                            </div>

                            <div class="row h-25">
                                <div class="col-2 text-center mt-1">
                                    <i class="fas fa-ellipsis-v text-black-50 fa-sm"></i>
                                </div>
                                <div class="col-9"><hr/></div>
                                <div class="col-1"></div>
                            </div>

                            <div class="row mt-n1">
                                <div class="col-2 mt-2 text-center">
                                    <i class="fas fa-map-marker-alt fa-lg text-danger"></i>
                                </div>
                                <div class="col-10">
                                    <input id="dropOffSearch" type="text" class="form-control mb-2 border-0 shadow-none" placeholder="Drop Off Location" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
        </div>
        <!-- END: Location Search -->

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
        <script src="../assets/js/Dashboard.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQHLdxkQcezk4kKWaX219nHla2xUHJ274&libraries=places&callback=initMap"></script>

        <script>
            function initMap() {
                let map, pickUp, autocompletePickUp, dropOff, autocompleteDropOff;

                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 3.139, lng: 101.547},
                    zoom: 10,
                    disableDefaultUI: true

                });

                pickUp = document.getElementById('pickUpSearch');
                autocompletePickUp = new google.maps.places.Autocomplete(pickUp);
                autocompletePickUp.setComponentRestrictions({'country': ['my']});

                dropOff = document.getElementById('dropOffSearch');
                autocompleteDropOff = new google.maps.places.Autocomplete(dropOff);
                autocompleteDropOff.setComponentRestrictions({'country': ['my']});

                let latOri = -999;
                let lngOri = -999;
                let latDest = -999;
                let lngDest = -999;
                let coordsOri, coordsDest, gOri = 0, gDest = 0, directionsService, directionsRenderer;

                let distance;
                let duration;
                let price;

                autocompletePickUp.addListener('place_changed', function () {
                    var place = autocompletePickUp.getPlace();

                    latOri = place.geometry.location.lat();
                    lngOri = place.geometry.location.lng();

                    coordsOri = {lat: latOri, lng: lngOri};

                    if(latDest !== -999 && lngDest !== -999) {

                        // START: Setup Directions (Origin Click)
                        if(directionsRenderer != null) {
                            directionsRenderer.set('directions', null); // Reset directions
                        }

                        directionsService = new google.maps.DirectionsService();
                        directionsRenderer = new google.maps.DirectionsRenderer();

                        gOri = new google.maps.LatLng(latOri, lngOri);
                        gDest = new google.maps.LatLng(latDest, lngDest);
                        directionsRenderer.setMap(map);

                        var request = {
                            origin: gOri,
                            destination: gDest,
                            travelMode: google.maps.TravelMode.DRIVING
                        };
                        directionsService.route(request, function(response, status) {
                            if(status == 'OK') {
                                directionsRenderer.setDirections(response);
                            }
                        });
                        // END: Setup Directions (Origin Click)

                        // START: Find Distance and Set Price (Origin Click)
                        var distanceService = new google.maps.DistanceMatrixService();
                        distanceService.getDistanceMatrix({
                                origins: [latOri + "," + lngOri],
                                destinations: [latDest + "," + lngDest],
                                travelMode: google.maps.TravelMode.DRIVING,
                                unitSystem: google.maps.UnitSystem.METRIC,
                                durationInTraffic: true,
                                avoidHighways: false,
                                avoidTolls: false
                            },
                            function (response, status) {
                                if (status !== google.maps.DistanceMatrixStatus.OK) {
                                    console.log('Error:', status);
                                } else {
                                    distance = response.rows[0].elements[0].distance.text;
                                    duration = response.rows[0].elements[0].duration.text;

                                    var distanceNum = distance.replace(' km','');

                                    price = 5 + (1.5 * distanceNum);

                                    let pickUpLocationVal = $('#pickUpSearch').val();
                                    let dropOffLocationVal = $('#dropOffSearch').val();

                                    if(pickUpLocationVal && dropOffLocationVal) {
                                        $('#text-price').html("RM " + price.toFixed(2) + " <span id='text-distance'></span>");
                                        $('#text-distance').text("(" + distance + ")");

                                        $('#container-location').removeClass('mb-5').addClass('mb-6', 100);
                                        $('#container-btn-deliver').slideDown('fast');

                                        $('#container-btn-deliver').click(function() {
                                            $('#btn-continue').hide();
                                            $('#btn-deliver').show();
                                            $('#container-details').slideDown();

                                        });

                                    }
                                }
                            });
                        // END: Find Distance and Set Price (Origin Click)
                    }
                });

                autocompleteDropOff.addListener('place_changed', function() {
                    var place = autocompleteDropOff.getPlace();
                    latDest = place.geometry.location.lat();
                    lngDest = place.geometry.location.lng();
                    coordsDest = {lat: latDest, lng: lngDest};


                    if(latOri !== -999 && lngOri !== -999) {

                        // START: Setup Directions (Destination Click)
                        if(directionsRenderer != null) {
                            directionsRenderer.set('directions', null); // Reset directions
                        }

                        directionsService = new google.maps.DirectionsService();
                        directionsRenderer = new google.maps.DirectionsRenderer();

                        gOri = new google.maps.LatLng(latOri, lngOri);
                        gDest = new google.maps.LatLng(latDest, lngDest);
                        directionsRenderer.setMap(map);


                        var request = {
                            origin: gOri,
                            destination: gDest,
                            travelMode: google.maps.TravelMode.DRIVING
                        };
                        directionsService.route(request, function(response, status) {
                            if(status === 'OK') {
                                directionsRenderer.setDirections(response);
                            }
                        });
                        // END: Setup Directions (Destination Click)

                        // START: Find Distance and Set Price (Destination Click)
                        var distanceService = new google.maps.DistanceMatrixService();
                        distanceService.getDistanceMatrix({
                                origins: [latOri + "," + lngOri],
                                destinations: [latDest + "," + lngDest],
                                travelMode: google.maps.TravelMode.DRIVING,
                                unitSystem: google.maps.UnitSystem.METRIC,
                                durationInTraffic: true,
                                avoidHighways: false,
                                avoidTolls: false
                            },
                            function (response, status) {
                                if (status !== google.maps.DistanceMatrixStatus.OK) {
                                    console.log('Error:', status);
                                } else {
                                    distance = response.rows[0].elements[0].distance.text;
                                    duration = response.rows[0].elements[0].duration.text;

                                    var distanceNum = distance.replace(' km','');

                                    price = 5 + (1.5 * distanceNum);

                                    let pickUpLocationVal = $('#pickUpSearch').val();
                                    let dropOffLocationVal = $('#dropOffSearch').val();

                                    if(pickUpLocationVal && dropOffLocationVal) {
                                        $('#text-price').html("RM " + price.toFixed(2) + " <span id='text-distance'></span>");
                                        $('#text-distance').text("(" + distance + ")");

                                        $('#container-location').removeClass('mb-5').addClass('mb-6', 100);
                                        $('#container-btn-deliver').slideDown('fast');

                                        $('#container-btn-deliver').click(function() {
                                            $('#btn-continue').hide();
                                            $('#btn-deliver').show();
                                            $('#container-details').slideDown();

                                        });

                                    }
                                }
                            });
                        // END: Find Distance and Set Price (Destination Click)
                    }
                });
            }

            $(document).ready(function() {
                $('#btn-back').click(function() {
                    window.location.href = "home.php";
                });

                getDashboardJS();
                getParcelDashboardJS();
            });

        </script>
    </body>

</html>