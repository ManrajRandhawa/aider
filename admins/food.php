<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Admin | Food</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

        <style>
            .pac-container {
                overflow-y: scroll;
                z-index: 9999;
            }
        </style>
    </head>

    <body class="bg-light">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <!-- Modal: Add Restaurant -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='addRestaurantLabel' id="addRestaurant" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <form method="post" enctype="multipart/form-data" id="add-restaurant-form">
                        <div class='modal-header'>
                            <h5 class='modal-title' id='addRestaurantLabel'>Add Restaurant</h5>
                        </div>
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label for='r_name' class='col-form-label'>Restaurant Name:</label>
                                <input type='text' class='form-control' name='r_name' id='r_name' required>
                            </div>
                            <div class='form-group'>
                                <label for='r_cat' class='col-form-label'>Categories:</label>
                                <select class="selectpicker w-100" data-live-search="true" id="r_cat" name="r_cat" multiple required>
                                    <?php
                                        $responseCategory = $Aider->getUserModal()->getFoodModal()->getCategoryForSelect('R');
                                        echo $responseCategory['data'];
                                    ?>
                                </select>
                            </div>
                            <div class='form-group'>
                                <label for='r_email' class='col-form-label'>Email Address:</label>
                                <input type='email' class='form-control' name='r_email' id='r_email' required>
                            </div>
                            <div class='form-group'>
                                <label for='r_hp' class='col-form-label'>Phone Number:</label>
                                <input type='text' class='form-control' name='r_hp' id='r_hp' required>
                            </div>
                            <div class="form-group">
                                <label for='r_tele_username' class='col-form-label'>Telegram Username:</label>
                                <div class='input-group'>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="pref">@</span>
                                    </div>
                                    <input type='text' class='form-control' name='r_tele_username' id='r_tele_username' aria-describedby="pref" required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='r_loc' class='col-form-label'>Location:</label>
                                <input type='text' class='form-control' name='r_loc' id='r_loc' required>
                            </div>
                            <div class='form-group'>
                                <label for='r_image' class='col-form-label'>Featured Image:</label>
                                <input type='file' class='form-control' name='r_image' id='r_image' required>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' id='add-restaurant-btn'>Add</button>
                        </div>
                    </form>
                </div>
                <!-- START: Toast Messages Area -->
                <div class='toast-container-modal' style='z-index: 9999;'>

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Modal: Edit Restaurant -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='editRestaurantLabel' id="editRestaurant" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <form method="post" enctype="multipart/form-data" id="edit-restaurant-form">
                        <div class='modal-header'>
                            <h5 class='modal-title' id='editRestaurantLabel'>Edit Restaurant - <span id="restaurant_name_edit"></span></h5>
                        </div>
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label for='r_name_edit' class='col-form-label'>Restaurant Name:</label>
                                <input type='text' class='form-control' name='r_name_edit' id='r_name_edit' required>
                            </div>
                            <div class='form-group'>
                                <label for='r_cat_edit' class='col-form-label'>Categories:</label>
                                <select class="selectpicker w-100" data-live-search="true" id="r_cat_edit" name="r_cat_edit" multiple required>
                                    <?php
                                        $responseCategory = $Aider->getUserModal()->getFoodModal()->getCategoryForSelect('R');
                                        echo $responseCategory['data'];
                                    ?>
                                </select>
                            </div>
                            <div class='form-group'>
                                <label for='r_email_edit' class='col-form-label'>Email Address:</label>
                                <input type='email' class='form-control' name='r_email_edit' id='r_email_edit' required>
                            </div>
                            <div class='form-group'>
                                <label for='r_hp_edit' class='col-form-label'>Phone Number:</label>
                                <input type='text' class='form-control' name='r_hp_edit' id='r_hp_edit' required>
                            </div>
                            <div class="form-group">
                                <label for='r_tele_username_edit' class='col-form-label'>Telegram Username:</label>
                                <div class='input-group'>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="pref">@</span>
                                    </div>
                                    <input type='text' class='form-control' name='r_tele_username_edit' id='r_tele_username_edit' aria-describedby="pref" required>
                                </div>
                            </div>
                            <div class='form-group'>
                                <label for='r_loc_edit' class='col-form-label'>Location:</label>
                                <input type='text' class='form-control' name='r_loc_edit' id='r_loc_edit' required>
                            </div>
                            <div class='form-group'>
                                <label for='r_image_edit' class='col-form-label'>Featured Image (optional):</label>
                                <input type='file' class='form-control' name='r_image_edit' id='r_image_edit'>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' id='edit-restaurant-btn'>Edit</button>
                        </div>
                    </form>
                </div>
                <!-- START: Toast Messages Area -->
                <div class='toast-container-modal' style='z-index: 9999;'>

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Modal: Add Menu Item -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='addMenuItemLabel' id="addMenuItem" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <form method="post" enctype="multipart/form-data" id="add-menu-item-form">
                        <div class='modal-header'>
                            <h5 class='modal-title' id='addMenuItemLabel'>Add Menu Item<span class="h6 font-weight-bold text-success"> > <span id="modal-restaurant-name"></span></span></h5>
                        </div>
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label for='m_name' class='col-form-label'>Name:</label>
                                <input type='text' class='form-control' name='m_name' id='m_name' required>
                            </div>
                            <div class='form-group'>
                                <label for='m_cat' class='col-form-label'>Categories:</label>
                                <select class="selectpicker w-100" data-live-search="true" id="m_cat" name="m_cat" multiple required>
                                    <?php
                                        $responseCategory = $Aider->getUserModal()->getFoodModal()->getCategoryForSelect('M');
                                        echo $responseCategory['data'];
                                    ?>
                                </select>
                            </div>
                            <div class='form-group'>
                                <label for='m_desc' class='col-form-label'>Description:</label>
                                <textarea class='form-control' name='m_desc' id='m_desc' rows="5" required></textarea>
                            </div>
                            <div class='form-group'>
                                <label for='m_price' class='col-form-label'>Price (RM):</label>
                                <input type='number' class='form-control' name='m_price' id='m_price' placeholder="0.00" required>
                            </div>
                            <div class='form-group'>
                                <label for='m_image' class='col-form-label'>Featured Image:</label>
                                <input type='file' class='form-control' name='m_image' id='m_image' required>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' id='add-menu-item-btn'>Add</button>
                        </div>
                    </form>
                </div>
                <!-- START: Toast Messages Area -->
                <div class='toast-container-modal' style='z-index: 9999;'>

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Modal: Edit Menu Item -->
        <div class='modal fade' tabindex='-1' role='dialog' aria-labelledby='editMenuItemLabel' id="editMenuItem" aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <form method="post" enctype="multipart/form-data" id="edit-menu-item-form">
                        <div class='modal-header'>
                            <h5 class='modal-title' id='editMenuItemLabel'>Edit Menu Item<span class="h6 font-weight-bold text-success"> > <span id="edit-restaurant-name"></span></span></h5>
                        </div>
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label for='m_name_edit' class='col-form-label'>Name:</label>
                                <input type='text' class='form-control' name='m_name_edit' id='m_name_edit' required>
                            </div>
                            <div class='form-group'>
                                <label for='m_cat_edit' class='col-form-label'>Categories:</label>
                                <select class="selectpicker w-100" data-live-search="true" id="m_cat_edit" name="m_cat_edit" multiple required>
                                    <?php
                                        $responseCategory = $Aider->getUserModal()->getFoodModal()->getCategoryForSelect('M');
                                        echo $responseCategory['data'];
                                    ?>
                                </select>
                            </div>
                            <div class='form-group'>
                                <label for='m_desc_edit' class='col-form-label'>Description:</label>
                                <textarea class='form-control' name='m_desc_edit' id='m_desc_edit' rows="5" required></textarea>
                            </div>
                            <div class='form-group'>
                                <label for='m_price_edit' class='col-form-label'>Price (RM):</label>
                                <input type='number' class='form-control' name='m_price_edit' id='m_price_edit' placeholder="0.00" required>
                            </div>
                            <div class='form-group'>
                                <label for='m_image_edit' class='col-form-label'>Featured Image:</label>
                                <input type='file' class='form-control' name='m_image_edit' id='m_image_edit' required>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' id='edit-menu-item-btn'>Edit</button>
                        </div>
                    </form>
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
                <div class="col-12 mt-4">
                    <h3 class="font-weight-bold">
                        <a href="food.php" class="text-decoration-none d-none mr-2" id="food-back-btn">
                            <i class="fas fa-chevron-circle-left fa-sm text-primary"></i>
                        </a>
                        Food
                    </h3>
                </div>

                <div class="col-12">
                    <div class="row">
                        <div class="col-6 mt-1">
                            <a class="text-decoration-none" href="r-category.php">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-store mr-1"></i> Restaurant Categories</h5>
                                        <p class="card-text">Manage your restaurant categories.</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-6 mt-1">
                            <a class="text-decoration-none" href="m-category.php">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fas fa-hamburger mr-1"></i> Menu Categories</h5>
                                        <p class="card-text">Manage your menu categories.</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <hr/>

                </div>
            </div>
        </div>

        <div class="container mb-5" id="restaurants-view">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="row">
                        <div class="col-12 pr-0">
                            <h5 class="pt-1">Restaurants
                                <a href="#" data-toggle="modal" data-target="#addRestaurant">
                                    <button class="btn btn-sm btn-primary rounded-circle ml-2">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </a>
                            </h5>
                        </div>
                        <div class="col-12 mt-1">
                            <input type="text" class="form-control form-control-lg" id="restaurant-search" placeholder="Enter at least 3 characters to search for restaurants" aria-label="Search">
                        </div>
                    </div>
                </div>


                <div class="col-12">
                    <div class="row" id="restaurant-container">

                    </div>
                </div>
            </div>
        </div>

        <div class="container mb-5 d-none" id="menu-view">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="row">
                        <div class="col-12 pr-0">
                            <h5 class="pt-1">Menu Items for <span class="font-weight-bold" id="menu_view_restaurant_name"></span>
                                <a href="#" data-toggle="modal" data-target="#addMenuItem">
                                    <button class="btn btn-sm btn-primary rounded-circle ml-2">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </a>
                            </h5>
                        </div>
                        <div class="col-12 mt-1">
                            <input type="text" class="form-control form-control-lg" id="menu-search" placeholder="Enter at least 3 characters to search for menu items in this restaurant" aria-label="Search">
                        </div>
                    </div>
                </div>


                <div class="col-12 mt-2">
                    <div class="row" id="menu-container">

                    </div>
                </div>
            </div>
        </div>

        <?php
            $Aider->getUI()->getDashboard()->getAdminBottomNavigation(2);
        ?>

        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Admin.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo MAP_API_KEY; ?>&libraries=places&callback=initMap"></script>
        <script>
            function initMap() {
                let home, homeEdit, autocompleteHome, autocompleteHomeEdit;

                home = document.getElementById('r_loc');
                homeEdit = document.getElementById('r_loc_edit');
                autocompleteHome = new google.maps.places.Autocomplete(home);
                autocompleteHomeEdit = new google.maps.places.Autocomplete(homeEdit);
                autocompleteHome.setComponentRestrictions({'country': ['my']});
                autocompleteHomeEdit.setComponentRestrictions({'country': ['my']});
            }

            $(document).ready(function() {
                getFoodDashboard();
            });

        </script>
    </body>

</html>