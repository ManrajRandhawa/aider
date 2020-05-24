function getDashboardJS() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../member/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        // START: Set Name and Credit
        $.ajax({
            url: "../assets/php/ajax/user/getUserData.php",
            method: "POST",
            data: {User_Email: user_email, User_Info: 'Name'},
            success: function(data) {
                $('#user_name').text(data);
            }
        });

        $.ajax({
            url: "../assets/php/ajax/user/getUserData.php",
            method: "POST",
            data: {User_Email: user_email, User_Info: 'Credit'},
            success: function(data) {
                $('#user_credit').text("RM " + data);
            }
        });
        //END: Set Name and Credit

        // START: Get First Time Login and show Modal
        $.ajax({
            url: "../assets/php/ajax/user/getUserData.php",
            method: "POST",
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
            let pswd = $('#pass').text();
            let cpswd = $('#confirm-pass').text();

            $.ajax({
                url: "../assets/php/ajax/user/updateFirstTimePassword.php",
                method: "POST",
                data: {User_Email: user_email, Password: pswd, Confirm_Password: cpswd},
                success: function (data) {
                    if(data === "YES") {
                        alert('Your password has been updated.');
                    } else {
                        alert('There was an issue while trying to update your password.');
                    }
                    $('.bd-example-modal-lg').modal('toggle');
                }
            });
        });

        //END: Change Password when button is clicked
    }
}

function getParcelDashboardJS() {

}