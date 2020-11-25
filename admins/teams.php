<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Admin | Teams</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" integrity="sha512-ARJR74swou2y0Q2V9k0GbzQ/5vJ2RBSoCWokg4zkfM29Fb3vZEQyv0iWBMW/yvKgyHSR/7D64pFMmU8nYmbRkg==" crossorigin="anonymous" />
    </head>

    <body class="bg-light">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <!-- Modal: Add a New Team -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' id="addTeam" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='approveModalLabel'>Add a Team</h5>
                    </div>
                    <div class='modal-body'>
                        <form method='post'>
                            <div class='form-group'>
                                <label for='t_name' class='col-form-label'>Team Name:</label>
                                <input type='text' class='form-control' name='t_name' id='t_name'>
                            </div>
                            <div class='form-group'>
                                <select class="selectpicker w-100" data-live-search="true" name="t_member_one" id="t_member_one">
                                    <option disabled selected>Select 1st Team Member</option>
                                    <optgroup label="Riders" id="select-riders-list-1">

                                    </optgroup>
                                    <optgroup label="Drivers" id="select-drivers-list-1">

                                    </optgroup>
                                </select>
                            </div>
                            <div class='form-group'>
                                <select class="selectpicker w-100" data-live-search="true" name="t_member_two" id="t_member_two">
                                    <option disabled selected>Select 2nd Team Member</option>
                                    <optgroup label="Riders" id="select-riders-list-2">

                                    </optgroup>
                                    <optgroup label="Drivers" id="select-drivers-list-2">

                                    </optgroup>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='add-team-btn-confirm'>Add</button>
                        <button type='button' class='btn btn-outline-primary' data-dismiss="modal" id='add-team-btn-cancel'>Cancel</button>
                    </div>
                </div>
                <!-- START: Toast Messages Area -->
                <div class='toast-container-modal' style='z-index: 9999;'>

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Modal: Edit Team -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' id="editTeam" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='approveModalLabel'>Edit Team - <span id="edit_team_name"></span></h5>
                    </div>
                    <div class='modal-body'>
                        <form method='post'>
                            <div class='form-group'>
                                <label for='edit_t_name' class='col-form-label'>Team Name:</label>
                                <input type='text' class='form-control' name='edit_t_name' id='edit_t_name'>
                            </div>
                            <div class='form-group'>
                                <select class="selectpicker w-100" data-live-search="true" name="edit_t_member_one" id="edit_t_member_one">
                                    <option disabled>Select 1st Team Member</option>
                                    <optgroup label="Riders" id="edit_select-riders-list-1">

                                    </optgroup>
                                    <optgroup label="Drivers" id="edit_select-drivers-list-1">

                                    </optgroup>
                                </select>
                            </div>
                            <div class='form-group'>
                                <select class="selectpicker w-100" data-live-search="true" name="edit_t_member_two" id="edit_t_member_two">
                                    <option disabled>Select 2nd Team Member</option>
                                    <optgroup label="Riders" id="edit_select-riders-list-2">

                                    </optgroup>
                                    <optgroup label="Drivers" id="edit_select-drivers-list-2">

                                    </optgroup>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='edit-team-btn-confirm'>Confirm Changes</button>
                        <button type='button' class='btn btn-outline-primary' data-dismiss="modal" id='edit-team-btn-cancel'>Cancel</button>
                    </div>
                </div>
                <!-- START: Toast Messages Area -->
                <div class='toast-container-modal' style='z-index: 9999;'>

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Modal: Delete Team -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='myLargeModalLabel' id="deleteTeam" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='approveModalLabel'>Delete Team - <span class="text-danger" id="delete_team_name"></span></h5>
                    </div>
                    <div class='modal-body'>
                        <h6>You are about to delete the <span class="text-danger" id="delete_team_name_2"></span> team.</h6>
                        <h6 class="font-weight-bold">Are you sure?</h6>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary' id='delete-team-btn-confirm'>Delete Team</button>
                        <button type='button' class='btn btn-outline-primary' data-dismiss="modal" id='delete-team-btn-cancel'>Cancel</button>
                    </div>
                </div>
                <!-- START: Toast Messages Area -->
                <div class='toast-container-modal' style='z-index: 9999;'>

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>


        <!-- Main Content -->
        <div class="container">
            <div class="row">
                <div class="col-9 mt-4">
                    <h3 class="font-weight-bold mb-4">Teams</h3>
                </div>
                <div class="col-3 mt-3">
                    <a class="float-right btn btn-outline-primary btn-lg text-primary" onclick="addTeam();"><i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="row" id="teams-container">

                    </div>
                </div>
            </div>
        </div>

        <br/>
        <br/>

        <?php
            $Aider->getUI()->getDashboard()->getAdminBottomNavigation(1);
        ?>

        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous"></script>
        <script src="../assets/js/Admin.js"></script>
        <script>

            $(document).ready(function() {
                getTeamsDashboard();
            });

        </script>
    </body>

</html>