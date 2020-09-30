/* START: Dashboard */
function getAdminHomeDashboard() {
    if(window.localStorage.getItem("User_Email") === null) {
        window.location.href = "../admins/index.php";
    } else {
        let user_email = window.localStorage.getItem("User_Email");

        $.ajax({
            url: '../assets/php/ajax/admin/getAdminData.php',
            method: 'POST',
            cache: false,
            data: {User_Email: user_email, User_Info: "Name"},
            success: function(data) {
                $('#admin-home-hello-name').html(data.split(" ", 1));
            }
        });
    }
}

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

        // Create Team
        $('#add-team-btn-confirm').click(function() {
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
        // Create Team

    }
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

        // START: Display Aider Driver Module Settings
        $.ajax({
            url: "../assets/php/ajax/admin/getSettingsInformation.php",
            method: "POST",
            cache: false,
            data: {Settings_Info: "Aider_Driver_Primary_Cut"},
            success: function(data){
                $('#primary-driver-cut-per').val(data);
            }
        });

        $.ajax({
            url: "../assets/php/ajax/admin/getSettingsInformation.php",
            method: "POST",
            cache: false,
            data: {Settings_Info: "Aider_Driver_Secondary_Cut"},
            success: function(data){
                $('#secondary-driver-cut-per').val(data);
            }
        });
        // END: Display Aider Driver Module Settings

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

        // START: [Settings] Aider Driver - Save Button
        $('#save-aider-driver').click(function() {
            let error = false;

            // STEP 1: Update Primary Percentage
            $.ajax({
                url: "../assets/php/ajax/admin/updateSettingsInformation.php",
                method: "POST",
                cache: false,
                data: {Settings_Info: "Aider_Driver_Primary_Cut", Settings_Value: $('#primary-driver-cut-per').val(), isNumber: true},
                success: function(data){
                    if(data === "YES") {
                        error = true;
                    }
                }
            });

            // STEP 2: Update Secondary Percentage
            $.ajax({
                url: "../assets/php/ajax/admin/updateSettingsInformation.php",
                method: "POST",
                cache: false,
                data: {Settings_Info: "Aider_Driver_Secondary_Cut", Settings_Value: $('#secondary-driver-cut-per').val(), isNumber: true},
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
                pricingToastMessage = "There was an issue while trying to update the percentage settings.";
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
        // END: [Settings] Aider Driver - Save Button
    }
}
/* END: Dashboard */

/* START: Individual Functions */
function addTeam() {
    $('#addTeam').modal();
}

function editTeam(team_id) {
    try {
        $.ajax({
            url: "../assets/php/ajax/admin/getTeamEditingDetails.php",
            method: "POST",
            cache: false,
            data: {Team_ID: team_id},
            success: function (data) {
                if (data !== "ERROR") {
                    let dataArray = JSON.parse(data);

                    $('#edit_team_name').html(dataArray[0]);
                    $('#edit_t_name').val(dataArray[0]);

                    $.ajax({
                        url: "../assets/php/ajax/admin/getEditedRidersSelectList.php",
                        method: "POST",
                        cache: false,
                        data: {Rider_ID: parseInt(dataArray[1])},
                        success: function (dataSelectList) {
                            if(dataSelectList !== "ERROR") {
                                let dataSelectArray = JSON.parse(dataSelectList);
                                $('#edit_select-riders-list-1').html(dataSelectArray[0]);
                                $('#edit_select-drivers-list-1').html(dataSelectArray[1]);

                                $('#edit_t_member_one').selectpicker('refresh');
                                $('#edit_t_member_two').selectpicker('refresh');
                            }
                        }
                    });

                    $.ajax({
                        url: "../assets/php/ajax/admin/getEditedRidersSelectList.php",
                        method: "POST",
                        cache: false,
                        data: {Rider_ID: parseInt(dataArray[2])},
                        success: function (dataSelectList) {
                            if(dataSelectList !== "ERROR") {
                                let dataSelectArray = JSON.parse(dataSelectList);
                                $('#edit_select-riders-list-2').html(dataSelectArray[0]);
                                $('#edit_select-drivers-list-2').html(dataSelectArray[1]);

                                $('#edit_t_member_one').selectpicker('refresh');
                                $('#edit_t_member_two').selectpicker('refresh');
                            }
                        }
                    });
                }
            }
        });
    } finally {
        $('#editTeam').modal();

        $('#edit-team-btn-confirm').click(function() {

            let teamName = $('#edit_t_name').val();
            let teamMemberOne = $('#edit_t_member_one').val();
            let teamMemberTwo = $('#edit_t_member_two').val();

            $.ajax({
                url: "../assets/php/ajax/admin/editTeam.php",
                method: "POST",
                cache: false,
                data: {Team_ID: team_id, Team_Name: teamName, Team_Member_One: teamMemberOne, Team_Member_Two: teamMemberTwo},
                success: function (data) {
                    if(data !== "ERROR") {
                        $('#editTeam').modal('toggle');

                        // Display Toast Message
                        $.ajax({
                            url: "../assets/php/ajax/ui/sendToastMessage.php",
                            method: "POST",
                            cache: false,
                            data: {Title: "Success!", Message: "The " + teamName + " team has been edited successfully."},
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
                            data: {Title: "Oops!", Message: "There was an issue while editing the team."},
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
                }
            });
        });
    }


}

function deleteTeam(team_id) {
    try {
        $.ajax({
            url: "../assets/php/ajax/admin/getTeamDeletingDetails.php",
            method: "POST",
            cache: false,
            data: {Team_ID: team_id},
            success: function (data) {
                if (data !== "ERROR") {
                    $('#delete_team_name').html(data);
                    $('#delete_team_name_2').html(data);
                }
            }
        });
    } finally {
        $('#deleteTeam').modal();

        $('#delete-team-btn-confirm').click(function() {
            $.ajax({
                url: "../assets/php/ajax/admin/deleteTeam.php",
                method: "POST",
                cache: false,
                data: {Team_ID: team_id},
                success: function (data) {
                    if (data !== "ERROR") {
                        $('#deleteTeam').modal('toggle');

                        // Display Toast Message
                        $.ajax({
                            url: "../assets/php/ajax/ui/sendToastMessage.php",
                            method: "POST",
                            cache: false,
                            data: {Title: "Success!", Message: "The team has been deleted."},
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
                            data: {Title: "Oops!", Message: "There was an issue while deleting the team."},
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
                }
            });
        });
    }
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
/* END: Individual Functions */