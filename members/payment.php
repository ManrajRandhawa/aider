<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/modals/CredentialModal.php";

    $Aider = new Aider();
    $credentialsModal = new CredentialModal();



?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Payment</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>

        <style>
            .circle-loader {
                margin-bottom: 1rem;
                border: 2px solid rgba(0, 0, 0, 0.2);
                animation: loader-spin 0.87s infinite linear;
                position: relative;
                display: inline-block;
                vertical-align: top;
                border-radius: 50%;
                width: 80px;
                height: 80px;
            }
            .circle-loader-page {
                margin: 0 auto 0 auto;
                border: 2px solid rgba(0, 0, 0, 0.2);
                animation: loader-spin 0.87s infinite linear;
                display: block;
                vertical-align: center;
                border-radius: 50%;
                width: 80px;
                height: 80px;
            }
            .circle-loader-page.success {
                border-left-color: #007bff;
            }
            .circle-loader.success {
                border-left-color: #007bff;
            }
            .circle-loader.danger {
                border-left-color: #dc3545;
            }
            .load-complete {
                -webkit-animation: none;
                animation: none;
                transition: border 500ms ease-out;
            }
            .load-complete.success {
                border-color: #007bff;
            }
            .load-complete.danger {
                border-color: #dc3545;
            }
            .checkmark {
                display: none;
            }
            .checkmark.draw:after {
                animation-duration: 800ms;
                animation-timing-function: ease;
                animation-name: checkmark;
                transform: scaleX(-1) rotate(135deg);
            }
            .checkmark.draw-2:after {
                animation-duration: 800ms;
                animation-timing-function: ease;
                animation-name: checkmark;
                transform: scaleX(-1) rotate(45deg);
            }
            .checkmark.draw-3:after {
                animation-duration: 800ms;
                animation-timing-function: ease;
                animation-name: checkmark;
                transform: scaleX(-1) rotate(135deg);
            }
            .checkmark.draw:after {
                opacity: 1;
                height: 40px;
                width: 20px;
                transform-origin: left top;
                content: '';
                left: 18px;
                top: 40px;
                position: absolute;
            }
            .checkmark.draw-2:after {
                opacity: 1;
                height: 40px;
                width: 20px;
                transform-origin: left top;
                content: '';
                left: 38px;
                top: 11px;
                position: absolute;
            }
            .checkmark.draw-3:after {
                opacity: 1;
                height: 40px;
                width: 20px;
                transform-origin: left top;
                content: '';
                left: 11px;
                top: 40px;
                position: absolute;
            }
            .checkmark.success-mark:after {
                border-right: 3px solid #007bff;
                border-top: 3px solid #007bff;
            }
            .checkmark.danger-mark:after {
                border-right: 3px solid #dc3545;
            }
            @keyframes loader-spin {
                0% {
                    transform: rotate(0deg);
                }
                100% {
                    transform: rotate(360deg);
                }
            }
            @keyframes checkmark {
                0% {
                    height: 0;
                    width: 0;
                    opacity: 1;
                }
                20% {
                    height: 0;
                    width: 20px;
                    opacity: 1;
                }
                40% {
                    height: 40px;
                    width: 20px;
                    opacity: 1;
                }
                100% {
                    height: 40px;
                    width: 20px;
                    opacity: 1;
                }
            }
            
            i {
                cursor: pointer !important;
            }
            
            #btn-back {
                cursor: pointer !important;
            }
        </style>
    </head>

    <body class="bg-white">

        <!-- Modal: First Time Login - Change Password -->
        <?php
            $Aider->getUI()->getDashboard()->getFirstTimePasswordChangeModal();
        ?>

        <div id="main-v-1" class="" style="background: #f8f9fa; height: 100vh;">
            <div style="padding-top: 60%;">
                <div class="circle-loader-page success">

                </div>
            </div>

        </div>

        <div id="main-v-2" class="d-none">
            <div class="bg-light">
                <?php
                $Aider->getUI()->getDashboard()->getDashboardHeader();
                ?>
                <!-- Header: 1st View -->
                <div class="container bg-light" id="payment-container-top-v1">
                    <div class="row">
                        <div class="col-12 mt-3">
                            <h3 class="font-weight-bold">Payment</h3>
                        </div>

                        <div class="col-12 mt-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-primary font-weight-bold">Wallet Balance</h5>
                                    <h6>RM <span class="h3 font-weight-bold" id="wallet-balance">0.00</span></h6>
                                </div>
                                <div class="card-footer bg-white" id="top-up-wallet">
                                    <span class="card-text text-dark h6">Top-up wallet balance</span>
                                    <span class="float-right"><i class="fas fa-chevron-right"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Header: 2nd View -->
                <div class="container bg-light min-vh-100 d-none" id="payment-container-top-v2">
                    <div class="row">
                        <div class="col-12 mt-3">
                            <a href='#' class='text-decoration-none' onclick='backBtnFadeOff();'>
                                <i class="fas fa-chevron-left fa-lg mb-1"></i>
                            </a>
                            <span class="h3 font-weight-bold ml-4">Payment <span class="h5 font-weight-bold">> Top-Up</span></span>
                        </div>

                        <div class="col-12 mt-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-primary font-weight-bold">Enter a top-up value (RM)</h5>
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="number" class="form-control" placeholder="Enter an amount (RM)" id="transaction-amount" />
                                    </div>

                                    <select title="Select a bank" class="selectpicker w-100" id="bank-code">
                                        <option selected disabled>Select a bank</option>
                                        <?php echo $credentialsModal->getBanks()['data']; ?>
                                    </select>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-lg w-100 mt-4" id="confirm-transaction-btn">Confirm</button>
                        </div>
                    </div>
                </div>

                <!-- START: 3rd View - Payment Completed -->
                <div class="container-fluid vw-100 vh-100 bg-light d-none" id="payment-completed-layout" style="z-index: 99999;">
                    <div class="row h-100">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-3"></div>
                                <div class="col-6 text-center align-self-center">
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <div class="circle-loader success">
                                        <div class="checkmark draw success-mark"></div>
                                    </div>
                                </div>
                                <div class="col-3"></div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <div class="d-none" id="payment-complete-text">
                                        <h5 class="font-weight-bold text-primary mb-3">You've successfully topped-up</h5>
                                        <h6 class="font-weight-bold text-primary mb-3">RM <span id="payment-complete-rm" class="h3 font-weight-bold"></span></h6>
                                        <h6 class="text-primary mb-5"><i>Reference Code: <span id="payment-complete-ref" class="font-weight-bold"></span></i></h6>

                                        <a href='home.php' class='text-decoration-none'>
                                            <i class="fal fa-times-circle fa-3x text-primary mt-5"></i>
                                        </a>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: 3rd View - Payment Completed -->

                <!-- START: 4th View - Payment Failed -->
                <div class="container-fluid vw-100 vh-100 bg-light d-none" id="payment-failed-layout" style="z-index: 99999;">
                    <div class="row h-100">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-3"></div>
                                <div class="col-6 text-center align-self-center ">
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <div class="circle-loader danger">
                                        <div class="checkmark draw-2 danger-mark"></div>
                                        <div class="checkmark draw-3 danger-mark"></div>
                                    </div>
                                </div>
                                <div class="col-3"></div>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <br/>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <div class="d-none" id="payment-fail-text">
                                        <h5 class="font-weight-bold text-danger">Oops, top-up wasn't successful.</h5>
                                        <h5 class="font-weight-bold text-danger mb-4">Please try again.</h5>
                                        <h6 class="text-danger mb-5"><i>Reference Code: <span id="payment-fail-ref" class="font-weight-bold"></span></i></h6>

                                        <a href='home.php' class='text-decoration-none'>
                                            <i class="fal fa-times-circle fa-3x text-danger mt-5"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: 4th View - Payment Failed -->
            </div>

            <!-- Main Content: 1st View -->
            <div class="container" id="payment-container-bottom-v1">
                <div class="row">
                    <div class="col-12">
                        <h5 class="font-weight-bold mt-4">Recent transactions</h5>

                        <!-- Transaction Container -->
                        <div id="transaction-container">

                        </div>

                    </div>
                </div>
            </div>

            <br/>

            <?php
            $Aider->getUI()->getDashboard()->getBottomNavigation(2);
            ?>
        </div>

        <!-- START: Toast Messages Area -->
        <div class="toast-container">

        </div>
        <!-- END: Toast Messages Area -->


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Dashboard.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous"></script>
        <script>

            $(document).ready(function() {
                $.support.cors = true;

                $('#main-v-1').addClass('d-none');
                $('#main-v-2').removeClass('d-none');
                
                getDashboardJS();
                getPaymentDashboardJS();
            });
            
            function backBtnFadeOff() {
                $('#payment-container-top-v2').fadeOut('fast', function() {
                    setTimeout(function() {
                        $('#payment-container-top-v1').fadeIn('fast');
                        $('#payment-container-bottom-v1').fadeIn('fast');
                    }, 133);
                });
            }
        </script>
    </body>

</html>