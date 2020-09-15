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
                        if(data === "NO-ERROR") {
                            title = "Finding a Rider";
                            message = "Your request is now being sent to our riders. We'll find a rider for you soon enough. Hang tight!";
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

        // START: Logout
        $('#logout').click(function() {
            window.localStorage.clear();
            window.location.href = "index.php";
        });
        // END: Logout
    }
}