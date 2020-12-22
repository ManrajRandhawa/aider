function getDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../members/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        // START: Set Wallet Value
        $.ajax({
            url: "../assets/php/ajax/user/getUserData.php",
            method: "POST",
            cache: false,
            data: {User_Email: user_email, User_Info: 'Credit'},
            success: function(data) {
                $('#user_credit').html("RM " + data + " <i class=\" ml-2 fas fa-wallet\"></i> +");
            }
        });
        //END: Set Wallet Value

        // START: Get First Time Login and show Modal
        $.ajax({
            url: "../assets/php/ajax/user/getUserData.php",
            method: "POST",
            cache: false,
            data: {User_Email: user_email, User_Info: "First_Login"},
            success: function (data) {
                if(data === "YES") {
                    $('.bd-example-modal-lg').modal({
                        backdrop: 'static',
                        keyboard: false,
                        show: true
                    });
                }
            }

        });
        // END: Get First Time Login and show Modal

        //START: Change Password when button is clicked
        $('#change-pass-btn').click(function() {
            let pswd = $('#pass').val();
            let cpswd = $('#confirm-pass').val();

            $.ajax({
                url: "../assets/php/ajax/user/updateFirstTimePassword.php",
                method: "POST",
                cache: false,
                data: {Password: pswd, Confirm_Password: cpswd, User_Email: user_email},
                success: function (data) {
                    let title, message;
                    if(parseInt(data, 10) === 0) {
                        $('.bd-example-modal-lg').modal('toggle');
                        title = "Success!";
                        message = "The password has been updated.";
                    } else if(parseInt(data, 10) === 2) {
                        title = "Something went wrong!";
                        message = "The passwords entered were not the same."
                    } else {
                        title = "Something went wrong!";
                        message = "There was an issue while trying to change your password.";
                    }
                    // Display Toast Message
                    $.ajax({
                        url: "../assets/php/ajax/ui/sendToastMessage.php",
                        method: "POST",
                        cache: false,
                        data: {Title: title, Message: message},
                        success: function(dataToast){
                            $('.toast-container').html(dataToast);
                            $('.toast-container-modal').html(dataToast);
                            $('.toast').toast('show');

                            setTimeout(function() {
                                $('.toast-container').html("");
                                $('.toast-container-modal').html("");
                            }, 5000);
                        }
                    });

                }
            });
        });
        //END: Change Password when button is clicked
    }
}

function getParcelDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../members/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        // START: Click Deliver Now Button
        $('#btn-deliver').click(function() {

            // Check if input values are valid
            let pickUpLocationVal = $('#pickUpSearch').val();
            let dropOffLocationVal = $('#dropOffSearch').val();

            if(!pickUpLocationVal && !dropOffLocationVal) {
                let titleLocError = "Oops! Something went wrong.";
                let messageLocError = "It seems that you've not completed the address entry. Please try again!";
                // Display Toast Message
                $.ajax({
                    url: "../assets/php/ajax/ui/sendToastMessage.php",
                    method: "POST",
                    cache: false,
                    data: {Title: titleLocError, Message: messageLocError},
                    success: function(dataToast){
                        $('.toast-container').html(dataToast);
                        $('.toast').toast('show');

                        setTimeout(function() {
                            $('.toast-container').html("");
                        }, 5000);
                    }
                });
            } else {
                let priceText, price, finalPrice;
                priceText = $('#text-price').text();
                price = priceText.replace('RM ', '');
                finalPrice = price.split(" ").slice(0, price.indexOf(',')).slice(0, price.indexOf(','));

                $.ajax({
                    url: "../assets/php/ajax/bookings/parcel/bookParcelDelivery.php",
                    method: "POST",
                    cache: false,
                    data: {Email: user_email, Pickup_Location: pickUpLocationVal, Dropoff_Location: dropOffLocationVal, Pickup_Details_Name: $('#pickup-details-name').val(), Pickup_Details_PhoneNum: $('#pickup-details-phonenum').val(), Dropoff_Details_Name: $('#dropoff-details-name').val(), Dropoff_Details_PhoneNum: $('#dropoff-details-phonenum').val(), Price: finalPrice},
                    success: function(data){
                        let title, message;
                        if(data === "NO-ERROR") {
                            title = "Finding a Rider";
                            message = "Your delivery request is now being sent to our riders. We'll find a rider for you soon enough. Hang tight!";
                        } else {
                            title = "Oops! Something went wrong.";
                            message = data;
                            alert(data);
                        }

                        // Display Toast Message
                        $.ajax({
                            url: "../assets/php/ajax/ui/sendToastMessage.php",
                            method: "POST",
                            cache: false,
                            data: {Title: title, Message: message},
                            success: function(dataToast){
                                $('.toast-container').html(dataToast);
                                $('.toast').toast('show');

                                setTimeout(function() {
                                    $('.toast-container').html("");
                                    window.location.href = 'home.php';
                                }, 5000);

                                $('#container-details').hide();

                                $('#btn-deliver').hide();
                                $('#btn-continue').show();


                            }
                        });
                    }
                });
            }
        });
        // END: Click Deliver Now Button
    }
}

function getAiderDriverDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../members/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");
        let rideID = 0;

        $.ajax({
            url: '../assets/php/ajax/user/getUserData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Home_Address"},
            success: function(data) {
                if(data !== "") {
                    $('#btn-drop-off-home').removeClass('d-none');
                } else {
                    if(!($('#btn-drop-off-home').hasClass('d-none'))) {
                        $('#btn-drop-off-home').addClass('d-none');
                    }
                }
            }
        });

        // START: Click Home Button
        $('#btn-drop-off-home').click(function() {
            $.ajax({
                url: '../assets/php/ajax/user/getUserData.php',
                method: 'POST',
                cache: false,
                data: {User_Email: user_email, User_Info: "Home_Address"},
                success: function(data) {
                    $('#dropOffSearch').val(data);
                }
            });
        });
        // END: Click Home Button

        // START: Click Cancel Button During Driver Search
        $('#btn-cancel-driver-ride').click(function() {
            $.ajax({
                url: "../assets/php/ajax/bookings/driver/cancelRide.php",
                method: "POST",
                cache: false,
                data: {RideID: rideID},
                success: function(data) {
                    if(data === "NO-ERROR") {
                        if(!($('#finding-rider-layout').hasClass('d-none'))) {
                            $('#main-full-container').fadeIn("fast", function() {
                                $('#main-full-container').removeClass('d-none');
                                $('#finding-rider-layout').addClass('d-none');
                            });
                        }
                    } else {
                        // Display Toast Message
                        $.ajax({
                            url: "../assets/php/ajax/ui/sendToastMessage.php",
                            method: "POST",
                            cache: false,
                            data: {Title: "An error occurred!", Message: "The ride could not be cancelled! Please try again."},
                            success: function(dataToast) {
                                $('.toast-container').html(dataToast);
                                $('.toast').toast('show');

                                setTimeout(function() {
                                    $('.toast-container').html("");
                                }, 5000);
                            }
                        });
                    }
                }
            });
        });
        // END: Click Cancel Button During Driver Search

        // START: Click Deliver Now Button
        $('#btn-deliver').click(function() {

            // Check if input values are valid
            let pickUpLocationVal = $('#pickUpSearch').val();
            let dropOffLocationVal = $('#dropOffSearch').val();

            if(!pickUpLocationVal && !dropOffLocationVal) {
                let titleLocError = "Oops! Something went wrong.";
                let messageLocError = "It seems that you've not completed the address entry. Please try again!";
                // Display Toast Message
                $.ajax({
                    url: "../assets/php/ajax/ui/sendToastMessage.php",
                    method: "POST",
                    cache: false,
                    data: {Title: titleLocError, Message: messageLocError},
                    success: function(dataToast) {
                        $('.toast-container').html(dataToast);
                        $('.toast').toast('show');

                        setTimeout(function() {
                            $('.toast-container').html("");
                        }, 5000);
                    }
                });
            } else {
                let priceText, price, finalPrice;
                priceText = $('#text-price').text();
                price = priceText.replace('RM ', '');
                finalPrice = price.split(" ").slice(0, price.indexOf(',')).slice(0, price.indexOf(','));

                $.ajax({
                    url: "../assets/php/ajax/bookings/driver/bookDriver.php",
                    method: "POST",
                    cache: false,
                    data: {Email: user_email, Pickup_Location: pickUpLocationVal, Dropoff_Location: dropOffLocationVal, Price: parseFloat(finalPrice)},
                    success: function(data){
                        let title, message;

                        if(!data.includes('ERROR')) {
                            rideID = parseInt(data);

                            $('#container-details').hide();
                            $('#btn-deliver').hide();
                            $('#btn-continue').show();

                            if($('#finding-rider-layout').hasClass('d-none')) {
                                $('#finding-rider-layout').html('<div class="row h-100">\n' +
                                    '                <div class="col-3"></div>\n' +
                                    '                <div class="col-6 text-center align-self-center mt-5" id="ripple-area">\n' +
                                    '                    <i class="fas fa-map-pin fa-5x text-white"></i>\n' +
                                    '                    <div class="ripple mt-n1"></div>\n' +
                                    '                </div>\n' +
                                    '                <div class="col-3"></div>\n' +
                                    '\n' +
                                    '                <div class="col-12 text-center p-0" id="ripple-desc">\n' +
                                    '                    <h5 class="font-weight-bold text-white mb-3">Finding you a nearby driver...</h5>\n' +
                                    '                    <h6 class="text-white">Give drivers some time to accept your booking.</h6>\n' +
                                    '                </div>\n' +
                                    '\n' +
                                    '                ');

                                $('#main-full-container').fadeOut("fast", function() {
                                    $('#main-full-container').addClass('d-none');
                                    $('#finding-rider-layout').fadeIn('fast', function() {
                                        $('#finding-rider-layout').removeClass('d-none');
                                    });
                                });
                            }

                            // START: Check if Driver/Rider is found
                            let checker = setInterval(function() {
                                if(!($('#finding-rider-layout').hasClass('d-none'))) {
                                    $.ajax({
                                        url: '../assets/php/ajax/bookings/driver/getStatus.php',
                                        method: 'POST',
                                        cache: false,
                                        data: {Order_ID: rideID, Order_Info: "Status"},
                                        success: function(data) {
                                            if(data !== "FINDING-RIDER") {

                                                $('#ripple-area').html("<i class=\"far fa-check-circle fa-5x text-white\"></i>");
                                                $('#ripple-desc').html("<h5 class=\"font-weight-bold text-white mb-3\">A driver has been found</h5>");
                                                $('#cancel-btn-layout').addClass('d-none');

                                                let timeOut = setTimeout(function() {
                                                    $('#finding-rider-layout').fadeOut('fast', function() {
                                                        $('#finding-rider-layout').addClass('d-none');
                                                        $('#main-full-container').fadeIn("fast", function() {
                                                            $('#main-full-container').removeClass('d-none');
                                                        });
                                                        clearInterval(checker);
                                                        clearTimeout(timeOut);
                                                    });

                                                    window.location.href = 'home.php?showorder=yes';

                                                }, 1500);

                                            }
                                        }
                                    });
                                }
                            }, 300);

                            // END: Check if Driver/Rider is found
                        } else {
                            title = "Oops! Something went wrong.";
                            message = data.split('ERROR: ')[1];

                            // Display Toast Message
                            $.ajax({
                                url: "../assets/php/ajax/ui/sendToastMessage.php",
                                method: "POST",
                                cache: false,
                                data: {Title: title, Message: message},
                                success: function(dataToast){
                                    $('.toast-container').html(dataToast);
                                    $('.toast').toast('show');

                                    setTimeout(function() {
                                        $('.toast-container').html("");
                                    }, 5000);

                                    $('#container-details').hide();

                                    $('#btn-deliver').hide();
                                    $('#btn-continue').show();
                                }
                            });
                        }
                    }
                });
            }
        });
        // END: Click Deliver Now Button
    }
}

