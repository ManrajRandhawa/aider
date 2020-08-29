// Define Enums for Rider - Active or Inactive Mode
const Mode = Object.freeze({"INACTIVE": 0, "ACTIVE": 1, "HEADING_TO_PICKUP": 2, "HEADING_TO_DESTINATION": 3, "COMPLETED": 4});
let riderMode = Mode.INACTIVE;
let isOrderContainerDisplayed = false;
let isOrderLogicCalled = false;
let isNewOrderCalled = false;

var riderAcceptanceInterval = null;
var riderOrderCountdownInterval = null;
var realTimeCheck = null;

let timeLeft = 30;

class RiderAJAX {
    static addRiderToDenialList(orderType, orderID, riderID) {
        $.ajax({
            url: "../assets/php/ajax/rider/addRiderToDeniedList.php",
            method: "POST",
            cache: false,
            data: {Order_Type: orderType, Order_ID: orderID, Rider_ID: riderID},
            success: function (data) {
                if(data === "TRUE") {
                    // Error Message
                    console.log('WHOOPS!');
                } else {
                    console.log('Denied:');
                    console.log('Order Type:' + orderType);
                    console.log('Order ID:' + orderID);
                    console.log('Rider ID:' + riderID);
                }
            }
        });
    }

    static acceptOrder(orderType, orderID, riderID) {
        $.ajax({
            url: "../assets/php/ajax/rider/acceptOrder.php",
            method: "POST",
            cache: false,
            data: {Order_Type: orderType, Order_ID: orderID, Rider_ID: riderID},
            success: function (data) {
                if(data !== "TRUE") {
                    console.log('Accepted:');
                    console.log('Order Type:' + orderType);
                    console.log('Order ID:' + orderID);
                    console.log('Rider ID:' + riderID);
                }
            }
        });
    }
}

class RiderDataSet {
    static setRiderID(id) {
        rID = id;
    }
    static getRiderID() {
        return rID;
    }

    static setRiderMode(rMode) {
        riderMode = rMode;
    }
    static getRiderMode() {
        return riderMode;
    }

    static setOrderContainerDisplayed(value) {
        isOrderContainerDisplayed = value;
    }
    static getOrderContainerDisplayed() {
        return isOrderContainerDisplayed;
    }

    static setOrderLogicCalled(value) {
        isOrderLogicCalled = value;
    }
    static getOrderLogicCalled() {
        return isOrderLogicCalled;
    }

    static setNewOrderCalled(value) {
        isNewOrderCalled = value;
    }
    static getNewOrderCalled() {
        return isNewOrderCalled;
    }
}

