// Define Enums for Rider - Active or Inactive Mode
const Mode = Object.freeze({"INACTIVE": 0, "ACTIVE": 1});
let riderMode = Mode.INACTIVE;

let orderLogicCalled = false;
let isOrderBeingDisplayed = false;
let currentOrder = 0;
let riderID = 0;


function getRiderDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../riders/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        // START: Get First Time Login and show Modal
        $.ajax({
            url: "../assets/php/ajax/rider/getRiderData.php",
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
                url: "../assets/php/ajax/rider/updateFirstTimePassword.php",
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

function getHomeJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../riders/index.php";
    } else {
        // START: Rider - Active and Inactive Mode
        $('#btn-inactive').click(function() {
            $('#btn-inactive').addClass('d-none');
            $('#btn-active').removeClass('d-none');
            riderMode = Mode.ACTIVE;

            getNewOrders("PARCEL");
        });

        $('#btn-active').click(function () {
            $('#btn-active').addClass('d-none');
            $('#btn-inactive').removeClass('d-none');
            riderMode = Mode.INACTIVE;

            $('#order-container').addClass('d-none');
        });
        // END: Rider - Active and Inactive Mode
    }
}

// STEP 1: Show Order
function createNewOrder(type, pickUpLoc, dropOffLoc, travelDistance, timeRemaining, orderDetails, totalPrice, paidStatus) {
    let transactionType, pickUpLocPrimary, pickUpLocSecondary, dropOffLocPrimary, dropOffLocSecondary;

    if(type === "PARCEL") {
        transactionType = "Parcel";
    } else {
        transactionType = "Food";
    }

    pickUpLocPrimary = pickUpLoc.split(",", 1);
    pickUpLocSecondary = pickUpLoc.replace(pickUpLocPrimary, '');
    pickUpLocSecondary = pickUpLocSecondary.replace(", ", '');

    dropOffLocPrimary = dropOffLoc.split(",", 1);
    dropOffLocSecondary = dropOffLoc.replace(dropOffLocPrimary, '');
    dropOffLocSecondary = dropOffLocSecondary.replace(", ", '');

    $('#delivery-type').text(transactionType + " Delivery");
    $('#pickUpLoc-primary').text(pickUpLocPrimary);
    $('#pickUpLoc-secondary').text(pickUpLocSecondary);
    $('#dropOffLoc-primary').text(dropOffLocPrimary);
    $('#dropOffLoc-secondary').text(dropOffLocSecondary);
    $('#travel-distance').text(travelDistance.split(" km", 1) + " KM");
    $('#time-remaining').text(" " + timeRemaining);
    if(transactionType === "Food") {
        $('#order-details').html(
            "<div class=\"col-6\">" +
            "<h6 class=\"font-weight-bold float-left\">Order</h6>" +
            "</div>" +
            "<div class=\"col-6\">" +
            "<h6 class=\"float-right text-muted\">" +
            "<span id=\"order-details\">" + orderDetails +
            "</span>" +
            "</h6>" +
            "</div>" +
            "<hr/>"
        );
        $('#food-divider').removeClass('d-none');
    }
    $('#total-price').text("RM " + totalPrice.toFixed(2));
    $('#payment-info').text("(" + paidStatus + ")");
}

function getOrderLogic(orderType, orderID, riderID) {
    // START: New Order Display
    if(riderMode === 1) {

        if(!$('#order-container').hasClass('d-none')) {
            let timeLeft = 30;

            // Rule 1: Dismiss by another rider accepting the order - Every 100 milliseconds
            let checkAcceptanceStatusCountdown = setInterval(function() {
                $.ajax({
                    url: "../assets/php/ajax/rider/getOrderDetailsByID.php",
                    method: "POST",
                    cache: false,
                    data: {Order_Type: orderType, Order_ID: orderID, Order_Data: "Status"},
                    success: function (dataDetails) {
                        if(dataDetails !== "ERROR") {
                            if(dataDetails === "RIDER-FOUND") {
                                orderLogicCalled = false;
                                isOrderBeingDisplayed = false;
                                clearTimeout(checkAcceptanceStatusCountdown);
                                timeLeft = 30;
                                $('#time-left').text("00:" + timeLeft);

                                $('#order-container').addClass('d-none');

                                // Crosscheck Rider ID and check whether to give a new order or start a ride
                                $.ajax({
                                    url: "../assets/php/ajax/rider/getOrderDetailsByID.php",
                                    method: "POST",
                                    cache: false,
                                    data: {Order_Type: orderType, Order_ID: orderID, Order_Data: "Rider_ID"},
                                    success: function (dataDetails) {
                                        if(dataDetails !== "ERROR") {

                                            console.log(dataDetails);
                                            console.log(riderID);

                                            if(parseInt(dataDetails) === parseInt(riderID)) {
                                                // If this rider accepted the order, display new container

                                            } else {
                                                // If this rider didn't accept the order, show a new order
                                                getNewOrders("PARCEL");
                                            }
                                        }
                                    }
                                });
                            } else {
                                // Finding for Rider
                            }
                        } else {
                            // Error Message
                        }
                    }
                });
            }, 100);

            // Rule 2: Dismiss by Timer Countdown
            let countdown = setInterval(function() {
                if(timeLeft === 0) {

                    // Reset Value
                    orderLogicCalled = false;
                    isOrderBeingDisplayed = false;

                    clearTimeout(countdown);
                    timeLeft = 30;
                    $('#time-left').text("00:" + timeLeft);

                    $('#order-container').addClass('d-none');
                    addRiderToDenialList(orderType, orderID, riderID);
                    getNewOrders("PARCEL");
                } else {
                    if(timeLeft < 10) {
                        $('#time-left').text("00:0" + timeLeft);
                    } else {
                        $('#time-left').text("00:" + timeLeft);
                    }

                    timeLeft--;
                }
            }, 1000);

            // Rule 3: Dismiss by choosing to cancel order
            $('#reject-order').click(function() {
                // Reset countdown and other values for next offer
                orderLogicCalled = false;
                isOrderBeingDisplayed = false;
                clearTimeout(countdown);
                timeLeft = 30;
                $('#time-left').text("00:" + timeLeft);

                $('#order-container').addClass('d-none');
                addRiderToDenialList(orderType, orderID, riderID);
                getNewOrders("PARCEL");
            });

            // Rule 4: Dismiss by choosing to accept order
            $('#accept-order').click(function() {
                // Reset countdown and other values
                orderLogicCalled = false;
                isOrderBeingDisplayed = false;
                clearTimeout(countdown);
                timeLeft = 30;
                $('#time-left').text("00:" + timeLeft);

                $('#order-container').addClass('d-none');
                acceptOrder(orderType, orderID, riderID);
            });
        }


    }
}

