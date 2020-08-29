// Define Enums for Rider - Active or Inactive Mode
// const Mode = Object.freeze({"INACTIVE": 0, "ACTIVE": 1});
// let riderMode = Mode.INACTIVE;

let rID;
let watchID;

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
        RiderLogic.getOngoingOrders();

        // START: Rider - Active and Inactive Mode
        $('#btn-inactive').click(function() {
            $('#btn-inactive').addClass('d-none');
            $('#btn-active').removeClass('d-none');
            riderMode = Mode.ACTIVE;

            RiderDataSet.setRiderMode(1);

            // Set Rider Status -> ACTIVE
            $.ajax({
                url: "../assets/php/ajax/rider/setRiderStatus.php",
                method: "POST",
                cache: false,
                data: {Status: "ACTIVE", T_Type: "", T_ID: 0, Email: window.localStorage.getItem("User_Email")},
                success: function (data) {
                    if(data !== "NO-ERROR") {
                        console.log(data);
                    } else {
                        console.log('Rider Status Data has been updated.');
                    }
                }
            });

            watchID = Rider.saveRiderLocation();

            Rider.getNewOrders();
            RiderDataSet.setNewOrderCalled(true);
        });

        $('#btn-active').click(function () {
            $('#btn-active').addClass('d-none');
            $('#btn-inactive').removeClass('d-none');
            riderMode = Mode.INACTIVE;
            RiderDataSet.setRiderMode(0);

            // Set Rider Status -> INACTIVE
            $.ajax({
                url: "../assets/php/ajax/rider/setRiderStatus.php",
                method: "POST",
                cache: false,
                data: {Status: "INACTIVE", T_Type: "", T_ID: 0, Email: window.localStorage.getItem("User_Email")},
                success: function (data) {
                    if(data !== "NO-ERROR") {
                        console.log(data);
                    } else {
                        console.log('Rider Status Data has been updated.');
                    }
                }
            });

            Rider.removeRiderLocation(watchID);

            $('#order-container').addClass('d-none');
        });
        // END: Rider - Active and Inactive Mode
    }
}