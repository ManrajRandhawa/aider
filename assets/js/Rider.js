// Define Enums for Rider - Active or Inactive Mode
const Mode = Object.freeze({"INACTIVE": 0, "ACTIVE": 1});
let riderMode = Mode.INACTIVE;

let orderLogicCalled = false;

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

function getHomeJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../riders/index.php";
    } else {
        // START: Rider - Active and Inactive Mode
        $('#btn-inactive').click(function() {
            $('#btn-inactive').addClass('d-none');
            $('#btn-active').removeClass('d-none');
            riderMode = Mode.ACTIVE;

            $('#order-container').removeClass('d-none');

            if(!orderLogicCalled) {
                getOrderLogic(riderMode);
            }
            orderLogicCalled = true;

        });

        $('#btn-active').click(function () {
            $('#btn-active').addClass('d-none');
            $('#btn-inactive').removeClass('d-none');
            riderMode = Mode.INACTIVE;

            $('#order-container').addClass('d-none');
        });
        // END: Rider - Active and Inactive Mode





        // START: New Order Display


        createNewOrder(
            "PARCEL",
            "Domino's Pizza, Jalan Kajang, 41050 Klang, Selangor.",
            "",
            8,
            "9:41",
            "1 x Nasi Goreng<br/>1 x Teh O' Ais",
            15.30,
            "Paid");

    }
}

function createNewOrder(type, pickUpLoc, dropOffLoc, travelDistance, timeRemaining, orderDetails, totalPrice, paidStatus) {
    let transactionType, pickUpLocPrimary, pickUpLocSecondary;

    if(type === "PARCEL") {
        transactionType = "Parcel";
    } else {
        transactionType = "Food";
    }

    pickUpLocPrimary = pickUpLoc.split(",", 1);
    pickUpLocSecondary = pickUpLoc.replace(pickUpLocPrimary, '');
    pickUpLocSecondary = pickUpLocSecondary.replace(", ", '');

    $('#delivery-type').text(transactionType + " Delivery");
    $('#pickUpLoc-primary').text(pickUpLocPrimary);
    $('#pickUpLoc-secondary').text(pickUpLocSecondary);
    $('#travel-distance').text(travelDistance + " KM");
    $('#time-remaining').text(" " + timeRemaining);
    $('#order-details').html(orderDetails);
    $('#total-price').text("RM " + totalPrice.toFixed(2));
    $('#payment-info').text("(" + paidStatus + ")");
}

function getOrderLogic(mode) {
    // START: New Order Display
    if(riderMode === 1) {

        // Rule 1: Dismiss by Timer Countdown
        if(!$('#order-container').hasClass('d-none')) {
            let timeLeft = 30;
            let countdown = setInterval(function() {
                if(timeLeft === 0) {

                    // Reset Value
                    timeLeft = 30;
                    orderLogicCalled = false;

                    clearTimeout(countdown);
                    $('#order-container').addClass('d-none');
                } else {
                    if(timeLeft < 10) {
                        $('#time-left').text("00:0" + timeLeft);
                    } else {
                        $('#time-left').text("00:" + timeLeft);
                    }

                    timeLeft--;
                }
            }, 1000);
        }

        // Rule 2: Dismiss by another Rider accepting the order

    }
}