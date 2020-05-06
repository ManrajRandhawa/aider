<?php

    include "php/Aider.php";

    $Whiz = new Aider();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php echo SITE_NAME; ?> | Register</title>

        <?php
            echo $Whiz->getUI()->getMaterialHead();
            echo $Whiz->getUI()->getCustomBootstrapHead();
        ?>

        <link rel="stylesheet" type="text/css" href='assets/css/material-design/md.css' />
        <link rel="stylesheet" type="text/css" href='assets/css/whiz.css' />
    </head>

    <body class="container-fluid bg-light fade-in-page">

        <!-- Main Content -->
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="row mt-n2">
                    <div class="col-1"></div>
                    <div class="col-10">

                        <?php
                            if(isset($_POST["btn_register"])) {
                                // Student
                                $name = $_POST["s_name_input"];
                                $email = $_POST["s_email_input"];
                                $pswd = $_POST["s_password_input"];
                                $cpswd = $_POST["s_cpassword_input"];
                                $dob = $_POST["s_dob_input"];

                                // Parent
                                $pname = $_POST['p_name_input'];
                                $pemail = $_POST['p_email_input'];
                                $ppswd = $_POST['p_password_input'];
                                $pcpswd = $_POST['p_cpassword_input'];

                                $crosscheckPswdResponse = $Whiz->getCredentials()->getRegister()->crosscheckPassword($pswd, $cpswd);
                                $pcrosscheckPswdResponse = $Whiz->getCredentials()->getRegister()->crosscheckPassword($ppswd, $pcpswd);

                                if(!$crosscheckPswdResponse['error'] && !$pcrosscheckPswdResponse['error']) {
                                        $responseStudent = $Whiz->getCredentials()->getRegister()->registerStudent($name, $email, $pswd, $dob);

                                        if(!$responseStudent['error']) {

                                            $studentIDResponse = $Whiz->getCredentials()->getRegister()->getStudentID($responseStudent['studentEmail']);

                                            if(!$studentIDResponse['error']) {
                                                $responseParent = $Whiz->getCredentials()->getRegister()->registerParent($studentIDResponse['studentID'], $pname, $pemail, $ppswd);

                                                if(!$responseParent['error']) {
                                                    echo $Whiz->getAlerts()->sendSuccessMessage("You've been successfully registered.");
                                                } else {
                                                    echo $Whiz->getAlerts()->sendErrorMessage($responseParent['message']);
                                                }
                                            } else {
                                                echo $Whiz->getAlerts()->sendErrorMessage($studentIDResponse['message']);
                                            }
                                        } else {
                                            echo $Whiz->getAlerts()->sendErrorMessage($responseStudent['message']);
                                        }
                                } else {
                                    echo $Whiz->getAlerts()->sendErrorMessage($crosscheckPswdResponse['message']);
                                }
                            }
                        ?>

                        <div class="mt-4">
                            <div class="row">
                                <div class="col-6">
                                    <img src="assets/images/logo.png" width="30px" alt="Whiz QBank"/>
                                    <h1 class="mt-4 ml-2 navbar-brand">Whiz QBank</h1>
                                </div>
                                <div class="col-6">
                                    <h3 class="mt-4 pl-1 text-purple text-right">Register</h3>
                                    <h5 class="text-black-50 pl-1 text-right">We can't wait for you to join us!</h5>
                                </div>
                            </div>

                            <form method="post">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="text-center mt-4 mb-n4 font-italic text-purple">Student's Information</h4>
                                        <div class="input-field mt-5">
                                            <i class="far fa-user prefix"></i>
                                            <input id="s_name_input" name="s_name_input" type="text" class="validate w-75" required/>
                                            <label for="s_name_input">Student's Full Name</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="far fa-envelope prefix"></i>
                                            <input id="s_email_input" name="s_email_input" type="email" class="validate w-75" required/>
                                            <label for="s_email_input">Student's Email Address</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="fas fa-calendar-day prefix"></i>
                                            <input id="s_dob_input" name="s_dob_input" type="text" class="datepicker w-75" required/>
                                            <label for="s_dob_input">Date of Birth</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="fas fa-lock prefix"></i>
                                            <input id="s_password_input" name="s_password_input" type="password" class="validate w-75" required/>
                                            <label for="s_password_input">Password</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="fas fa-lock prefix"></i>
                                            <input id="s_cpassword_input" name="s_cpassword_input" type="password" class="validate w-75" required/>
                                            <label for="s_cpassword_input">Confirm Password</label>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <h4 class="text-center mt-4 mb-n4 font-italic text-purple">Parent's Information</h4>
                                        <div class="input-field mt-5">
                                            <i class="far fa-user prefix"></i>
                                            <input id="p_name_input" name="p_name_input" type="text" class="validate w-75" required/>
                                            <label for="p_name_input">Parent's Full Name</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="far fa-envelope prefix"></i>
                                            <input id="p_email_input" name="p_email_input" type="email" class="validate w-75" required/>
                                            <label for="p_email_input">Parent's Email Address</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="fas fa-lock prefix"></i>
                                            <input id="p_password_input" name="p_password_input" type="password" class="validate w-75" required/>
                                            <label for="p_password_input">Password</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="fas fa-lock prefix"></i>
                                            <input id="p_cpassword_input" name="p_cpassword_input" type="password" class="validate w-75" required/>
                                            <label for="p_cpassword_input">Confirm Password</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="fas fa-map-marker-alt prefix"></i>
                                            <input id="p_address_1_input" name="p_address_1_input" type="text" class="validate w-75" required/>
                                            <label for="p_address_1_input">Address - Line 1</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="fas fa-map-marker-alt prefix"></i>
                                            <input id="p_address_2_input" name="p_address_2_input" type="text" class="validate w-75" required/>
                                            <label for="p_address_2_input">Address - Line 2</label>
                                        </div>

                                        <div class="input-field">
                                            <i class="fas fa-map-marker-alt prefix"></i>
                                            <input id="p_address_postcode_input" name="p_address_postcode_input" type="number" min="0" class="validate w-75" required/>
                                            <label for="p_address_postcode_input">Postcode</label>
                                        </div>

                                        <div class="input-field w-75 center-block">
                                            <select>
                                                <option value="" disabled selected>Choose your state</option>
                                                <option value="Johor">Johor</option>
                                                <option value="Kedah">Kedah</option>
                                                <option value="Kelantan">Kelantan</option>
                                                <option value="Malacca">Malacca</option>
                                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                                <option value="Pahang">Pahang</option>
                                                <option value="Penang">Penang</option>
                                                <option value="Perak">Perak</option>
                                                <option value="Perlis">Perlis</option>
                                                <option value="Selangor">Selangor</option>
                                                <option value="Terengganu">Terengganu</option>
                                                <option value="Sabah">Sabah</option>
                                                <option value="Sarawak">Sarawak</option>
                                                <option value="Kuala Lumpur">Kuala Lumpur</option>
                                                <option value="Labuan">Labuan</option>
                                                <option value="Putrajaya">Putrajaya</option>
                                            </select>
                                            <label>State</label>
                                        </div>

                                    </div>
                                </div>

                                <div class="row mb-lg-n5">
                                    <div class="col-2"></div>
                                    <div class="col-8">
                                        <button class="btn btn-outline-purple mt-1 w-100" type="submit" name="btn_register">
                                            Register <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </div>
                                    <div class="col-2"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-1"></div>
                </div>

                <div class="d-flex d-lg-none row mt-n4">
                    <div class="col-2"></div>
                    <div class="col-8">
                        <button class="btn btn-purple w-100" onclick="location.href = 'index.php';">Already have an account? Log in.</button>
                    </div>
                    <div class="col-2"></div>
                </div>

            </div>

            <div class="col-12 d-none d-lg-block col-lg-4 bg-dark min-vh-100 mb-n5">
                <br/><br/><br/><br/><br/><br/><br/> <!-- Lazy code, I know :') -->
                <h1 class="display-4 text-center text-purple mt-5 font-weight-light">Are you an existing user?</h1>
                <h5 class="text-white text-center font-weight-light">You shouldn't be here then :)</h5>

                <div class="row mt-5">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <button class="btn btn-purple w-100" onclick="location.href = 'index.php';">Log in</button>
                    </div>
                    <div class="col-4"></div>
                </div>

            </div>
        </div>


        <?php
            echo $Whiz->getUI()->getBootstrapScripts();
            echo $Whiz->getUI()->getMaterialScripts();

        ?>
        <script>
            $(document).ready(function(){
                $('.datepicker').datepicker({
                    yearRange: 20
                });

                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);

                $('select').formSelect();
            });
        </script>
    </body>

</html>