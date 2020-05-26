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
                height: 40vh;
                margin: 0;
                padding: 0;
            }
        </style>
    </head>

    <body class="bg-light">

        <!-- Get Header -->
        <?php
            $Aider->getUI()->getDashboard()->getHeader();
        ?>




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
        <div class="container mb-5">
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10">
                    <div class="row">
                        <div class="col-12">
                            <input id="pickUpSearch" type="text" class="form-control mt-5" placeholder="Pick Up Location" required/>
                        </div>

                        <div class="col-6 mt-1 pr-1">
                            <input id="pickup-details-name" type="text" class="form-control" placeholder="Name (optional)"/>
                        </div>
                        <div class="col-6 mt-1 pl-1">
                            <input id="pickup-details-phonenum" type="text" class="form-control" placeholder="Phone Number (optional)"/>
                        </div>

                        <div class="col-12 mt-5">
                            <input id="dropOffSearch" type="text" class="form-control" placeholder="Drop Off Location" required/>
                        </div>

                        <div class="col-6 mt-1 pr-1">
                            <input id="dropoff-details-name" type="text" class="form-control" placeholder="Name (optional)"/>
                        </div>
                        <div class="col-6 mt-1 pl-1">
                            <input id="dropoff-details-phonenum" type="text" class="form-control" placeholder="Phone Number (optional)"/>
                        </div>
                        <div class="col-12 rounded mt-4 mb-5" style="background: #cce4ff;">
                            <h4 class="text-center" id="text-price">RM 0.00 <span id="text-distance"></span></h4>
                            <button class="btn btn-primary w-100 mb-3" id="btn-deliver">Deliver Now</button>
                        </div>
                    </div>
                </div>
                <div class="col-1"></div>
            </div>
        </div>

        <!-- START: Toast Messages Area -->

        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->

        <div class="container-fluid bg-dark fixed-bottom rounded-top" style="height: 10%;">
            <div class="row text-center">
                <a href="" class="col-4 text-decoration-none pt-2">
                    <i class="fas fa-utensils fa-lg"></i>
                    <span class="navbar-brand text-primary d-block m-0">Food</span>
                </a>
                <a href="" class="col-4 text-decoration-none pt-2">
                    <i class="far fa-user fa-lg"></i>
                    <span class="navbar-brand text-primary d-block m-0">My Account</span>
                </a>
                <a href="" class="col-4 text-decoration-none pt-2">
                    <i class="fas fa-box fa-lg"></i>
                    <span class="navbar-brand text-primary d-block m-0">Parcel</span>
                </a>
            </div>
        </div>


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
                                    $('#text-price').html("RM " + price.toFixed(2) + " <span id='text-distance'></span>");
                                    $('#text-distance').text("(" + distance + ")");
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
                                    $('#text-price').html("RM " + price.toFixed(2) + " <span id='text-distance'></span>");
                                    $('#text-distance').text("(" + distance + ")");
                                }
                            });
                        // END: Find Distance and Set Price (Destination Click)
                    }
                });
            }

            $(document).ready(function(){
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);

                $('.toast').toast({
                    delay: 3500
                });
                $('.toast').toast('show');

                getDashboardJS();
                getParcelDashboardJS();
            });

        </script>
    </body>

</html>