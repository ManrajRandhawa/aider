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
                    data: {Email: user_email, Pickup_Location: pickUpLocationVal, Dropoff_Location: dropOffLocationVal, Price: finalPrice},
                    success: function(data){
                        let title, message;

                        if(data !== "ERROR") {
                            rideID = parseInt(data);

                            $('#container-details').hide();
                            $('#btn-deliver').hide();
                            $('#btn-continue').show();

                            if($('#finding-rider-layout').hasClass('d-none')) {
                                $('#finding-rider-layout').html('<div class="row h-100">\n' +
                                    '                <div class="col-3"></div>\n' +
                                    '                <div class="col-6 text-center align-self-center mt-5" id="ripple-area">\n' +
                                    '                    <img src="../assets/images/aider-logo.png" style="height: 150px; width: auto;" class="d-block ml-auto mr-auto"/>\n' +
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


                                                }, 1500);

                                            }
                                        }
                                    });
                                }
                            }, 300);

                            // END: Check if Driver/Rider is found
                        } else {
                            title = "Oops! Something went wrong.";
                            message = data;

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

        // START: Get Ongoing Order and Set Data
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

                                                    console.log(riderLoc);

                                                    $.ajax({
                                                        url: '../assets/php/ajax/user/getRiderData.php',
                                                        method: 'POST',
                                                        cache: false,
                                                        data: {Rider_ID: parseInt(riderID), Data: "Status"},
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
                                                            }

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

                                                                    carouselInner += "\n" +
                                                                        "                                    <h6 class=\"text-black-50 mt-4\">Estimate time of arrival</h6>\n" +
                                                                        "                                    <h2 class=\"text-dark font-weight-bold\" id=\"eta\">" + dataTime + "</h2>\n" +
                                                                        "\n" +
                                                                        "                                    <img src=\"../assets/images/ongoing-1.png\" style=\"width: 90%\" />\n" +
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
                                                                        "\n" +
                                                                        "                                    <br/>\n" +
                                                                        "                                </div></div>";
                                                                }, complete: function(dataTime) {
                                                                    $('#loc-add').html(addStriped);
                                                                    $('#prog-bar').html(progressBar);
                                                                    $('#desc').html(description);
                                                                    $('#time-order').html(time);

                                                                    if($('#ongoing-order-small-container').hasClass('d-none')) {
                                                                        $('#ongoing-order-small-container').fadeIn('fast').removeClass('d-none');
                                                                    }

                                                                    $('#ca-ind').html(carouselIndicators);
                                                                    $('#ca-inn').html(carouselInner);
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

function getPaymentDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../members/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

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

        $('#btn-back').click(function() {
            $('#payment-container-top-v2').fadeOut('fast', function() {
                setTimeout(function() {
                    $('#payment-container-top-v1').fadeIn('fast');
                    $('#payment-container-bottom-v1').fadeIn('fast');
                }, 133);
            });
        });

        // Create bill and perform transaction
        $('#confirm-transaction-btn').click(function() {
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
                            window.open(transactionData);
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