class RiderLayout {
    /* [START]
        STEP 1: Show Order
     */
    static createNewOrder(type, pickUpLoc, dropOffLoc, travelDistance, timeRemaining, orderDetails, totalPrice, paidStatus) {
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
    // [END]

    static createPickUpLocationContent(pickUpLoc) {
        let pickUpLocPrimary, pickUpLocSecondary;

        pickUpLocPrimary = pickUpLoc.split(",", 1);
        pickUpLocSecondary = pickUpLoc.replace(pickUpLocPrimary, '');
        pickUpLocSecondary = pickUpLocSecondary.replace(", ", '');

        $('#riding-1-content-loc-1').text(pickUpLocPrimary);
        $('#riding-1-content-loc-2').text(pickUpLocSecondary);

        let url = "geo:0,0";
        /*
        if (device.platform.toLowerCase() === "ios") {
            url = "maps://"
        }
         */

        $('#riding-1-content-loc-btn').html("<a href='" + url + "?q=" + pickUpLoc + "'>\n" +
            "                                        <i class=\"mt-2 mr-3 fas fa-location-arrow fa-lg text-success\"></i>\n" +
            "                                    </a>");
    }

    static createDropOffLocationContent(dropOffLoc) {
        let dropOffLocPrimary, dropOffLocSecondary;

        dropOffLocPrimary = dropOffLoc.split(",", 1);
        dropOffLocSecondary = dropOffLoc.replace(dropOffLocPrimary, '');
        dropOffLocSecondary = dropOffLocSecondary.replace(", ", '');

        $('#riding-2-content-loc-1').text(dropOffLocPrimary);
        $('#riding-2-content-loc-2').text(dropOffLocSecondary);

        let url = "geo:0,0";
        /*
        if (device.platform.toLowerCase() === "ios") {
            url = "maps://"
        }
         */

        $('#riding-2-content-loc-btn').html("<a href='" + url + "?q=" + dropOffLoc + "'>\n" +
            "                                        <i class=\"mt-2 mr-3 fas fa-location-arrow fa-lg text-success\"></i>\n" +
            "                                    </a>");
    }
}

class RiderLogic {
    static getOrderLogic(orderType, orderID) {
        let riID = RiderDataSet.getRiderID();

        if(RiderDataSet.getOrderContainerDisplayed()) {
            // Rule 1: Dismiss by another rider accepting the order - Every 500 milliseconds
            if(riderAcceptanceInterval === null) {
                riderAcceptanceInterval = setInterval(function () {
                    $.ajax({
                        url: "../assets/php/ajax/rider/getOrderDetailsByID.php",
                        method: "POST",
                        cache: false,
                        data: {Order_Type: orderType, Order_ID: orderID, Order_Data: "Status"},
                        success: function (dataDetails) {
                            if (dataDetails !== "ERROR") {
                                if (dataDetails === "RIDER-FOUND") {
                                    // Crosscheck Rider ID and check whether to give a new order or start a ride
                                    $.ajax({
                                        url: "../assets/php/ajax/rider/getOrderDetailsByID.php",
                                        method: "POST",
                                        cache: false,
                                        data: {Order_Type: orderType, Order_ID: orderID, Order_Data: "Rider_ID"},
                                        success: function (data) {
                                            if(data !== "ERROR") {
                                                if(parseInt(data) === parseInt(riID)) {
                                                    // If this rider accepted the order, display new container
                                                    $('#rider-navigation-main').addClass('d-none');
                                                    $('#rider-navigation-riding-1').removeClass('d-none');
                                                } else {
                                                    // If this rider didn't accept the order, show a new order
                                                    Rider.resetValues();

                                                    if(RiderDataSet.getNewOrderCalled() === false) {
                                                        Rider.getNewOrders();
                                                    }
                                                    RiderDataSet.setNewOrderCalled(true);
                                                }
                                            }
                                        }
                                    });
                                } else {
                                    // Finding For Rider
                                }
                            } else {
                                // [ERROR] Can't retrieve Order Details By ID
                            }
                        }
                    });
                }, 500);
            } else {
                console.log('riderAcceptanceInterval variable has been defined!');
            }
            // Rule 2: Dismiss by Timer Countdown
            if(riderOrderCountdownInterval === null) {
                riderOrderCountdownInterval = setInterval(function() {
                    if(timeLeft === 0) {
                        RiderAJAX.addRiderToDenialList(orderType, orderID, riID);

                        // Reset Values
                        Rider.resetValues();

                        if(RiderDataSet.getNewOrderCalled() === false) {
                            Rider.getNewOrders();
                        }
                        RiderDataSet.setNewOrderCalled(true);
                    } else {
                        if(timeLeft < 10) {
                            $('#time-left').text("00:0" + timeLeft);
                        } else {
                            $('#time-left').text("00:" + timeLeft);
                        }

                        timeLeft--;
                    }
                }, 1000);
            } else {
                console.log('riderOrderCountdownInterval variable has been defined!');
            }
            // Rule 3: Dismiss by choosing to cancel order
            $('#reject-order').unbind().click(function() {
                RiderAJAX.addRiderToDenialList(orderType, orderID, riID);

                // Reset Values
                Rider.resetValues();

                if(RiderDataSet.getNewOrderCalled() === false) {
                    Rider.getNewOrders();
                }
                RiderDataSet.setNewOrderCalled(true);
            });
            // Rule 4: Dismiss by choosing to accept order
            $('#accept-order').unbind().click(function() {
                // Reset countdown and other values
                Rider.resetValues();
                clearInterval(realTimeCheck);

                RiderAJAX.acceptOrder(orderType, orderID, riID);
                RiderDataSet.setNewOrderCalled(false);

                RiderDataSet.setRiderMode(Mode.HEADING_TO_PICKUP);
                RiderDataSet.setOrderContainerDisplayed(false);
                RiderLogic.getOrderLogic(orderType, orderID);
            });
        } else {
            // Mode.INACTIVE implementations have been set in getNewOrders() function

            if(RiderDataSet.getRiderMode() === Mode.HEADING_TO_PICKUP) {
                // Set Rider Status -> HEADING_TO_PICKUP
                $.ajax({
                    url: "../assets/php/ajax/rider/setRiderStatus.php",
                    method: "POST",
                    cache: false,
                    data: {Status: "HEADING_TO_PICKUP", T_Type: orderType, T_ID: orderID, Email: window.localStorage.getItem("User_Email")},
                    success: function (data) {
                        if(data !== "NO-ERROR") {
                            console.log(data);
                        }
                    }
                });

                $('#rider-navigation-main').addClass('d-none');
                $('#rider-navigation-riding-1').removeClass('d-none');
                $('#rider-navigation-riding-1-content').removeClass('d-none');

                $('#btn-arrived-pickup').click(function() {
                    RiderDataSet.setRiderMode(Mode.HEADING_TO_DESTINATION);
                    RiderLogic.getOrderLogic(orderType, orderID);
                });
            }

            if(RiderDataSet.getRiderMode() === Mode.HEADING_TO_DESTINATION) {
                // Set Rider Status -> HEADING_TO_DESTINATION
                $.ajax({
                    url: "../assets/php/ajax/rider/setRiderStatus.php",
                    method: "POST",
                    cache: false,
                    data: {Status: "HEADING_TO_DESTINATION", T_Type: orderType, T_ID: orderID, Email: window.localStorage.getItem("User_Email")},
                    success: function (data) {
                        if(data !== "NO-ERROR") {
                            console.log(data);
                        }
                    }
                });

                if(!($('#rider-navigation-main').hasClass('d-none'))) {
                    $('#rider-navigation-main').addClass('d-none');
                }
                $('#rider-navigation-riding-1').addClass('d-none');
                $('#rider-navigation-riding-1-content').addClass('d-none');
                $('#rider-navigation-riding-2').removeClass('d-none');
                $('#rider-navigation-riding-2-content').removeClass('d-none');

                $('#btn-arrived-destination').click(function() {
                    RiderDataSet.setRiderMode(Mode.COMPLETED);
                    RiderLogic.getOrderLogic(orderType, orderID);
                });
            }

            if(RiderDataSet.getRiderMode() === Mode.COMPLETED) {
                // Set Rider Status -> COMPLETED
                $.ajax({
                    url: "../assets/php/ajax/rider/setRiderStatus.php",
                    method: "POST",
                    cache: false,
                    data: {Status: "COMPLETED", T_Type: orderType, T_ID: orderID, Email: window.localStorage.getItem("User_Email")},
                    success: function (data) {
                        if(data !== "NO-ERROR") {
                            console.log(data);
                        }
                    }
                });

                if(!($('#rider-navigation-main').hasClass('d-none'))) {
                    $('#rider-navigation-main').addClass('d-none');
                }
                $('#rider-navigation-riding-2').addClass('d-none');
                $('#rider-navigation-riding-2-content').addClass('d-none');
                $('#rider-navigation-riding-3').removeClass('d-none');

                $('#rider-navigation-riding-3-btn').click(function() {
                    RiderDataSet.setRiderMode(Mode.ACTIVE);

                    // Set Rider Status -> ACTIVE
                    $.ajax({
                        url: "../assets/php/ajax/rider/setRiderStatus.php",
                        method: "POST",
                        cache: false,
                        data: {Status: "ACTIVE", T_Type: "", T_ID: 0, Email: window.localStorage.getItem("User_Email")},
                        success: function (data) {
                            if(data !== "NO-ERROR") {
                                console.log(data);
                            }
                        }
                    });

                    $('#rider-navigation-riding-3').addClass('d-none');
                    $('#rider-navigation-main').removeClass('d-none');
                    $('#btn-inactive').addClass('d-none');
                    $('#btn-active').removeClass('d-none');
                    Rider.getNewOrders();
                });
            }
        }
    }

