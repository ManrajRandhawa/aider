<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Home</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>

        <style>
            .mb-7 {
                margin-bottom: 4rem;
            }

            .mb-10 {
                margin-bottom: 7rem;
            }

            .progress {
                height: 8px;
            }

            .pad-1 {
                padding: 3px !important;
            }

            .carousel-control-prev-icon {
                background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23007bff' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E") !important;
            }

            .carousel-control-next-icon {
                background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23007bff' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E") !important;
            }
            
            i {
                cursor: pointer;
            }

            #car-prev, #car-next {
                height: 60%;
            }

            .rating {
                display: flex;
                flex-direction: row-reverse;
                justify-content: center
            }

            .rating>input {
                display: none
            }

            .rating>label {
                position: relative;
                width: 1em;
                font-size: 32pt;
                color: #87ceeb;
                cursor: pointer
            }

            .rating>label::before {
                content: "\2605";
                position: absolute;
                opacity: 0
            }

            .rating>label:hover:before,
            .rating>label:hover~label:before {
                opacity: 1 !important
            }

            .rating>input:checked~label:before {
                opacity: 1
            }

            .rating:hover>input:checked~label:before {
                opacity: 0.4
            }

            .btn-circle.btn-xl {
                width: 70px;
                height: 70px;
                padding: 10px 16px;
                border-radius: 35px;
                font-size: 24px;
                line-height: 1.33;
            }

            .btn-circle {
                width: 50px;
                height: 50px;
                padding: 6px 0px;
                border-radius: 30px;
                text-align: center;
                font-size: 12px;
                line-height: 1.42857;
            }

            .carousel-control-next-icon, .carousel-control-prev-icon {
                width: 0;
                height: 0;
            }
        </style>
    </head>

    <body class="bg-light">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <!-- Main Content -->
        <div class="" id="main-content-home">

            <?php
                $Aider->getUI()->getDashboard()->getDashboardHeader();
            ?>

            <div class="container">
                <div class="row">
                    <div class="col-6 mt-2">
                        <span class="h3 text-black-50">Hi, <span class="h3 text-dark font-weight-bold" id="home-hello-name"></span></span>
                    </div>
                    <div class="col-6">
                        <div class="mt-2 text-right">
                            <span class="h6 text-black-50">RM <span class="h3 text-dark font-weight-bold" id="home-wallet-balance"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="container">
                <div class="row m-1">
                    <!--
                    <div class="col-12 mt-3">
                        <a class="text-decoration-none" href="parcel.php">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><i class="fas fa-box mr-1"></i> Parcel</h5>
                                    <p class="card-text">Need to deliver something to a friend?</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    -->

                    <div class="col-3 mt-3">
                        <a class="text-decoration-none" href="parcel.php">
                            <button type="button" class="btn btn-primary btn-circle">
                                <i class="fas fa-box fa-2x"></i>
                            </button>
                            <h6 class="text-dark font-weight-bold mt-2">Parcel</h6>
                        </a>
                    </div>

                    <div class="col-3 mt-3">
                        <a class="text-decoration-none" href="driver.php">
                            <button type="button" class="btn btn-primary btn-circle">
                                <i class="fas fa-car fa-2x"></i>
                            </button>
                            <h6 class="text-dark font-weight-bold mt-2">Driver</h6>
                        </a>
                    </div>

                    <div class="col-3 mt-3">
                        <a class="text-decoration-none">
                            <button type="button" class="btn btn-secondary btn-circle">
                                <i class="fas fa-question fa-2x"></i>
                            </button>
                            <h6 class="text-dark font-weight-bold mt-2"></h6>
                        </a>
                    </div>

                    <div class="col-3 mt-3">
                        <a class="text-decoration-none">
                            <button type="button" class="btn btn-secondary btn-circle">
                                <i class="fas fa-question fa-2x"></i>
                            </button>
                            <h6 class="text-dark font-weight-bold mt-2"></h6>
                        </a>
                    </div>

                    <div class="col-3 mt-3">
                        <a class="text-decoration-none">
                            <button type="button" class="btn btn-secondary btn-circle">
                                <i class="fas fa-question fa-2x"></i>
                            </button>
                            <h6 class="text-dark font-weight-bold mt-2"></h6>
                        </a>
                    </div>

                    <div class="col-3 mt-3">
                        <a class="text-decoration-none">
                            <button type="button" class="btn btn-secondary btn-circle">
                                <i class="fas fa-question fa-2x"></i>
                            </button>
                            <h6 class="text-dark font-weight-bold mt-2"></h6>
                        </a>
                    </div>

                    <div class="col-3 mt-3">
                        <a class="text-decoration-none">
                            <button type="button" class="btn btn-secondary btn-circle">
                                <i class="fas fa-question fa-2x"></i>
                            </button>
                            <h6 class="text-dark font-weight-bold mt-2"></h6>
                        </a>
                    </div>

                    <div class="col-3 mt-3">
                        <a class="text-decoration-none">
                            <button type="button" class="btn btn-secondary btn-circle">
                                <i class="fas fa-question fa-2x"></i>
                            </button>
                            <h6 class="text-dark font-weight-bold mt-2"></h6>
                        </a>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="container mb-10">
                <div class="row">
                    <div class="col-12">
                        <h6 class="text-center mb-3">Promotions</h6>
                    </div>

                    <div class="col-12">
                        <div id="promotion-container">
                            <!-- Placeholder for Promotions -->
                        </div>
                    </div>
                </div>
            </div>

            <br/>

            <!-- Ongoing Order Small Container -->
            <div class="container mb-7 fixed-bottom p-0 d-none" id="ongoing-order-small-container">
                <div class="row m-0">
                    <div class="col-12">
                        <div class="row bg-white w-100 m-0 rounded shadow shadow-lg">
                            <div class="col-9">
                                <div class="pt-3 pb-3">
                                    <i class="fas fa-circle text-primary"></i>
                                    <span class="pl-1 text-primary font-weight-bold" id="loc-add"></span>

                                    <br/>

                                    <div class="row" style="padding-left: 15px !important;" id="prog-bar">

                                    </div>

                                    <h6 class="font-weight-bold mt-2" style="font-size: 11pt;" id="desc"></h6>
                                </div>
                            </div>
                            <div class="col-3 text-center">
                                <i class="fas fa-chevron-down fa-sm mt-3 text-primary float-right"></i>
                                <h6 class="text-dark font-weight-bold float-right position-absolute ml-3 mb-4" style="font-size: 11pt; bottom: 0;" id="time-order"></h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php
                $Aider->getUI()->getDashboard()->getBottomNavigation(1);
            ?>
        </div>

        <!-- Ongoing Order Large Container -->
        <div class="d-none" id="main-order-container-home">
            <div class="container vh-100 vw-100" style="z-index: 9999;">
                <div class="row">
                    <div class="col-12 text-center">

                        <div class="row mb-3 mt-4">
                            <div class="col-2">
                                <a href='#' class='text-decoration-none' id="close-ongoing-order">
                                    <i class="fas fa-chevron-circle-left fa-2x text-dark text-center"></i>
                                </a>
                            </div>
                            <div class="col-10 text-right p-0">
                                <img src='../assets/images/aider-logo-alt.png' style='height: 130px; width: auto;' class="mt-n5" id="order-type" />
                            </div>
                        </div>

                        <div id="carouselOrders" class="carousel slide" data-ride="carousel" data-interval="false">
                            <ol class="carousel-indicators" id="ca-ind">

                            </ol>
                            <div class="carousel-inner" id="ca-inn">

                            </div>
                            <a class="carousel-control-prev" href="#carouselOrders" role="button" data-slide="prev" id="car-prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselOrders" role="button" data-slide="next" id="car-next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Dashboard.js"></script>
        <script>
            $(document).ready(function() {
                getDashboardJS();
                getHomeDashboardJS();
            });
        </script>
    </body>

</html>