// Step 2: Get New Orders
function getNewOrders(orderType) {
    if(riderMode === Mode.ACTIVE) {

        // Set Rider ID
        $.ajax({
            url: "../assets/php/ajax/rider/getRiderData.php",
            method: "POST",
            cache: false,
            data: {User_Email: window.localStorage.getItem("User_Email"), User_Info: "ID"},
            success: function (data) {
                riderID = parseInt(data);
            }
        });

        let realTimeCheck = setInterval(function() {

            // Stop interval when Mode is Inactive
            if(riderMode === Mode.INACTIVE) {
                clearTimeout(realTimeCheck);
            }

            // START: Get New Orders
            $.ajax({
                url: "../assets/php/ajax/rider/getNewOrders.php",
                method: "POST",
                cache: false,
                data: {Rider_ID: riderID},
                success: function (data) {
                    if(data !== "ERROR") {
                        // Put New Orders
                        currentOrder = data;

                        $.ajax({
                            url: "../assets/php/ajax/rider/getOrderDetails.php",
                            method: "POST",
                            cache: false,
                            data: {Order_Type: orderType, Order_ID: currentOrder},
                            success: function (dataDetails) {
                                if(dataDetails !== "ERROR") {

                                    dataDetails = JSON.parse(dataDetails);

                                    // Get Order Details
                                    $('#order-container').removeClass('d-none');

                                    if(!orderLogicCalled) {
                                        getOrderLogic(orderType, currentOrder, riderID);
                                    }
                                    orderLogicCalled = true;
                                    isOrderBeingDisplayed = true;

                                    clearTimeout(realTimeCheck);

                                    createNewOrder(
                                        orderType,
                                        dataDetails[1],
                                        dataDetails[2],
                                        dataDetails[10],
                                        dataDetails[11],
                                        "1 x Nasi Goreng<br/>1 x Teh O' Ais",
                                        parseFloat(dataDetails[8]),
                                        "Paid");


                                } else {
                                    // Display Error Message

                                }
                            }
                        });
                    } else {
                        // Display Error Message

                    }
                }

            });
            // END: Get New Orders

        }, 500);
    }
}

// If timer goes out or Rider cancels request, add them to denied list
function addRiderToDenialList(orderType, orderID, riderID) {
    $.ajax({
        url: "../assets/php/ajax/rider/addRiderToDeniedList.php",
        method: "POST",
        cache: false,
        data: {Order_Type: orderType, Order_ID: orderID, Rider_ID: riderID},
        success: function (data) {
            if(data === "TRUE") {
                // Error Message
            }
        }
    });
}

function acceptOrder(orderType, orderID, riderID) {
    $.ajax({
        url: "../assets/php/ajax/rider/acceptOrder.php",
        method: "POST",
        cache: false,
        data: {Order_Type: orderType, Order_ID: orderID, Rider_ID: riderID},
        success: function (data) {
            if(data !== "TRUE") {

            }
        }
    });
}

function getOrderAcceptanceStatus(orderType, orderID) {
    let response = false;

    $.ajax({
        url: "../assets/php/ajax/rider/getOrderDetailsByID.php",
        method: "POST",
        cache: false,
        data: {Order_Type: orderType, Order_ID: orderID, Order_Data: "Status"},
        success: function (dataDetails) {
            if(dataDetails !== "ERROR") {
                if(dataDetails === "RIDER-FOUND") {
                    console.log('found');
                    response = true;
                } else {
                    console.log('finding');
                    response = false;
                }
            } else {
                console.log('error');
                response = false;
            }
        }
    });

    return response;
}