    static getOngoingOrders() {
        // Get Rider's Status
        $.ajax({
            url: "../assets/php/ajax/rider/getRiderData.php",
            method: "POST",
            cache: false,
            data: {User_Email: window.localStorage.getItem("User_Email"), User_Info: "Status"},
            success: function (data) {
                if(data === "ACTIVE" || data === "INACTIVE") {
                    Rider.getNewOrders();
                } else {
                    if(data === "HEADING_TO_PICKUP") {
                        RiderDataSet.setRiderMode(Mode.HEADING_TO_PICKUP);
                    } else if(data === "HEADING_TO_DESTINATION") {
                        RiderDataSet.setRiderMode(Mode.HEADING_TO_DESTINATION);
                    } else if(data === "COMPLETED") {
                        RiderDataSet.setRiderMode(Mode.COMPLETED);
                    }

                    console.log(RiderDataSet.getRiderMode());

                    // Get Rider's Current Transaction Type
                    $.ajax({
                        url: "../assets/php/ajax/rider/getRiderData.php",
                        method: "POST",
                        cache: false,
                        data: {User_Email: window.localStorage.getItem("User_Email"), User_Info: "Transaction_Type"},
                        success: function (dataTType) {
                            let orderType = dataTType;

                            // Get Rider's Current Transaction Details from ID
                            $.ajax({
                                url: "../assets/php/ajax/rider/getRiderData.php",
                                method: "POST",
                                cache: false,
                                data: {User_Email: window.localStorage.getItem("User_Email"), User_Info: "Transaction_ID"},
                                success: function (dataDetails) {
                                    console.log("Transaction ID: " + dataDetails);
                                    console.log(orderType);
                                    RiderLogic.getOrderLogic(orderType, dataDetails);

                                    $.ajax({
                                        url: "../assets/php/ajax/rider/getOrderDetails.php",
                                        method: "POST",
                                        cache: false,
                                        data: {Order_Type: orderType, Order_ID: dataDetails},
                                        success: function (dataOrderD) {
                                            if(dataOrderD !== "ERROR") {
                                                let dataDP = JSON.parse(dataOrderD);

                                                RiderLayout.createPickUpLocationContent(dataDP[1]);
                                                RiderLayout.createDropOffLocationContent(dataDP[2]);
                                            } else {
                                                // [ERROR] Not able to get Order Details
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
}

class Rider {
    static getNewOrders() {
        Rider.resetValues();

        $.ajax({
            url: "../assets/php/ajax/rider/getRiderData.php",
            method: "POST",
            cache: false,
            data: {User_Email: window.localStorage.getItem("User_Email"), User_Info: "ID"},
            success: function (data) {
                RiderDataSet.setRiderID(data);

                let riID = parseInt(RiderDataSet.getRiderID());

                let orderID = null;
                let orderType = null;
                let dataDetails = null;
                let dataArray = null;

                // Check for new orders in 1000ms intervals
                if(realTimeCheck === null) {
                    realTimeCheck = setInterval(function () {

                        // clearInterval for realTimeCheck if RiderMode === INACTIVE or RiderMode === 0
                        if(RiderDataSet.getRiderMode() === Mode.INACTIVE) {
                            Rider.resetValues();
                            clearInterval(realTimeCheck);
                        } else {
                            // Get New Orders from Database
                            $.ajax({
                                url: "../assets/php/ajax/rider/getNewOrders.php",
                                method: "POST",
                                cache: false,
                                data: {Rider_ID: riID},
                                success: function (data) {
                                    if(data !== "ERROR") {
                                        dataArray = JSON.parse(data);
                                        orderID = parseInt(dataArray[0]);
                                        orderType = dataArray[1];

                                        $.ajax({
                                            url: "../assets/php/ajax/rider/getOrderDetails.php",
                                            method: "POST",
                                            cache: false,
                                            data: {Order_Type: orderType, Order_ID: orderID},
                                            success: function (dataD) {
                                                if(dataD !== "ERROR") {
                                                    dataDetails = JSON.parse(dataD);

                                                    clearInterval(realTimeCheck);
                                                    realTimeCheck = null;

                                                    // Get Order Details
                                                    RiderDataSet.setOrderContainerDisplayed(true);
                                                    $('#order-container').removeClass('d-none');

                                                    if(RiderDataSet.getOrderLogicCalled() === false) {
                                                        RiderLogic.getOrderLogic(orderType, orderID, riID);
                                                    }
                                                    RiderDataSet.setOrderLogicCalled(true);

                                                    RiderLayout.createNewOrder(
                                                        orderType,
                                                        dataDetails[1],
                                                        dataDetails[2],
                                                        dataDetails[10],
                                                        dataDetails[11],
                                                        "1 x Nasi Goreng<br/>1 x Teh O' Ais",
                                                        parseFloat(dataDetails[8]),
                                                        "Paid");

                                                    RiderLayout.createPickUpLocationContent(dataDetails[1]);
                                                    RiderLayout.createDropOffLocationContent(dataDetails[2]);
                                                } else {
                                                    // [ERROR] Not able to get Order Details
                                                }
                                            }
                                        });
                                    } else {
                                        // [ERROR] Not able to get New Orders
                                    }
                                }
                            });
                        }
                    }, 1000);
                }
            }
        });
    }

    static saveRiderLocation() {
        return navigator.geolocation.watchPosition(function(position) {
            // Update Rider's Coordinates
            $.ajax({
                url: "../assets/php/ajax/rider/setRiderLocation.php",
                method: "POST",
                cache: false,
                data: {Loc_LNG: position.coords.longitude, Loc_LAT: position.coords.latitude, Email: window.localStorage.getItem("User_Email")},
                success: function (data) {
                    if(data !== "NO-ERROR") {
                        console.log(data);
                    }
                }
            });
        });
    }

    static removeRiderLocation(watchID) {
        // Clear Geolocation Watch
        navigator.geolocation.clearWatch(watchID);

        // Update Rider's Coordinates
        $.ajax({
            url: "../assets/php/ajax/rider/setRiderLocation.php",
            method: "POST",
            cache: false,
            data: {Loc_LNG: "", Loc_LAT: "", Email: window.localStorage.getItem("User_Email")},
            success: function (data) {
                if(data !== "NO-ERROR") {
                    console.log(data);
                }
            }
        });
    }

    static resetValues() {
        if(RiderDataSet.getOrderContainerDisplayed() === true) {
            RiderDataSet.setOrderContainerDisplayed(false);
        }
        if(RiderDataSet.getOrderLogicCalled() === true) {
            RiderDataSet.setOrderLogicCalled(false);
        }
        if(RiderDataSet.getNewOrderCalled() === true) {
            RiderDataSet.setNewOrderCalled(false);
        }
        if(realTimeCheck !== null) {
            clearInterval(realTimeCheck);
            realTimeCheck = null;
            console.log('Cleared Real Time Check Interval');
        }
        if(riderOrderCountdownInterval !== null) {
            clearInterval(riderOrderCountdownInterval);
            riderOrderCountdownInterval = null;
            console.log('Cleared Order Countdown Interval');
        }
        if(riderAcceptanceInterval !== null) {
            clearInterval(riderAcceptanceInterval);
            riderAcceptanceInterval = null;
            console.log('Cleared Acceptance Countdown Interval');
        }


        timeLeft = 30;
        $('#time-left').text("00:" + timeLeft);
        $('#order-container').addClass('d-none');
    }
}