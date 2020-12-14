<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Apply Now</title>

        <script src="../assets/js/Credentials.js"></script>
        <script>
            getLoginJS();
        </script>

        <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@300&display=swap" rel="stylesheet">
        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="container-fluid bg-light">

        <!-- T&C Modal -->
        <div class='modal fade' id="tac-modal" tabindex='-1' role='dialog' aria-labelledby='tacModalLabel' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-scrollable modal-lg'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title' id='tacModalLabel'><strong>AIDER DRIVER AGREEMENT</strong></h5>
                    </div>
                    <div class='modal-body'>
                        <p><span>The following Agreement is for our Aiderdriver to agree and only can join once they agree.</span></p>
                        <p><span>Please read all the BASIC REQUIREMENT and TERMS &amp;CONDITION described below carefully before agreeing to this Agreement. If you agree with this Agreement and meet all the requirement below, you will join AIDER as an commission service provider and have the right to use the Platform provided by AIDER and operate in a manner that complies with its rules and regulations to earn personal profit from the platform. This Agreement applies to all employees who wish to join AIDER as a designated driver.</span></p>
                        <p><span>1.0 BASIC APPLICATION REQUIREMENT</span></p>
                        <p><span>1.1 Minimum of THREE years or more of safe and skilled driving experience. </span></p>
                        <p><span>1.2 Age 21-55 years old.</span></p>
                        <p><span>1.3 Have NO criminal record, NO history of psychiatric illnesses, NO history of drug abuse, and any history of being deemed unfit to be a designated driver for AIDER. </span></p>
                        <p><span>1.4 Good physical health, without any physical disabilities or large tattoos.</span></p>
                        <p><span>1.5 FOR APPLICATION OF MYAIDER, YOU ARE REQUIRED TO PROVIDE THE FOLLOWING INFORMATION FOR OUR VERIFICATION:</span></p>
                        <p><span>(a.) PHOTO OF NRIC (FRONT &amp; BACK)</span></p>
                        <p><span>(b.) PHOTO OF DRIVING LICENSE (FRONT &amp; BACK)</span></p>
                        <p><span>(c.) WHICH BANK + BANK ACCOUNT NUMBER + BANK NAME</span></p>
                        <p><span>(d.) TAKE PHOTO WITH YOUR IC AND MAKE SURE YOUR FACE CAN BE SEEN CLEARLY</span></p>
                        <p><span>PLEASE ENSURE THAT THE INFORMATION YOU PROVIDE IS ACCURATE AND REAL, IF ANY OF THE ABOVE REQUIREMENT IS MISSING, WE WILL NOT APPROVED YOUR APPLICATION UNTIL WE RECEIVED ALL THE REQUIRE INFORMATION ABOVE. MYAIDER IS COMMITTED TO NOT DISCLOSING YOUR PERSONAL INFORMATION AND PRIVACY.</span></p>
                        <p><span>&nbsp;</span></p>
                        <p><span>2.0 TERMS &amp; CONDITIONS</span></p>
                        <p><span>2.1 Do not exceed the speed limit while driving a customer vehicle and must drive safely to avoid any unnecessary accidents.</span></p>
                        <p><span>2.2 In the event of an accident caused by the driver&rsquo;s fault, driver shall take all the responsibility and compensation which including a fine from police in the amount of approximately RM300 and a fine from company in the amount of RM500.</span></p>
                        <p><span>2.3 Do not take or touch anything which belongs to the customer.</span></p>
                        <p><span>2.4 Before getting into or leaving a customer&rsquo;s car, regardless of whether the customer is sober or drunk, ask the customer to check their valuables and check for any damage to the car to avoid any unnecessary trouble.</span></p>
                        <p><span>2.5 Drivers are not allowed to ask for any tips from customers, unless they voluntary give it to you.</span></p>
                        <p><span>2.6 Drivers are strictly prohibited from drinking alcohol if they are going to be designated driver that night. If driver has consumed alcohol on that day, he/she are not allowed to take any orders.</span></p>
                        <p><span>2.7 Driver must wear uniforms with the company logo and working name card to prove that you are an AIDER employee to avoid unnecessary trouble.</span></p>
                        <p><span>2.8 In the event of any accident resulting in a loss of benefit to the Employee or the Company, AIDER will rigorously investigate what caused the accident. If found out that is because the employee has failed to comply with the Company&rsquo;s rule and information which stated in this Agreement, the Employee will be held fully responsible and will indemnify the Company or the customer for any loss of profit.</span></p>
                        <p><span>&nbsp;2.9 The company reserves the right to change this agreement at any time. Company will update the latest terms &amp; conditions to every commission service provier by email there after.</span></p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary d-block w-100' id='tac-rider-accept-btn'>Accept</button>
                    </div>
                </div>

                <!-- START: Toast Messages Area -->
                <div class="toast-container-modal" style="z-index: 9999;">

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12" style="height: 100vh;">
                <div class="row">
                    <div class="col-2 mt-4 text-center">
                        <i class="fas fa-long-arrow-alt-left fa-2x ml-2 text-primary" id="back-btn"></i>
                    </div>
                    <div class="col-10"></div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <img src="../assets/images/aider-r-logo.png" class="d-block ml-auto mr-auto p-3" style="width: auto; height: 180px;" />
                    </div>
                </div>
                <h3 class="text-center font-weight-bold mt-5 text-primary" style="font-family: 'Barlow', sans-serif;">APPLY NOW</h3>

                <div class="row">
                    <div class="col-12">
                        <form method="post">
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" name="user-firstname" placeholder="First Name" required />
                                <input type="text" class="form-control" name="user-lastname" placeholder="Last Name" required />
                            </div>
                            <div class="input-group mt-3">
                                <input type="email" class="form-control" name="user-email" placeholder="Email Address" required />
                            </div>
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" name="user-phone-num" placeholder="Phone Number (e.g. 0122233445)" required />
                            </div>
                            <div class="input-group mt-3 d-inline-block text-center">
                                <input type="checkbox" id="checkbox-tac" name="checkbox-tac" style="width: 20px; height: 20px; vertical-align: middle;" class="mr-1" required/>
                                <label for="checkbox-tac">
                                    I agree to the <a class="text-decoration-none text-primary" data-toggle="modal" data-target="#tac-modal">terms and conditions</a>
                                </label>
                            </div>
                            <button name="btn-reg" id="btn-reg" class="btn btn-outline-primary btn-block mt-5">Apply</button>
                        </form>

                        <h6 class="text-center mt-3">Already a rider? <a href="signin.php" class="text-primary">Sign in.</a></h6>
                    </div>
                </div>
            </div>
        </div>

        <div class="toast-container">
            <?php
                if(isset($_POST['btn-reg'])) {
                    $first_name = $_POST['user-firstname'];
                    $last_name = $_POST['user-lastname'];
                    $email = $_POST['user-email'];
                    $phone_num = $_POST['user-phone-num'];

                    $name = $first_name . " " . $last_name;

                    $response = $Aider->getUserModal()->getRiderModal()->applyRider($name, $email, $phone_num);

                    if(!$response['error']) {
                        echo $Aider->getAlerts()->sendToastCredentials("You've applied to become an Aider Rider", "Your application has been sent to us. You'll hear from us soon.");
                    } else {
                        echo $Aider->getAlerts()->sendToastCredentials("Oops! Something went wrong.", $response['message']);
                    }
                }
            ?>
        </div>


        <?php
            echo $Aider->getUI()->getBootstrapScripts();

        ?>
        <script>
            $(document).ready(function(){
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);

                $('#back-btn').click(function() {
                    window.location.href = "index.php";
                });
                $('.toast').toast('show');

                setTimeout(function() {
                    $('.toast-container').html("");
                }, 5000);

                $('#tac-rider-accept-btn').click(function() {
                    $('#tac-modal').modal('toggle');
                    $('#checkbox-tac').prop('checked', 'checked');
                });
            });
        </script>
    </body>

</html>