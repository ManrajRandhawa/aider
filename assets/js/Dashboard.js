function getDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../member/index.php";
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
                data: {Password: pswd, Confirm_Password: cpswd, User_Email: user_email, },
                success: function (data) {
                    if(data === 0) {
                        $('.bd-example-modal-lg').modal('toggle');
                    } else {

                    }

                }
            });
        });
        //END: Change Password when button is clicked
    }
}



function getParcelDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../member/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");
        //START: Click Deliver Now Button
        $('#btn-deliver').click(function() {
            let priceText, price, finalPrice;
            priceText = $('#text-price').text();
            price = priceText.replace('RM ', '');
            finalPrice = price.split(" ").slice(0, price.indexOf(',')).slice(0, price.indexOf(','));
            $.ajax({
                url: "../assets/php/ajax/bookings/parcel/bookParcelDelivery.php",
                method: "POST",
                cache: false,
                data: {Email: user_email, Pickup_Location: $('#pickUpSearch').val(), Dropoff_Location: $('#dropOffSearch').val(), Pickup_Details_Name: $('#pickup-details-name').val(), Pickup_Details_PhoneNum: $('#pickup-details-phonenum').val(), Dropoff_Details_Name: $('#dropoff-details-name').val(), Dropoff_Details_PhoneNum: $('#dropoff-details-phonenum').val(), Price: finalPrice},
                success: function(data){
                    let title, message;
                    if(data === "NO-ERROR") {
                        title = "Finding a Rider";
                        message = "Your delivery request is now being sent to our riders. We'll find a rider for you soon enough. Hang tight!";
                        alert('true: price = ' + finalPrice);
                    } else {
                        title = "Oops! Something went wrong.";
                        message = data;
                        alert(data);
                    }
                    $.ajax({
                        url: "../assets/php/ajax/bookings/parcel/bookParcelDelivery.php",
                        method: "POST",
                        cache: false,
                        data: {Title: title, Message: message},
                        success: function(dataToast){
                            $('.toast-container').html(dataToast);
                        }
                    });
                }
            });
        });
        //END: Click Deliver Now Button
    }
}