let homeOngoingOrderBackButtonPressed = false;

function getHomeDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../members/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        $.ajax({
            url: '../assets/php/ajax/user/getUserData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Name"},
            success: function(data) {

                $('#home-hello-name').html(data.split(" ", 1));
            }
        });

        $.ajax({
            url: '../assets/php/ajax/user/getUserData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Credit"},
            success: function(data) {
                $('#home-wallet-balance').html(data);
            }
        });

        $.ajax({
            url: '../assets/php/ajax/user/getPromos.php',
            method: 'POST',
            cache: false,
            success: function(data) {
                $('#promotion-container').html(data);
            }
        });

        // START: Get Ongoing Order and Set Data

        function getOngoingData() {
            $.ajax({
                url: '../assets/php/ajax/user/getOngoingOrder.php',
                method: 'POST',
                cache: false,
                data: {User_Email: user_email},
                success: function(data) {
                    if(data !== "ERROR") {

                        let ongoingResults = JSON.parse(data);
                        console.log(ongoingResults);

                        let ongoingResultsCount = parseInt(ongoingResults[0]);

                        let carouselIndicators = "";
                        let carouselInner = "";
                        let oType = "";
                        let oStatus = "";
                        for(let i = 0; i < ongoingResultsCount; i++) {

                            let result = ongoingResults[1][0][i];
                            oStatus = result[5];


                            $.ajax({
                                url: '../assets/php/ajax/rider/getOrderDetails.php',
                                method: 'POST',
                                cache: false,
                                data: {Order_Type: result[2], Order_ID: result[1]},
                                success: function (dataOrder) {
                                    let orderDetailsData = JSON.parse(dataOrder);

                                    console.log(orderDetailsData);

                                    let pickUp = orderDetailsData[1];
                                    let dropOff = orderDetailsData[2];

                                    let pickUpStriped = pickUp.split(",");
                                    pickUpStriped = pickUpStriped[0] + ", " + pickUpStriped[1];

                                    let dropOffStriped = dropOff.split(",");
                                    dropOffStriped = dropOffStriped[0] + ", " + dropOffStriped[1];

                                    let riderID = 0;

                                    $.ajax({
                                        url: '../assets/php/ajax/user/getOneRiderFromTeam.php',
                                        method: 'POST',
                                        cache: false,
                                        data: {Team_ID: parseInt(orderDetailsData[7])},
                                        success: function (dataRiderFromTeam) {
                                            if(result[2] === "DRIVER") {
                                                riderID = dataRiderFromTeam;
                                            } else if(result[2] === "PARCEL") {
                                                riderID = orderDetailsData[12];
                                            } else if(result[2] === "FOOD") {
                                                riderID = orderDetailsData[13];
                                            }

                                            console.log(riderID);

                                            $.ajax({
                                                url: '../assets/php/ajax/user/getRiderLocation.php',
                                                method: 'POST',
                                                cache: false,
                                                data: {Rider_ID: parseInt(riderID)},
                                                success: function (dataRiderLoc) {
                                                    if(dataRiderLoc !== "ERROR") {
                                                        let riderLoc = dataRiderLoc.split(",");
                                                        let lat = riderLoc[0];
                                                        let lng = riderLoc[1];

                                                        $.ajax({
                                                            url: '../assets/php/ajax/user/getOrderDetailByID.php',
                                                            method: 'POST',
                                                            cache: false,
                                                            data: {Order_Type: result[2], Order_ID: parseInt(result[1]), Data: "Status"},
                                                            success: function (dataRiderStatus) {
                                                                let addTwo = "";
                                                                let progressBar = "";
                                                                let description = "";
                                                                let addStriped = "";
                                                                let time = 0;

                                                                if(dataRiderStatus === "HEADING_TO_PICKUP") {
                                                                    addTwo = pickUp;

                                                                    addStriped = addTwo.split(",");
                                                                    addStriped = addStriped[0] + ", " + addStriped[1];

                                                                    if(result[2] === "DRIVER") {
                                                                        description = "Your driver is heading to the pick-up location.";
                                                                    } else if(result[2] === "PARCEL") {
                                                                        description = "Your rider is heading to the pick-up location.";
                                                                    } else if(result[2] === "FOOD") {
                                                                        description = "Preparing your food. Your rider will pick it up once it's ready.";
                                                                    }

                                                                    progressBar = "<div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 100%;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"0\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>\n" +
                                                                        "                                                <div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 50%;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"75\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>\n" +
                                                                        "                                                <div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 0;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"100\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>\n" +
                                                                        "                                                <div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 0;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"100\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>";

                                                                } else if(dataRiderStatus === "HEADING_TO_DESTINATION") {
                                                                    addTwo = dropOff;

                                                                    addStriped = addTwo.split(",");
                                                                    addStriped = addStriped[0] + ", " + addStriped[1];

                                                                    if(result[2] === "DRIVER") {
                                                                        description = "Your driver is heading to the destination.";
                                                                    } else if(result[2] === "PARCEL") {
                                                                        description = "Your rider is heading to the destination.";
                                                                    } else if(result[2] === "FOOD") {
                                                                        description = "Your rider has picked up the food.";
                                                                    }

                                                                    progressBar = "<div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 100%;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"0\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>\n" +
                                                                        "                                                <div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 100%;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"75\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>\n" +
                                                                        "                                                <div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 50%;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"100\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>\n" +
                                                                        "                                                <div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 0;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"100\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>";
                                                                } else if(dataRiderStatus === "COMPLETED") {
                                                                    addTwo = dropOff;

                                                                    addStriped = addTwo.split(",");
                                                                    addStriped = addStriped[0] + ", " + addStriped[1];

                                                                    if(result[2] === "DRIVER") {
                                                                        description = "This ride has been completed.";
                                                                    } else if(result[2] === "PARCEL") {
                                                                        description = "This delivery has been completed.";
                                                                    } else if(result[2] === "FOOD") {
                                                                        description = "Your rider has picked up the food.";
                                                                    }

                                                                    progressBar = "<div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 100%;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"0\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>\n" +
                                                                        "                                                <div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 100%;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"75\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>\n" +
                                                                        "                                                <div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 100%;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"100\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>\n" +
                                                                        "                                                <div class=\"col-3 pad-1\">\n" +
                                                                        "                                                    <div class=\"progress\">\n" +
                                                                        "                                                        <div class=\"progress-bar\" role=\"progressbar\" style=\"width: 100%;\" aria-valuemin=\"0\" aria-valuemax=\"100\" aria-valuenow=\"100\"></div>\n" +
                                                                        "                                                    </div>\n" +
                                                                        "                                                </div>";
                                                                }

                                                                console.log(dataRiderStatus);

                                                                $.ajax({
                                                                    url: '../assets/php/ajax/user/getRiderDataArray.php',
                                                                    method: 'POST',
                                                                    cache: false,
                                                                    data: {Rider_ID: parseInt(riderID)},
                                                                    success: function (dataRiderArray) {
                                                                        dataRiderArray = JSON.parse(dataRiderArray);

                                                                        $.ajax({
                                                                            url: '../assets/php/ajax/user/getTime.php',
                                                                            method: 'POST',
                                                                            cache: false,
                                                                            data: {riderLat: lat, riderLng: lng, addressTwo: addTwo},
                                                                            success: function (dataTime) {
                                                                                if(!(i === 0)) {
                                                                                    carouselIndicators += "<li data-target=\"#carouselOrders\" data-slide-to='" + i + "'></li>";
                                                                                    carouselInner += "<div class=\"carousel-item\">";
                                                                                } else {
                                                                                    carouselIndicators += "<li data-target=\"#carouselOrders\" data-slide-to=\"0\" class=\"active\"></li>";
                                                                                    carouselInner += "<div class=\"carousel-item active\">";
                                                                                }

                                                                                time = dataTime;

                                                                                let timingData = "";
                                                                                let ratingData = "";

                                                                                if(dataRiderStatus === "COMPLETED") {
                                                                                    timingData = "<i class=\"far fa-check-circle fa-3x text-center text-primary mb-3\"></i>";

                                                                                    ratingData = "                            <hr class='mt-3'/>\n" +
                                                                                        "                            <div class='row m-2'>\n" +
                                                                                        "                                <div class='col-12 mt-2 mb-3'>\n" +
                                                                                        "<div class=\"rating\">\n" +
                                                                                        "                <input type=\"radio\" name=\"rating\" value=\"5\" id='5-" + result[1] + "'>\n" +
                                                                                        "                <label for='5-" + result[1] + "'>☆</label>\n" +
                                                                                        "                <input type=\"radio\" name=\"rating\" value=\"4\" id='4-" + result[1] + "'>\n" +
                                                                                        "                <label for='4-" + result[1] + "'>☆</label>\n" +
                                                                                        "                <input type=\"radio\" name=\"rating\" value=\"3\" id='3-" + result[1] + "'>\n" +
                                                                                        "                <label for='3-" + result[1] + "'>☆</label>\n" +
                                                                                        "                <input type=\"radio\" name=\"rating\" value=\"2\" id='2-" + result[1] + "'>\n" +
                                                                                        "                <label for='2-" + result[1] + "'>☆</label>\n" +
                                                                                        "                <input type=\"radio\" name=\"rating\" value=\"1\" id='1-" + result[1] + "'>\n" +
                                                                                        "                <label for='1-" + result[1] + "'>☆</label>\n" +
                                                                                        "            </div>" +
                                                                                        "<button class='btn btn-outline-primary' id='" + result[2] + "-" + result[1] + "' onclick='rateRide(this.id);'>Rate</button>" +
                                                                                        "                                </div>\n" +
                                                                                        "                            </div>\n";
                                                                                } else {
                                                                                    timingData = "<h6 class=\"text-black-50 mt-3 mb-3\">Estimate time of arrival</h6>\n<h2 class=\"text-dark font-weight-bold\" id=\"eta\">" + dataTime + "</h2>\n";
                                                                                    ratingData = "";
                                                                                }

                                                                                let currentRating = parseFloat(parseFloat(dataRiderArray[4]) / parseFloat(dataRiderArray[5])).toFixed(1);

                                                                                if(currentRating === "NaN") {
                                                                                    currentRating = 0.0;
                                                                                }

                                                                                carouselInner += "\n" +
                                                                                    timingData +
                                                                                    "\n" +
                                                                                    "<div class='row'><div class='col-12'><img src='../assets/images/car-full.png' style='width: 80vw;' /></div></div>" +
                                                                                    "\n" +
                                                                                    "                                    <div class=\"mt-3\"></div>\n" +
                                                                                    "\n" +
                                                                                    "                                    <div class=\"row text-center\">\n" +
                                                                                    "                                        <div class=\"col-5 text-primary text-center\" id=\"add-pickup\">" + pickUpStriped + "</div>\n" +
                                                                                    "                                        <div class=\"col-2 p-0\">\n" +
                                                                                    "                                            <i class=\"fas fa-angle-right fa-lg text-primary\"></i>\n" +
                                                                                    "                                        </div>\n" +
                                                                                    "                                        <div class=\"col-5 text-primary text-center\" id=\"add-dropoff\">" + dropOffStriped + "</div>\n" +
                                                                                    "                                    </div>\n" +
                                                                                    "\n" +
                                                                                    "                                    <div class=\"mt-3\"></div>\n" +
                                                                                    "\n" +
                                                                                    "                                    <div class=\"row\">\n" +
                                                                                    "                                        <div class=\"col-1\"></div>\n" +
                                                                                    "                                        <div class=\"col-10\">\n" +
                                                                                    "                                            <div class=\"row\">\n" +
                                                                                    progressBar +
                                                                                    "                                            </div>\n" +
                                                                                    "\n" +
                                                                                    "                                            <h6 class=\"mt-3 text-muted\">" + description + "</h6>\n" +
                                                                                    "                                        </div>\n" +
                                                                                    "                                        <div class=\"col-1\"></div>\n" +
                                                                                    "                                    </div>\n" +
                                                                                    ratingData +
                                                                                    "                           <hr class='mt-3'/>\n" +
                                                                                    "                            <div class='row m-2'>\n" +
                                                                                    "                                <div class='col-12 mt-2 mb-3'>\n" +
                                                                                    "                                    <i class=\"far fa-id-card fa-2x text-primary\"></i>\n" +
                                                                                    "                                </div>\n" +
                                                                                    "                                <div class='col-6 text-left'>\n" +
                                                                                    "                                    <a class='text-muted'>Name</a><br/>\n" +
                                                                                    "                                    <a class='text-muted'>Phone Number</a><br/>\n" +
                                                                                    "                                    <a class='text-muted'>Rating</a>\n" +
                                                                                    "                                </div>\n" +
                                                                                    "                                <div class='col-6 text-right'>\n" +
                                                                                    "                                    <a class='text-dark'>" + dataRiderArray[0] + "</a><br/>\n" +
                                                                                    "                                    <a class='text-decoration-none' href='tel:" + dataRiderArray[1] + "'>" + dataRiderArray[1] + "</a><br/>\n" +
                                                                                    "                                    <a class='text-dark'>" + currentRating + "<i class=\"far fa-star text-primary ml-1\"></i></a>\n" +
                                                                                    "                                </div>\n" +
                                                                                    "                            </div>\n" +
                                                                                    "                            <hr class='mt-3'/>\n" +
                                                                                    "                            <div class='row m-2'>\n" +
                                                                                    "                                <div class='col-12 mt-2 mb-3'>\n" +
                                                                                    "                                    <i class=\"fas fa-car fa-2x text-primary\"></i>\n" +
                                                                                    "                                </div>\n" +
                                                                                    "                                <div class='col-6 text-left'>\n" +
                                                                                    "                                    <a class='text-muted'>Vehicle Model</a><br/>\n" +
                                                                                    "                                    <a class='text-muted'>Number Plate</a>\n" +
                                                                                    "                                </div>\n" +
                                                                                    "                                <div class='col-6 text-right'>\n" +
                                                                                    "                                    <a class='text-dark'>" + dataRiderArray[2] + "</a><br/>\n" +
                                                                                    "                                    <a class='text-dark'>" + dataRiderArray[3] + "</a>\n" +
                                                                                    "                                </div>\n" +
                                                                                    "                            </div>\n" +
                                                                                    "                            <hr class='mt-3'/>\n" +
                                                                                    "                            <div class='row m-2'>\n" +
                                                                                    "                                <div class='col-12 mt-2 mb-3'>\n" +
                                                                                    "                                    <h5 class='text-primary'>Customer Service</h5>\n" +
                                                                                    "                                </div>\n" +
                                                                                    "                                <div class='col-6 text-left'>\n" +
                                                                                    "                                    <a class='text-muted'>Steven</a><br/>\n" +
                                                                                    "                                    <a class='text-muted'>Tony</a><br/>\n" +
                                                                                    "                                    <a class='text-muted'>Ahyan</a>\n" +
                                                                                    "                                </div>\n" +
                                                                                    "                                <div class='col-6 text-right'>\n" +
                                                                                    "                                    <a href='tel:0102070866' class='text-primary'>010 207 0866</a><br/>\n" +
                                                                                    "                                    <a href='tel:0187612888' class='text-primary'>018 761 2888</a><br/>\n" +
                                                                                    "                                    <a href='tel:0176551171' class='text-primary'>017 655 1171</a>\n" +
                                                                                    "                                </div>\n" +
                                                                                    "                            </div>\n" +
                                                                                    "                            <br/>\n" +
                                                                                    "                            <br/>" +
                                                                                    "\n" +
                                                                                    "                                    <br/>\n" +
                                                                                    "                                </div></div>";

                                                                            }, complete: function(dataTime) {
                                                                                $('#loc-add').html(addStriped);
                                                                                $('#prog-bar').html(progressBar);
                                                                                $('#desc').html(description);

                                                                                if(dataRiderStatus !== 'COMPLETED') {
                                                                                    $('#time-order').html(time);
                                                                                } else {
                                                                                    $('#time-order').html("");
                                                                                }

                                                                                if($('#ongoing-order-small-container').hasClass('d-none')) {
                                                                                    $('#ongoing-order-small-container').fadeIn('fast').removeClass('d-none');
                                                                                }

                                                                                $('#ca-ind').html(carouselIndicators);
                                                                                $('#ca-inn').html(carouselInner);

                                                                                if(window.location.href.includes('?showorder=yes')) {
                                                                                    if(!homeOngoingOrderBackButtonPressed) {
                                                                                        if(!($('#main-content-home').hasClass('d-none'))) {
                                                                                            $('#main-content-home').fadeOut('fast', function() {
                                                                                                $('#main-content-home').addClass('d-none');
                                                                                                $('#main-order-container-home').fadeIn('fast', function() {
                                                                                                    $('#main-order-container-home').removeClass('d-none');
                                                                                                });
                                                                                            });
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        });

                                                                    }
                                                                });

                                                            }
                                                        });
                                                    }
                                                }
                                            });

                                        }
                                    });
                                }
                            });
                        }
                    }
                }
            });
        }

        getOngoingData();

        setInterval(function() {
            getOngoingData();
        }, 5000);

        // END: Get Ongoing Order and Set Data

        // START: Open Ongoing Order
        $('#ongoing-order-small-container').click(function() {
            if(!($('#main-content-home').hasClass('d-none'))) {
                $('#main-content-home').fadeOut('fast', function() {
                    $('#main-content-home').addClass('d-none');
                    $('#main-order-container-home').fadeIn('fast', function() {
                        $('#main-order-container-home').removeClass('d-none');
                    });
                });
            }
        });
        // END: Open Ongoing Order

        // START: Close Ongoing Order
        $('#close-ongoing-order').click(function() {
            homeOngoingOrderBackButtonPressed = true;

            if(!($('#main-order-container-home').hasClass('d-none'))) {
                $('#main-order-container-home').fadeOut('fast', function() {
                    $('#main-order-container-home').addClass('d-none');
                    $('#main-content-home').fadeIn('fast', function() {
                        $('#main-content-home').removeClass('d-none');
                    });
                });
            }
        });
        // END: Close Ongoing Order
    }
}

function rateRide(order) {
    let orderType = order.split("-")[0];
    let orderID = order.split("-")[1];

    let rating = 0;

    if($('#1-' + orderID).is(':checked')) {
        rating = 1;
    }
    if($('#2-' + orderID).is(':checked')) {
        rating = 2;
    }
    if($('#3-' + orderID).is(':checked')) {
        rating = 3;
    }
    if($('#4-' + orderID).is(':checked')) {
        rating = 4;
    }
    if($('#5-' + orderID).is(':checked')) {
        rating = 5;
    }

    $.ajax({
        url: '../assets/php/ajax/user/addRating.php',
        method: 'POST',
        cache: false,
        data: {Order_Type: orderType, Order_ID: orderID, Rating: rating},
        success: function(data) {
            if(data === "NO-ERROR") {
                $.ajax({
                    url: "../assets/php/ajax/rider/setOrderStatus.php",
                    method: "POST",
                    cache: false,
                    data: {Order_Type: orderType, Order_ID: orderID, Status: 'COMPLETED_RATED'},
                    success: function (data) {
                        if(data !== "TRUE") {
                            $.ajax({
                                url: "../assets/php/ajax/ui/sendToastMessage.php",
                                method: "POST",
                                cache: false,
                                data: {Title: "Rating", Message: "Thank you for rating!"},
                                success: function(dataToast){
                                    $('.toast-container-modal').html(dataToast);
                                    $('.toast').toast('show');

                                    setTimeout(function() {
                                        $('.toast-container-modal').html("");
                                    }, 5000);

                                    window.location.href = 'home.php';
                                }
                            });
                        } else {
                            $.ajax({
                                url: "../assets/php/ajax/ui/sendToastMessage.php",
                                method: "POST",
                                cache: false,
                                data: {Title: "Rating", Message: "There was an issue while rating. Please try again."},
                                success: function(dataToast){
                                    $('.toast-container-modal').html(dataToast);
                                    $('.toast').toast('show');

                                    setTimeout(function() {
                                        $('.toast-container-modal').html("");
                                    }, 5000);
                                }
                            });
                        }
                    }
                });
            } else {
                $.ajax({
                    url: "../assets/php/ajax/ui/sendToastMessage.php",
                    method: "POST",
                    cache: false,
                    data: {Title: "Rating", Message: "There was an issue while rating. Please try again."},
                    success: function(dataToast){
                        $('.toast-container-modal').html(dataToast);
                        $('.toast').toast('show');

                        setTimeout(function() {
                            $('.toast-container-modal').html("");
                        }, 5000);
                    }
                });
            }
        }
    });
}


let checkError = false;
function getAmount(user_email, params) {
    // Fetch Amount from Database
    $.ajax({
        url: '../assets/php/ajax/user/payment/getCompletedPaymentAmount.php',
        method: 'POST',
        cache: false,
        data: {User_Email: user_email, Ref: params['billplz[id]'][0]},
        success: function(data) {
            if(data === "ERROR") {
                getAmount(user_email, params);
            } else {
                $('#payment-complete-rm').html(data);
                
                // Completion Spinner
                $('.circle-loader').toggleClass('load-complete');
                $('.checkmark').toggle();

                $('#payment-complete-text').removeClass('d-none');
            }
        }
    });
}

function getPaymentDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../members/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        // Check GET Request
        if(window.location.href.includes('?')) {
            let params = "";
            params = parseURLParams(window.location.href);

            if(params['billplz[paid]'][0] === 'true') {
                // Display Payment Completion Layout
                $('#payment-complete-ref').html(params['billplz[id]'][0]);

                // START: Set Proper Layouts
                if(!$('#payment-container-top-v1').hasClass('d-none')) {
                    $('#payment-container-top-v1').addClass('d-none');
                }

                if(!$('#payment-container-bottom-v1').hasClass('d-none')) {
                    $('#payment-container-bottom-v1').addClass('d-none');
                }

                if(!$('#payment-container-top-v2').hasClass('d-none')) {
                    $('#payment-container-top-v2').addClass('d-none');
                }

                if(!$('#payment-failed-layout').hasClass('d-none')) {
                    $('#payment-failed-layout').addClass('d-none');
                }

                if(!$('#bottom-nav').hasClass('d-none')) {
                    $('#bottom-nav').addClass('d-none');
                }

                if(!$('#aider-header').hasClass('d-none')) {
                    $('#aider-header').addClass('d-none');
                }

                if($('#payment-completed-layout').hasClass('d-none')) {
                    $('#payment-completed-layout').removeClass('d-none');
                }
                // END: Set Proper Layouts

                getAmount(user_email, params);

            } else {
                // Display Payment Failure Layout

                // Display Payment Completion Layout
                $('#payment-fail-ref').html(params['billplz[id]'][0]);

                // START: Set Proper Layouts
                if(!$('#payment-container-top-v1').hasClass('d-none')) {
                    $('#payment-container-top-v1').addClass('d-none');
                }

                if(!$('#payment-container-bottom-v1').hasClass('d-none')) {
                    $('#payment-container-bottom-v1').addClass('d-none');
                }

                if(!$('#payment-container-top-v2').hasClass('d-none')) {
                    $('#payment-container-top-v2').addClass('d-none');
                }

                if(!$('#payment-completed-layout').hasClass('d-none')) {
                    $('#payment-completed-layout').addClass('d-none');
                }

                if(!$('#bottom-nav').hasClass('d-none')) {
                    $('#bottom-nav').addClass('d-none');
                }

                if(!$('#aider-header').hasClass('d-none')) {
                    $('#aider-header').addClass('d-none');
                }

                if($('#payment-failed-layout').hasClass('d-none')) {
                    $('#payment-failed-layout').removeClass('d-none');
                }
                // END: Set Proper Layouts

                setTimeout(function() {
                    // Completion Spinner
                    $('.circle-loader').toggleClass('load-complete');
                    $('.checkmark').toggle();

                    $('#payment-fail-text').removeClass('d-none');
                }, 1000);
            }

            console.log(params);

            console.log(params['billplz[id]'][0]);
            console.log(params['billplz[paid]'][0]);
            console.log(params['billplz[paid_at]'][0]);
            console.log(params['billplz[x_signature]'][0]);
        }

        $.ajax({
            url: '../assets/php/ajax/user/getUserData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Credit"},
            success: function(data) {
                $('#wallet-balance').html(data);
            }
        });

        // START: Get Recent Transactions
        $.ajax({
            url: '../assets/php/ajax/user/getUserData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "ID"},
            success: function(data) {
                $.ajax({
                    url: '../assets/php/ajax/user/payment/getRecentTransactions.php',
                    method: 'POST',
                    cache: false,
                    data: {User_ID: data},
                    success: function(dataTransaction) {
                        $('#transaction-container').html(dataTransaction);
                    }
                });
            }
        });
        // END: Get Recent Transactions

        $('#top-up-wallet').click(function() {

            // Fade (1st View) content & Display (2nd View) content
            $('#payment-container-top-v1').fadeOut("fast");
            $('#payment-container-bottom-v1').fadeOut("fast", function() {
                setTimeout(function() {
                    $('#payment-container-top-v2').removeClass('d-none').fadeIn('fast');
                }, 133);
            });

        });

        // Create bill and perform transaction
        $('#confirm-transaction-btn').unbind().on('click', function() {

            let select = document.getElementById('bank-code');
            let bankCode = select.options[select.selectedIndex].value;

            let amount = $('#transaction-amount').val();

            $.ajax({
                url: '../assets/php/ajax/user/getUserData.php',
                method: 'POST',
                cache: false,
                data: {User_Email: user_email, User_Info: "Name"},
                success: function(data) {
                    $.ajax({
                        url: '../assets/php/ajax/user/payment/performTransaction.php',
                        method: 'POST',
                        cache: false,
                        data: {User_Email: user_email, User_Name: data, Bank_Code: bankCode, Amount: amount},
                        success: function(transactionData) {
                            window.location.href = transactionData;
                        }
                    });
                }
            });
        });
    }
}

function getAccountDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../members/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        $.ajax({
            url: '../assets/php/ajax/user/getUserData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Name"},
            success: function(data) {
                $('#user-name').html(data);
            }
        });

        $.ajax({
            url: '../assets/php/ajax/user/getUserData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Credit"},
            success: function(data) {
                $('#wallet-balance').html(data);
            }
        });

        $.ajax({
            url: '../assets/php/ajax/user/getUserData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Home_Address"},
            success: function(data) {
                $('#current-home').val(data);
            }
        });

        // START: Change Email
        $('#chg-email-btn').click(function() {
            let email = $('#chg-email').val();

            $.ajax({
                url: "../assets/php/ajax/user/updateUserData.php",
                method: "POST",
                cache: false,
                data: {User_Email: user_email, Key: "Email_Address", Value: email},
                success: function (data) {
                    let title, message;

                    // data = error. If true, an error occurred, else, no error occurred.
                    if(data === "FALSE") {
                        title = "Success!";
                        message = "The email has been updated.";
                        window.localStorage.setItem("User_Email", email);
                    } else {
                        title = "Something went wrong!";
                        message = "There was an issue while trying to change your email.";
                    }
                    // Display Toast Message
                    $.ajax({
                        url: "../assets/php/ajax/ui/sendToastMessage.php",
                        method: "POST",
                        cache: false,
                        data: {Title: title, Message: message},
                        success: function(dataToast){
                            $('.toast-container-modal').html(dataToast);
                            $('.toast').toast('show');

                            setTimeout(function() {
                                $('.toast-container-modal').html("");
                            }, 5000);
                        }
                    });
                }
            });
        });
        // END: Change Email

        // START: Change Password
        $('#chg-pass-btn').click(function() {
            let pswd = $('#chg-pass').val();
            let cpswd = $('#chg-confirm-pass').val();

            $.ajax({
                url: "../assets/php/ajax/user/updateFirstTimePassword.php",
                method: "POST",
                cache: false,
                data: {Password: pswd, Confirm_Password: cpswd, User_Email: user_email},
                success: function (data) {
                    let title, message;
                    if(parseInt(data, 10) === 0) {
                        title = "Success!";
                        message = "The password has been updated.";
                    } else if(parseInt(data, 10) === 2) {
                        title = "Something went wrong!";
                        message = "The passwords entered were not the same."
                    } else {
                        title = "Something went wrong!";
                        message = "There was an issue while trying to change your password.";
                    }
                    // Display Toast Message
                    $.ajax({
                        url: "../assets/php/ajax/ui/sendToastMessage.php",
                        method: "POST",
                        cache: false,
                        data: {Title: title, Message: message},
                        success: function(dataToast){
                            $('.toast-container-modal').html(dataToast);
                            $('.toast').toast('show');

                            setTimeout(function() {
                                $('.toast-container-modal').html("");
                            }, 5000);
                        }
                    });
                }
            });
        });
        // END: Change Password

        // START: Change Phone Number
        $('#chg-num-btn').click(function() {
            let num = $('#chg-num').val();

            $.ajax({
                url: "../assets/php/ajax/user/updateUserData.php",
                method: "POST",
                cache: false,
                data: {User_Email: user_email, Key: "Phone_Number", Value: num},
                success: function (data) {
                    let title, message;

                    // data = error. If true, an error occurred, else, no error occurred.
                    if(data === "FALSE") {
                        title = "Success!";
                        message = "The phone number has been updated.";
                    } else {
                        title = "Something went wrong!";
                        message = "There was an issue while trying to change your phone number.";
                    }
                    // Display Toast Message
                    $.ajax({
                        url: "../assets/php/ajax/ui/sendToastMessage.php",
                        method: "POST",
                        cache: false,
                        data: {Title: title, Message: message},
                        success: function(dataToast){
                            $('.toast-container-modal').html(dataToast);
                            $('.toast').toast('show');

                            setTimeout(function() {
                                $('.toast-container-modal').html("");
                            }, 5000);
                        }
                    });
                }
            });
        });
        // END: Change Phone Number

        // START: Set Home Address
        $('#set-home-btn').click(function() {
            let address = $('#set-home').val();

            $.ajax({
                url: "../assets/php/ajax/user/updateUserData.php",
                method: "POST",
                cache: false,
                data: {User_Email: user_email, Key: "Home_Address", Value: address},
                success: function (data) {
                    let title, message;

                    // data = error. If true, an error occurred, else, no error occurred.
                    if(data === "FALSE") {
                        title = "Success!";
                        message = "Your home address has been updated.";
                    } else {
                        title = "Something went wrong!";
                        message = "There was an issue while trying to update your home address.";
                    }

                    // Display Toast Message
                    $.ajax({
                        url: "../assets/php/ajax/ui/sendToastMessage.php",
                        method: "POST",
                        cache: false,
                        data: {Title: title, Message: message},
                        success: function(dataToast){
                            $('.toast-container-modal').html(dataToast);
                            $('.toast').toast('show');

                            setTimeout(function() {
                                $('.toast-container-modal').html("");
                                $('#set-home-address').modal('toggle');
                                $('#set-home').val('');
                            }, 1000);

                            $.ajax({
                                url: '../assets/php/ajax/user/getUserData.php',
                                method: 'POST',
                                cache: false,
                                data: {User_Email: user_email, User_Info: "Home_Address"},
                                success: function(data) {
                                    $('#current-home').val(data);
                                }
                            });
                        }
                    });
                }
            });
        });
        // END: Set Home Address

        // START: Logout
        $('#logout').click(function() {
            window.localStorage.clear();
            window.location.href = "index.php";
        });
        // END: Logout
    }
}

function parseURLParams(url) {
    var queryStart = url.indexOf("?") + 1,
        queryEnd   = url.indexOf("#") + 1 || url.length + 1,
        query = url.slice(queryStart, queryEnd - 1),
        pairs = query.replace(/\+/g, " ").split("&"),
        parms = {}, i, n, v, nv;

    if (query === url || query === "") return;

    for (i = 0; i < pairs.length; i++) {
        nv = pairs[i].split("=", 2);
        n = decodeURIComponent(nv[0]);
        v = decodeURIComponent(nv[1]);

        if (!parms.hasOwnProperty(n)) parms[n] = [];
        parms[n].push(nv.length === 2 ? v : null);
    }
    return parms;
}