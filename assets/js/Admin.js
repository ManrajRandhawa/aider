function getWaitingListDashboard() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../admins/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        // Display Waiting List
        $.ajax({
            url: "../assets/php/ajax/admin/getWaitingList.php",
            method: "POST",
            cache: false,
            success: function(data){
                $('#waiting-list-container').html(data);
            }
        });

        // Approve Rider from Waiting List
        $('#approve-btn').click(function() {
            let emailApprove = $('.btn-approve').attr('id');

            // Approve Rider & Display Toast Message
            $.ajax({
                url: "../assets/php/ajax/admin/approveRider.php",
                method: "POST",
                cache: false,
                data: {User_Email: emailApprove, V_Model: $('#v_model').val(), V_Plate_Num: $('#v_plate_num').val(), Rider_Type: $('#rider_type').val()},
                success: function(data) {
                    $('#riderApproval').modal('toggle');

                    // Display Toast Message
                    $.ajax({
                        url: "../assets/php/ajax/ui/sendToastMessage.php",
                        method: "POST",
                        cache: false,
                        data: {Title: "Approval Status for " + emailApprove, Message: data},
                        success: function(dataToast){
                            $('.toast-container').html(dataToast);
                            $('.toast').toast('show');

                            setTimeout(function() {
                                $('.toast-container').html("");
                            }, 5000);
                        }
                    });

                    // Update Waiting List
                    $.ajax({
                        url: "../assets/php/ajax/admin/getWaitingList.php",
                        method: "POST",
                        cache: false,
                        success: function(dataWL){
                            $('#waiting-list-container').html(dataWL);
                        }
                    });
                }
            });
        });
        // Approve Rider from Waiting List
    }
}

function getTeamsDashboard() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../admins/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        refreshSelectList();

        // Display Teams
        $.ajax({
            url: "../assets/php/ajax/admin/getTeamsList.php",
            method: "POST",
            cache: false,
            success: function(dataWL){
                $('#teams-container').html(dataWL);
            }
        });

        // Add Team
        $('#add-team-btn').click(function() {
            // Add Team & Display Toast Message

            let member_one_id = $('#t_member_one').val();
            let member_two_id = $('#t_member_two').val();

            let teamMembers = member_one_id + "," + member_two_id;

            $.ajax({
                url: "../assets/php/ajax/admin/createTeam.php",
                method: "POST",
                cache: false,
                data: {Team_Name: $('#t_name').val(), Team_Members: teamMembers},
                success: function(data) {
                    if(data === "OK") {
                        $('#addTeam').modal('toggle');

                        // Display Toast Message
                        $.ajax({
                            url: "../assets/php/ajax/ui/sendToastMessage.php",
                            method: "POST",
                            cache: false,
                            data: {Title: "Success!", Message: "The team has been created successfully."},
                            success: function(dataToast){
                                $('.toast-container').html(dataToast);
                                $('.toast').toast('show');

                                setTimeout(function() {
                                    $('.toast-container').html("");
                                }, 5000);
                            }
                        });

                    } else {
                        // Display Toast Message
                        $.ajax({
                            url: "../assets/php/ajax/ui/sendToastMessage.php",
                            method: "POST",
                            cache: false,
                            data: {Title: "Oops!", Message: "There was an issue while creating the team."},
                            success: function(dataToast){
                                $('.toast-container').html(dataToast);
                                $('.toast').toast('show');

                                setTimeout(function() {
                                    $('.toast-container').html("");
                                }, 5000);
                            }
                        });
                    }

                    // Update Teams
                    $.ajax({
                        url: "../assets/php/ajax/admin/getTeamsList.php",
                        method: "POST",
                        cache: false,
                        success: function(dataWL){
                            $('#teams-container').html(dataWL);
                        }
                    });

                    // Refresh Values
                    $('#t_name').val("");

                    refreshSelectList();

                    $('#t_member_one').val('default').selectpicker('refresh');
                    $('#t_member_two').val('default').selectpicker('refresh');
                }
            });
        });
        // Add Team

    }
}

function editTeam(team_id) {
    $('')
}

function refreshSelectList() {
    // Display Select Items
    $.ajax({
        url: "../assets/php/ajax/admin/getRidersSelectList.php",
        method: "POST",
        cache: false,
        data: {Rider_Type: "RIDER"},
        success: function(dataWL){
            $('#select-riders-list-1').html(dataWL);
            $('#select-riders-list-2').html(dataWL);

            $('#t_member_one').selectpicker('refresh');
            $('#t_member_two').selectpicker('refresh');
        }
    });

    $.ajax({
        url: "../assets/php/ajax/admin/getRidersSelectList.php",
        method: "POST",
        cache: false,
        data: {Rider_Type: "DRIVER"},
        success: function(dataWL){
            $('#select-drivers-list-1').html(dataWL);
            $('#select-drivers-list-2').html(dataWL);

            $('#t_member_one').selectpicker('refresh');
            $('#t_member_two').selectpicker('refresh');
        }
    });
    // Display Select Items
}

function approveButtonClick() {
    let emailApprove = $('.btn-approve').attr('id');
    $('#approve_email').text(emailApprove);

    $('#riderApproval').modal();
}

