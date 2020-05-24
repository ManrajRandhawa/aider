<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php echo SITE_NAME; ?> | Parcel</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
        <style type="text/css">
            #map {
                height: 100%;
            }

            body.bg-light,
            div.container-fluid,
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

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark min-vw-100 nav-pills nav-fill">
            <a class="navbar-brand text-primary" href="#"><?php echo SITE_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link d-inline-block" href="food.php">Food</a>
                    <a class="nav-item nav-link active d-inline-block" href="#">Parcel</a>
                    <a class="nav-item nav-link d-inline-block" href="#">Wallet</a>
                    <a class="nav-item nav-link d-inline-block" href="#">Transactions</a>
                </div>
                <div class="navbar-nav ml-auto">
                    <a class="nav-item nav-link d-inline-block mr-lg-2 ml-lg-2" href="#">
                        <table class="table table-bordered table-hover rounded m-0 p-0" style="border-color: #007bff;">
                            <tr>
                                <td style="border-color: #007bff;" class="p-0 pr-3 pl-3 text-white" id="user_name">Name</td>
                                <td style="border-color: #007bff;" class="p-0 pr-3 pl-3 text-white" id="user_credit">RM 0</td>
                            </tr>
                        </table>
                    </a>
                    <a class="nav-item nav-link d-inline-block fa-lg mr-lg-2 ml-lg-2" href="#">
                        <i class="fas fa-cog"></i>
                    </a>
                    <a class="nav-item nav-link d-inline-block fa-lg mr-lg-2 ml-lg-2" href="#">
                        <i class="far fa-question-circle"></i>
                    </a>
                </div>
            </div>
        </nav>



        <!-- Main Content -->
        <div class="container-fluid">

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

            <div class="row map-row">
                <div class="col-12 map-col-12">
                    <div id="map"></div>
                </div>
            </div>
            <div class="container mt-3">
                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-10">
                        <input id="pickUpSearch" type="text" class="form-control mt-5" placeholder="Pick Up Location"/>
                        <input id="dropOffSearch" type="text" class="form-control mt-3" placeholder="Drop Off Location"/>
                        <div class="row mt-5 rounded" style="background: #cce4ff">
                            <div class="col-12">
                                <h4 class="text-center" id="text-price">RM 0.00</h4>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 mb-2" id="btn-deliver">Deliver Now</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-1"></div>
                </div>
            </div>

        </div>


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Dashboard.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQHLdxkQcezk4kKWaX219nHla2xUHJ274&libraries=places&callback=initMap" async defer></script>
        <script src="https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=Washington,DC&destinations=New+York+City,NY&key=AIzaSyBQHLdxkQcezk4kKWaX219nHla2xUHJ274" async defer></script>

        <script>
            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: 3.139, lng: 101.547},
                    zoom: 10,
                    disableDefaultUI: true
                });
            }

            $(document).ready(function(){
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);

                getDashboardJS();

                var pickUp = document.getElementById('pickUpSearch');
                var autocompletePickUp = new google.maps.places.Autocomplete(pickUp);
                autocompletePickUp.setComponentRestrictions({'country': ['my']});

                var dropOff = document.getElementById('dropOffSearch');
                var autocompleteDropOff = new google.maps.places.Autocomplete(dropOff);
                autocompleteDropOff.setComponentRestrictions({'country': ['my']});

                let latOri = -999;
                let lngOri = -999;
                let latDest = -999;
                let lngDest = -999;

                let distance;
                let duration;
                let price;

                autocompletePickUp.addListener('place_changed', function () {
                    var place = autocompletePickUp.getPlace();
                    latOri = place.geometry.location.lat();
                    lngOri = place.geometry.location.lng();

                    if(latDest !== -999 && lngDest !== -999) {
                        //Find the distance
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
                                    $('#text-price').text("RM " + price.toFixed(2));

                                    $.ajax({
                                        url: "",
                                        method: "POST",
                                        data: {Price: price},
                                        success: function(data) {

                                        }
                                    });
                                }
                            });
                    }
                });

                autocompleteDropOff.addListener('place_changed', function() {
                    var place = autocompleteDropOff.getPlace();
                    latDest = place.geometry.location.lat();
                    lngDest = place.geometry.location.lng();

                    if(latOri !== -999 && lngOri !== -999) {
                        //Find the distance
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
                                    $('#text-price').text("RM " + price.toFixed(2));

                                    $.ajax({
                                        url: "",
                                        method: "POST",
                                        data: {Price: price},
                                        success: function(data) {

                                        }
                                    });
                                }
                            });
                    }
                });

                $('#btn-deliver').click(function() {

                });

            });

        </script>
    </body>

</html>