<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> Admin | Promotions</title>

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


        <!-- Modal: Add Promotion -->
        <div class='modal fade' id="promotion-modal" tabindex='-1' role='dialog' aria-labelledby='promotionModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='promotionModalLabel'>Promotions</h5>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class='modal-body'>
                            <div class='form-group'>
                                <label for='promo_title' class='col-form-label'>Promotion Title:</label>
                                <input type='text' class='form-control' name='promo_title' id='promo_title' required>
                            </div>
                            <div class='form-group'>
                                <label for='promo_url' class='col-form-label'>Promotion URL <span class="text-muted">(leave blank if not required)</span>:</label>
                                <input type='text' class='form-control' name='promo_url' id='promo_url' placeholder="https://aider.my">
                            </div>
                            <div class='form-group'>
                                <label for='promo_desc' class='col-form-label'>Promotion Description:</label>
                                <input type='text' class='form-control' name='promo_desc' id='promo_desc' required>
                            </div>
                            <div class='form-group'>
                                <label for='promo_img' class='col-form-label'>Promotion Image (optional):</label>
                                <input type='file' class='form-control' name='fileToUpload' id='fileToUpload'>
                            </div>
                        </div>
                        <div class='modal-footer'>
                            <button type='submit' class='btn btn-primary' id='add-promo-btn' name="add-promo-btn">Add Promotion</button>
                        </div>
                    </form>
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
                    <h3 class="font-weight-bold mb-4">Promotions</h3>
                </div>
                <div class="col-3 mt-3">
                    <a class="float-right btn btn-outline-primary btn-lg text-primary" href="#" data-toggle="modal" data-target="#promotion-modal"><i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <div class="col-12 mt-3">
                    <div class="row" id="promo-container">

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
            <?php
                if(isset($_POST["add-promo-btn"])) {
                    if($_FILES['fileToUpload']['name'] == "") {
                        $addPromoResponse = $Aider->getUserModal()->getPromoModal()->addPromo($_POST['promo_title'], $_POST['promo_url'], $_POST['promo_desc'], "");
                        if($addPromoResponse['error']) {
                            echo $Aider->getAlerts()->sendToastDashboard('Oops, something happened!', $addPromoResponse['message']);
                        } else {
                            echo $Aider->getAlerts()->sendToastDashboard('Promotion added!', "The promotion has been added.");
                        }
                    } else {
                        $target_dir = "../assets/images/promos/";
                        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                        if($check !== false) {
                            $uploadOk = 1;

                            // Check if file already exists
                            if (file_exists($target_file)) {
                                echo $Aider->getAlerts()->sendToastDashboard('Oops, something happened!', "The file already exists. Change the file name and try again.");
                                $uploadOk = 0;
                            }

                            // Check file size
                            if ($_FILES["fileToUpload"]["size"] > 5000000) {
                                echo $Aider->getAlerts()->sendToastDashboard('Oops, something happened!',  "Your file is too large.");
                                $uploadOk = 0;
                            }

                            // Allow certain file formats
                            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                                && $imageFileType != "gif" ) {
                                echo $Aider->getAlerts()->sendToastDashboard('Oops, something happened!',  "Only JPG, JPEG, PNG & GIF files are allowed.");
                                $uploadOk = 0;
                            }

                            // Check if $uploadOk is set to 0 by an error
                            if ($uploadOk == 0) {
                                $Aider->getAlerts()->sendToastDashboard('Oops, something happened!',  "There was an issue while uploading your file.");
                                // if everything is ok, try to upload file
                            } else {
                                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                    $addPromoResponse = $Aider->getUserModal()->getPromoModal()->addPromo($_POST['promo_title'], $_POST['promo_url'], $_POST['promo_desc'], htmlspecialchars(basename($_FILES["fileToUpload"]["name"])));
                                    if($addPromoResponse['error']) {
                                        echo $Aider->getAlerts()->sendToastDashboard('Oops, something happened!', $addPromoResponse['message']);
                                    } else {
                                        echo $Aider->getAlerts()->sendToastDashboard('Promotion added!', "The promotion has been added.");
                                    }
                                } else {
                                    echo $Aider->getAlerts()->sendToastDashboard('Oops, something happened!', "There was an issue while uploading your file.");
                                }
                            }
                        } else {
                            echo $Aider->getAlerts()->sendToastDashboard('Oops, something happened!', "The file is not an image.");
                            $uploadOk = 0;
                        }
                    }
                }
            ?>
        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js" integrity="sha512-yDlE7vpGDP7o2eftkCiPZ+yuUyEcaBwoJoIhdXv71KZWugFqEphIS3PU60lEkFaz8RxaVsMpSvQxMBaKVwA5xg==" crossorigin="anonymous"></script>
        <script src="../assets/js/Admin.js"></script>
        <script>

            $(document).ready(function() {
                getPromoDashboard();
            });

        </script>
    </body>

</html>