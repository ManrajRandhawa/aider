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
        let user_email = window.localStorage.getItem("User_Email");

        watchID = Rider.saveRiderLocation();

        RiderLogic.getOngoingOrders();
        RiderLogic.getTeamOngoingOrders();

        // START: Rider - Active and Inactive Mode
        $('#btn-inactive').click(function() {
            // Check if user is assigned to a Team, if not add 'disabled' function to 'Aider Driver' button
            $.ajax({
                url: '../assets/php/ajax/rider/getRiderData.php',
                method: 'POST',
                cache: false,
                data: {User_Email: user_email, User_Info: "Team_ID"},
                success: function(teamID) {
                    if(parseInt(teamID) === 0) {
                        $('#btn-driver').prop('disabled', true);
                    }

                    // Show Active Modes List
                    $('#rider-active-modes-list').removeClass('d-none');
                }
            });
        });

        // Active Modes Selection: Others
        $('#btn-others').click(function() {
            $('#rider-active-modes-list').addClass('d-none');
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

            Rider.getNewOrders();
            RiderDataSet.setNewOrderCalled(true);
        });

        // Active Modes Selection: Aider Driver
        $('#btn-driver').click(function() {
            // Get Team Name and insert in Navigation
            $.ajax({
                url: '../assets/php/ajax/rider/getRiderData.php',
                method: 'POST',
                cache: false,
                data: {User_Email: user_email, User_Info: "Team_ID"},
                success: function(teamID) {
                    $.ajax({
                        url: '../assets/php/ajax/rider/getTeamDetails.php',
                        method: 'POST',
                        cache: false,
                        data: {Team_ID: teamID, Team_Data: "Team_Name"},
                        success: function(teamName) {
                            $('#team_name_nav').html(teamName);

                            $('#rider-active-modes-list').addClass('d-none');
                            $('#btn-inactive').addClass('d-none');
                            $('#btn-active-team').removeClass('d-none');

                            riderMode = Mode.ACTIVE;

                            RiderDataSet.setRiderMode(1);

                            let teamMemberOne = 0, teamMemberTwo = 0;

                            $.ajax({
                                url: "../assets/php/ajax/rider/getTeamDetails.php",
                                method: "POST",
                                cache: false,
                                data: {Team_ID: teamID, Team_Data: "Team_Members"},
                                success: function (dataMembers) {
                                    let members = dataMembers.split(',');
                                    teamMemberOne = members[0];
                                    teamMemberTwo = members[1];

                                    // Set Rider 1 Status -> ACTIVE
                                    $.ajax({
                                        url: "../assets/php/ajax/rider/setRiderStatusByID.php",
                                        method: "POST",
                                        cache: false,
                                        data: {Status: "ACTIVE", T_Type: "", T_ID: 0, ID: teamMemberOne},
                                        success: function (data) {
                                            if(data !== "NO-ERROR") {
                                                console.log(data);
                                            } else {
                                                // Set Rider 2 Status -> ACTIVE
                                                $.ajax({
                                                    url: "../assets/php/ajax/rider/setRiderStatusByID.php",
                                                    method: "POST",
                                                    cache: false,
                                                    data: {Status: "ACTIVE", T_Type: "", T_ID: 0, ID: teamMemberTwo},
                                                    success: function (dataTwo) {
                                                        if(dataTwo !== "NO-ERROR") {
                                                            console.log(dataTwo);
                                                        } else {
                                                            $.ajax({
                                                                url: "../assets/php/ajax/rider/setTeamStatus.php",
                                                                method: "POST",
                                                                cache: false,
                                                                data: {ID: teamID, Status: "ACTIVE"},
                                                                success: function (dataTwo) {
                                                                    if(dataTwo !== "NO-ERROR") {
                                                                        console.log(dataTwo);
                                                                    } else {
                                                                        console.log('Status Data has been updated.');
                                                                    }
                                                                }
                                                            });
                                                        }
                                                    }
                                                });
                                            }
                                        }
                                    });
                                }
                            });

                            Rider.getNewTeamOrders();
                            RiderDataSet.setNewOrderCalled(true);
                        }
                    });
                }
            });
        });

        // 'X' button to close Active Modes Selection
        $('#close-active-modes-selection').click(function() {
            $('#rider-active-modes-list').addClass('d-none');
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

        $('#btn-active-team').click(function () {
            $('#btn-active-team').addClass('d-none');
            $('#btn-inactive').removeClass('d-none');
            riderMode = Mode.INACTIVE;
            RiderDataSet.setRiderMode(0);

            $.ajax({
                url: '../assets/php/ajax/rider/getRiderData.php',
                method: 'POST',
                cache: false,
                data: {User_Email: user_email, User_Info: "Team_ID"},
                success: function (teamID) {
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
                                $.ajax({
                                    url: "../assets/php/ajax/rider/setTeamStatus.php",
                                    method: "POST",
                                    cache: false,
                                    data: {ID: teamID, Status: "INACTIVE"},
                                    success: function (dataTwo) {
                                        if(dataTwo !== "NO-ERROR") {
                                            console.log(dataTwo);
                                        } else {
                                            console.log('Status Data has been updated.');
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });



            Rider.removeRiderLocation(watchID);

            $('#order-container').addClass('d-none');
        });
        // END: Rider - Active and Inactive Mode

        // Completion Spinner
        $('.circle-loader').toggleClass('load-complete');
        $('.checkmark').toggle();
    }
}

function getEarningsDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../members/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        // START: Get Wallet Balance
        $.ajax({
            url: '../assets/php/ajax/rider/getRiderData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Wallet_Balance"},
            success: function(data) {
                $('#wallet-balance').html(data);
            }
        });
        // END: Get Wallet Balance

        // START: Get Driver Trips
        $.ajax({
            url: '../assets/php/ajax/rider/getRecentTrips.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, Trip_Type: "DRIVER"},
            success: function(data) {
                $('#driver-trips-container').html(data);
            }
        });
        // END: Get Driver Trips

        // START: Get Other Trips
        $.ajax({
            url: '../assets/php/ajax/rider/getRecentTrips.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, Trip_Type: "OTHERS"},
            success: function(data) {
                $('#other-trips-container').html(data);
            }
        });
        // END: Get Other Trips

        // START: Max Value of Amount
        $('#cashout-amt').on('input', function() {
            $.ajax({
                url: '../assets/php/ajax/rider/getRiderData.php',
                method: 'POST',
                cache: false,
                data: {User_Email: user_email, User_Info: "Wallet_Balance"},
                success: function(data) {
                    if(parseFloat($('#cashout-amt').val()) > parseFloat(data)) {
                        $('#cashout-amt').val(data);
                    }
                }
            });

        });

        $('#top-up-wallet').click(function() {
            $.ajax({
                url: '../assets/php/ajax/rider/getRiderData.php',
                method: 'POST',
                cache: false,
                data: {User_Email: user_email, User_Info: "Wallet_Balance"},
                success: function(data) {
                    $('#cashout-amt').val(data);
                }
            });


            $('#cashout-modal').modal();
        });
        // START: Max Value of Amount

        // START: Cash Out Button
        $('#cashout-btn').click(function() {

            if($('#bank').val().length < 3 || $('#bank-acc').val().length < 7 || $('#cashout-amt').val().length === 0) {
                // Display Toast Message
                $.ajax({
                    url: "../assets/php/ajax/ui/sendToastMessage.php",
                    method: "POST",
                    cache: false,
                    data: {Title: "Cash Out", Message: "Invalid entry. Please try again!"},
                    success: function(dataToast){
                        $('.toast-container-modal').html(dataToast);
                        $('.toast').toast('show');

                        setTimeout(function() {
                            $('.toast-container-modal').html("");
                        }, 5000);

                        clearTimeout(this);
                    }
                });
            } else {
                $.ajax({
                    url: '../assets/php/ajax/rider/updateRiderData.php',
                    method: 'POST',
                    cache: false,
                    data: {User_Email: user_email, Key: "Bank", Value: $('#bank').val()},
                    success: function(dataBank) {
                        if(dataBank === "FALSE") {
                            $.ajax({
                                url: '../assets/php/ajax/rider/updateRiderData.php',
                                method: 'POST',
                                cache: false,
                                data: {User_Email: user_email, Key: "Account_Number", Value: parseInt($('#bank-acc').val())},
                                success: function(dataAcc) {
                                    if(dataAcc === "FALSE") {
                                        $.ajax({
                                            url: '../assets/php/ajax/rider/updateRiderData.php',
                                            method: 'POST',
                                            cache: false,
                                            data: {User_Email: user_email, Key: "Withdraw_Amount", Value: parseFloat($('#cashout-amt').val())},
                                            success: function(dataAmt) {
                                                if(dataAmt === "FALSE") {
                                                    $('#cashout-modal').modal('toggle');

                                                    setTimeout(function() {
                                                        // Display Toast Message
                                                        $.ajax({
                                                            url: "../assets/php/ajax/ui/sendToastMessage.php",
                                                            method: "POST",
                                                            cache: false,
                                                            data: {Title: "Cash Out", Message: "Your request is in process. We will contact you soon!"},
                                                            success: function(dataToast){
                                                                $('.toast-container').html(dataToast);
                                                                $('.toast').toast('show');

                                                                setTimeout(function() {
                                                                    $('.toast-container').html("");
                                                                }, 5000);

                                                                clearTimeout(this);
                                                            }
                                                        });
                                                    }, 300);
                                                }
                                            }
                                        });
                                    }
                                }
                            });

                        }

                    }
                });
            }

        });
        // END: Cash Out Button
    }
}

function getAccountDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../members/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        $.ajax({
            url: '../assets/php/ajax/rider/getRiderData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Name"},
            success: function(data) {
                $('#user-name').html(data);
            }
        });

        // START: Change Email
        $('#chg-email-btn').click(function() {
            let email = $('#chg-email').val();

            $.ajax({
                url: "../assets/php/ajax/rider/updateRiderData.php",
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
                url: "../assets/php/ajax/rider/updateFirstTimePassword.php",
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
                url: "../assets/php/ajax/rider/updateRiderData.php",
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

        // START: Logout
        $('#logout').click(function() {
            window.localStorage.clear();
            window.location.href = "index.php";
        });
        // END: Logout
    }
}