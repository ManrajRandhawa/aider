<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Driver</title>

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

            .ripple {
                margin: auto;
                background-color: #4eb7f8;
                width: 2rem;
                height: 2rem;
                border-radius: 50%;
                position: relative;
                animation: ripple 3s linear infinite;
                z-index: 1;
            }

            .ripple::before,
            .ripple::after {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                border-radius: 50%;
                animation: ripple 3s linear infinite 1s
            }

            .ripple::after {
                animation: ripple 3s linear infinite 2s
            }

            @keyframes ripple {
                0% {
                    box-shadow: 0 0 0 .7rem rgba(255, 255, 255, 0.2)
                }

                100% {
                    box-shadow: 0 0 0 8rem rgba(255, 255, 255, 0)
                }
            }
        </style>
    </head>

    <body class="bg-light" id="body-main">

        <!-- START: Finding for Rider Layout -->
        <div class="container-fluid vw-100 vh-100 bg-primary d-none" id="finding-rider-layout" style="z-index: 99999;">
            <div class="row h-100">
                <div class="col-3"></div>
                <div class="col-6 text-center align-self-center mt-5" id="ripple-area">
                    <i class="fas fa-map-pin fa-5x text-white"></i>
                    <div class="ripple mt-n1"></div>
                </div>
                <div class="col-3"></div>

                <div class="col-12 text-center p-0" id="ripple-desc">
                    <h5 class="font-weight-bold text-white mb-3">Finding you a nearby driver...</h5>
                    <h6 class="text-white">Give drivers some time to accept your booking.</h6>
                </div>
            </div>
        </div>
        <!-- END: Finding for Rider Layout -->

        <div id="main-full-container">
            <!-- Get Header -->
            <div class="container-fluid fixed-top mt-4">
                <div class="row">
                    <div class="col-2">
                        <a href="home.php" class="text-decoration-none">
                            <i class="fas fa-chevron-circle-left fa-2x text-muted float-right shadow-lg rounded-circle" id="btn-back"></i>
                        </a>

                    </div>
                    <div class="col-10 bg-transparent"></div>
                </div>
            </div>

            <!-- Modal: First Time Login - Change Password -->
            <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
            ?>

            <div class="map-row">
                <div class="map-col-12">
                    <div id="map"></div>
                </div>
            </div>
            <!-- Main Content -->

            <!-- START: Location Search -->
            <div class="container mb-5 fixed-bottom p-0" style="z-index: 0;" id="container-location">
                <div class="row m-0">
                    <div class="col-1"></div>
                    <div class="col-10 p-0">
                        <div class="row rounded bg-white p-0 shadow-lg">
                            <div class="col-12">
                                <div class="row mb-n2">
                                    <div class="col-2 mt-3 text-center">
                                        <i class="far fa-dot-circle fa-lg text-primary"></i>
                                    </div>
                                    <div class="col-8">
                                        <input id="pickUpSearch" type="text" class="form-control mt-2 border-0 shadow-none" placeholder="Pick Up Location" required/>
                                    </div>
                                    <div class="col-2 p-0">
                                        <button class="btn btn-outline-dark rounded-circle mt-2" id="btn-pinpoint-loc"><i class="fas fa-map-marker-alt"></i></button>
                                    </div>
                                </div>

                                <div class="row h-25">
                                    <div class="col-2 text-center mt-1">
                                        <i class="fas fa-ellipsis-v text-black-50 fa-sm"></i>
                                    </div>
                                    <div class="col-10"><hr/></div>
                                </div>

                                <div class="row mt-n1">
                                    <div class="col-2 mt-2 text-center">
                                        <i class="fas fa-map-marker-alt fa-lg text-danger"></i>
                                    </div>
                                    <div class="col-8">
                                        <input id="dropOffSearch" type="text" class="form-control mb-2 border-0 shadow-none" placeholder="Drop Off Location" required/>
                                    </div>
                                    <div class="col-2 p-0">
                                        <button class="btn btn-outline-dark rounded-circle pl-2 pr-2 d-none" id="btn-drop-off-home"><i class="fas fa-home"></i></button>
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
            <div class="container mb-3 fixed-bottom p-0" id="container-btn-deliver" style="display: none;">
                <div class="row p-0">
                    <div class="col-1"></div>
                    <div class="col-10 p-0">
                        <button class="btn btn-light rounded w-100 bg-white shadow-lg" id="btn-continue">
                            Continue
                        </button>
                        <button class="btn btn-light rounded w-100 bg-white shadow-lg" id="btn-deliver" style="display: none;">
                            Book Now <h6 id="text-price">RM 0.00 <span id='text-distance'>(0 km)</span></h6>
                        </button>
                    </div>
                    <div class="col-1"></div>
                </div>
            </div>
            <!-- END: Deliver Button -->
        </div>

        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->

        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/AiderEvents.js"></script>
        <script src="../assets/js/Dashboard.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAP_API_KEY; ?>&region=MY&language=en&libraries=places&callback=initMap"></script>

        <script>
            let basefare;
            let priceperkm;

            function initMap() {
                let map, pickUp, autocompletePickUp, dropOff, autocompleteDropOff;

                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 3.139, lng: 101.547},
                    zoom: 10,
                    disableDefaultUI: true
                });

                const iconBase =
                    "https://aider.my/aider/assets/images/";
                const icons = {
                    info: {
                        icon: iconBase + "car.png",
                    },
                };
                const features = [
                    <?php
                        echo $Aider->getUserModal()->getRiderModal()->getActiveRidersForMap()['data'];
                    ?>
                ];

                // Create markers.
                for (let i = 0; i < features.length; i++) {
                    const marker = new google.maps.Marker({
                        position: features[i].position,
                        icon: icons[features[i].type].icon,
                        map: map,
                    });
                }

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

                    if($('#dropOffSearch').val() !== "") {
                        $.ajax({
                            url: '../assets/php/ajax/bookings/driver/getCoordinates.php',
                            method: 'POST',
                            cache: false,
                            data: {Address: $('#dropOffSearch').val() + ""},
                            success: function(data) {
                                latDest = data.split(",")[0];
                                lngDest = data.split(",")[1];

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

                                                price = basefare + (priceperkm * distanceNum);

                                                if(!Number.isNaN(price)) {
                                                    price = parseFloat(price);

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
                                                        });

                                                    }
                                                }
                                            }
                                        });
                                    // END: Find Distance and Set Price (Origin Click)
                                }
                            }
                        });
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

                                    price = basefare + (priceperkm * distanceNum);

                                    if(!Number.isNaN(price)) {
                                        price = parseFloat(price);

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
                                            });
                                        }
                                    }
                                }
                            });
                        // END: Find Distance and Set Price (Destination Click)
                    }
                });

                $('#btn-drop-off-home').click(function() {
                    setTimeout(function() {
                        $.ajax({
                            url: '../assets/php/ajax/bookings/driver/getCoordinates.php',
                            method: 'POST',
                            cache: false,
                            data: {Address: $('#dropOffSearch').val() + ""},
                            success: function (data) {
                                latDest = data.split(",")[0];
                                lngDest = data.split(",")[1];

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

                                                price = basefare + (priceperkm * distanceNum);

                                                if(!Number.isNaN(price)) {
                                                    price = parseFloat(price);

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
                                                        });
                                                    }
                                                }
                                            }
                                        });
                                    // END: Find Distance and Set Price (Destination Click)
                                }
                            }
                        });

                    }, 300);
                });

                $('#btn-pinpoint-loc').click(function() {
                    if(navigator.geolocation) {

                        let add = "";

                        navigator.geolocation.getCurrentPosition(function(position) {
                            const latlng = {
                                lat: parseFloat(position.coords.latitude),
                                lng: parseFloat(position.coords.longitude),
                            };

                            let geocoder = new google.maps.Geocoder();

                            geocoder.geocode({ location: latlng }, (results, status) => {
                                if (status === "OK") {
                                    if (results[0]) {
                                        add = results[0].formatted_address;
                                        $('#pickUpSearch').val(add);

                                        latOri = position.coords.latitude;
                                        lngOri = position.coords.longitude;

                                        coordsOri = {lat: latOri, lng: lngOri};

                                        if($('#dropOffSearch').val() !== "") {
                                            $.ajax({
                                                url: '../assets/php/ajax/bookings/driver/getCoordinates.php',
                                                method: 'POST',
                                                cache: false,
                                                data: {Address: $('#dropOffSearch').val() + ""},
                                                success: function(data) {
                                                    latDest = data.split(",")[0];
                                                    lngDest = data.split(",")[1];

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

                                                                    price = basefare + (priceperkm * distanceNum);

                                                                    if(!Number.isNaN(price)) {
                                                                        price = parseFloat(price);

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
                                                                            });

                                                                        }
                                                                    }
                                                                }
                                                            });
                                                        // END: Find Distance and Set Price (Origin Click)
                                                    }
                                                }
                                            });
                                        }

                                    } else {
                                        window.alert("No results found");
                                    }
                                } else {
                                    window.alert("Geocoder failed due to: " + status);
                                }
                            });

                        }, function() {
                            // Display Toast Message on Location Permission not given
                        }, {
                            enableHighAccuracy: true
                        });
                    } else {
                        // Display Toast Message on the error that occurred
                        alert("Oops!");
                    }


                });
            }

            $(document).ready(function() {
                // Get Pricing Details
                $.ajax({
                    url: "../assets/php/ajax/admin/getSettingsInformation.php",
                    method: "POST",
                    cache: false,
                    data: {Settings_Info: "Base_Fare_Driver"},
                    success: function(data){
                        basefare = parseFloat(data);
                    }
                });

                $.ajax({
                    url: "../assets/php/ajax/admin/getSettingsInformation.php",
                    method: "POST",
                    cache: false,
                    data: {Settings_Info: "Price_Per_KM_Driver"},
                    success: function(data){
                        priceperkm = parseFloat(data);
                    }
                });


                $('#btn-back').click(function() {
                    window.location.href = "home.php";
                });

                getDashboardJS();
                getAiderDriverDashboardJS();
            });

        </script>
    </body>

</html>