function denyButtonClick() {
    let emailApprove = $('.btn-deny').attr('id');

    // Approve Rider & Display Toast Message
    $.ajax({
        url: "../assets/php/ajax/admin/denyRider.php",
        method: "POST",
        cache: false,
        data: {User_Email: emailApprove},
        success: function(data) {
            // Display Toast Message
            $.ajax({
                url: "../assets/php/ajax/ui/sendToastMessage.php",
                method: "POST",
                cache: false,
                data: {Title: "Approval Status for " + emailApprove, Message: data},
                success: function(dataToast){
                    $('.toast-container').html(dataToast);
                    $('.toast').toast('show');

                    setTimeout(function() {
                        $('.toast-container').html("");
                    }, 5000);
                }
            });

            // Update Waiting List
            $.ajax({
                url: "../assets/php/ajax/admin/getWaitingList.php",
                method: "POST",
                cache: false,
                success: function(dataWL){
                    $('#waiting-list-container').html(dataWL);
                }
            });
        }
    });
}

function addTeam() {
    $('#addTeam').modal();
}

function getSettingsDashboard() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../admins/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        // START: Display Pricing Settings
        $.ajax({
            url: "../assets/php/ajax/admin/getSettingsInformation.php",
            method: "POST",
            cache: false,
            data: {Settings_Info: "Base_Fare"},
            success: function(data){
                $('#base-fare-price').val(data);
            }
        });

        $.ajax({
            url: "../assets/php/ajax/admin/getSettingsInformation.php",
            method: "POST",
            cache: false,
            data: {Settings_Info: "Price_Per_KM"},
            success: function(data){
                $('#per-km-price').val(data);
            }
        });
        // END: Display Pricing Settings

        // START: Display Rider Details
        $.ajax({
            url: "../assets/php/ajax/admin/getSettingsInformation.php",
            method: "POST",
            cache: false,
            data: {Settings_Info: "Maximum_Radius_KM"},
            success: function(data){
                $('#max-rad-km').val(data);
            }
        });
        // END: Display Rider Details

        // START: [Settings] Pricing - Save Button
        $('#save-pricing').click(function() {

            let error = false;

            // STEP 1: Update Base Fare
            $.ajax({
                url: "../assets/php/ajax/admin/updateSettingsInformation.php",
                method: "POST",
                cache: false,
                data: {Settings_Info: "Base_Fare", Settings_Value: $('#base-fare-price').val(), isNumber: true},
                success: function(data){
                    if(data === "YES") {
                        error = true;
                    }
                }
            });

            // STEP 2: Update Price per KM
            $.ajax({
                url: "../assets/php/ajax/admin/updateSettingsInformation.php",
                method: "POST",
                cache: false,
                data: {Settings_Info: "Price_Per_KM", Settings_Value: $('#per-km-price').val(), isNumber: true},
                success: function(data){
                    if(data === "YES") {
                        error = true;
                    }
                }
            });

            // STEP 3: Display Toast Message
            let pricingToastTitle, pricingToastMessage;
            if(error) {
                pricingToastTitle = "An error occurred!";
                pricingToastMessage = "There was an issue while trying to update the pricing settings.";
            } else {
                pricingToastTitle = "Settings Updated!";
                pricingToastMessage = "The pricing settings has been updated.";
            }

            // Display Toast Message
            $.ajax({
                url: "../assets/php/ajax/ui/sendToastMessage.php",
                method: "POST",
                cache: false,
                data: {Title: pricingToastTitle, Message: pricingToastMessage},
                success: function(dataToast){
                    $('.toast-container').html(dataToast);
                    $('.toast').toast('show');

                    setTimeout(function() {
                        $('.toast-container').html("");
                    }, 5000);
                }
            });
        });
        // END: [Settings] Pricing - Save Button

        // START: [Settings] Rider - Save Button
        $('#save-rider').click(function() {

            let error = false;

            // STEP 1: Update Base Fare
            $.ajax({
                url: "../assets/php/ajax/admin/updateSettingsInformation.php",
                method: "POST",
                cache: false,
                data: {Settings_Info: "Maximum_Radius_KM", Settings_Value: $('#max-rad-km').val(), isNumber: true},
                success: function(data){
                    if(data === "YES") {
                        error = true;
                    }
                }
            });

            // STEP 2: Display Toast Message
            let pricingToastTitle, pricingToastMessage;
            if(error) {
                pricingToastTitle = "An error occurred!";
                pricingToastMessage = "There was an issue while trying to update the pricing settings.";
            } else {
                pricingToastTitle = "Settings Updated!";
                pricingToastMessage = "The rider settings has been updated.";
            }

            // Display Toast Message
            $.ajax({
                url: "../assets/php/ajax/ui/sendToastMessage.php",
                method: "POST",
                cache: false,
                data: {Title: pricingToastTitle, Message: pricingToastMessage},
                success: function(dataToast){
                    $('.toast-container').html(dataToast);
                    $('.toast').toast('show');

                    setTimeout(function() {
                        $('.toast-container').html("");
                    }, 5000);
                }
            });
        });
        // END: [Settings] Rider - Save Button
    }
}