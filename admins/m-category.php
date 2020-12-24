<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Admin | Menu Category</title>

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


        <!-- Modal: Add Category -->
        <div class='modal fade' id="category" tabindex='-1' role='dialog' aria-labelledby='categoryModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='categoryModalLabel'>Add Category</h5>
                    </div>
                    <div class='modal-body'>
                        <div class='form-group'>
                            <label for='cat_name' class='col-form-label'>Category Name:</label>
                            <input type='text' class='form-control' name='cat_name' id='cat_name' required>
                        </div>
                    </div>
                    <div class='modal-footer'>
                        <button type='submit' class='btn btn-primary' id='add-cat-btn' name="add-cat-btn">Add Category</button>
                    </div>
                </div>

                <!-- START: Toast Messages Area -->
                <div class="toast-container-modal" style="z-index: 9999;">

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Modal: Delete Category -->
        <div class='modal fade' id="categoryDel" tabindex='-1' role='dialog' aria-labelledby='categoryDelModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='categoryDelModalLabel'>Delete Category</h5>
                    </div>
                    <div class='modal-body'>
                        <h5>Are you sure you want to delete the <span id="cat_name_modal" class="text-danger"></span> category?</h5>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-light' id='cancel-cat-btn' name="cancel-cat-btn" data-dismiss="modal">Cancel</button>
                        <button type='button' class='btn btn-danger' id='delete-cat-btn' name="delete-cat-btn">Delete</button>
                    </div>
                </div>

                <!-- START: Toast Messages Area -->
                <div class="toast-container-modal" style="z-index: 9999;">

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Main Content -->
        <div class="container">
            <div class="row">
                <div class="col-9 mt-4">
                    <h3 class="font-weight-bold mb-4">
                        <a href="food.php" class="text-decoration-none mr-2">
                            <i class="fas fa-chevron-circle-left fa-sm text-primary"></i>
                        </a>
                        Menu Categories
                    </h3>
                </div>
                <div class="col-3 mt-3">
                    <a class="float-right btn btn-outline-primary btn-lg text-primary" href="#" data-toggle="modal" data-target="#category"><i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="row">
                        <div class="col-12" id="r-category-container">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br/>
        <br/>

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous"></script>
        <script src="../assets/js/Admin.js"></script>
        <script>

            $(document).ready(function() {
                getMCategoryDashboard();
            });

        </script>
    </body>

</html>