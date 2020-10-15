<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no, user-scalable=no">

        <title><?php echo SITE_NAME; ?> | Sign Up</title>

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
                        <h5 class='modal-title' id='tacModalLabel'>Terms & Conditions</h5>
                    </div>
                    <div class='modal-body'>
                        <p><span class="font-weight-bold">For Aider drive customer T&C:</span><br />Important note: Before using the service provided by Aider driver information service platform, you must read the rules and attachments (hereinafter referred to as "the rules") carefully, and fully understand the contents of each clause, especially those concerning Exemption or limitation of liability. When you enter the Aider drive information service platform and accept or use the services of the platform, you are aware of and agree to all the contents of the rules and the attachments. If you do not agree with any of the provisions in the rules, you should stop using them immediately.</p>
                        <p>You must have full behavioral competence to use platform services. If you do not have the above-mentioned capacity, you and your guardian and agent shall bear all legal liabilities caused by it.</p>
                        <p>Our services: on our platform, users can publish and (or) obtain information on the demand of car valet driving, as well as the order record, fee settlement, experience evaluation, and other activities related to Valet driving. However, the platform does not actually provide Valet driving service, does not act as an agent for any user, and only acts as an intermediary between users. The use or provision of valet service between users is subject to the agreement signed (to be) signed by users. The platform is not a party to such agreements.<br />Our users: the natural person or legal person with full capacity to act according to the law is the platform users who use or accept the platform services, including the service users and service providers.</p>
                        <p><span class="font-weight-bold">1. User registration</span></p>
                        <p>1.1 self-registration: before using or accepting the platform service for the first time, the user of the driving service shall independently complete the registration according to the prompt information of the platform.<br />1.2 assistance in registration: after the user of valet service enters the platform for the first time or before using the platform service, the platform will assist the user to complete the registration automatically according to the information provided by the user.<br />1.3 audit and registration: the agent driving service provider shall complete the training, driving technology audit and other processes according to the requirements, and generate a special account before completing the registration.<br />1.4 after registration, users can enjoy the services provided by the platform, and at the same time, they should abide by and perform the obligations of the rules. Users who accept or use the valet service in other ways recognized by the platform are deemed to have completed the registration.</p>
                        <p><span class="font-weight-bold">2. Service items</span></p>
                        <p>2.1 information services<br />2.1.1 the platform provides users with information push service to send and accept the request (order receiving), and the information recording service for users to reach and fulfill the valet service agreement.<br />2.1.2 after obtaining information through the platform, the driver service provider shall pay the information service fee to the platform.<br />2.2 settlement services<br />2.2.1 the platform provides services for the settlement of agent driving service fees among users. After the end of the valet service, the user of the valet service shall pay the valet service fee to the valet service provider according to the time and mileage data recorded in the platform order and the charging standard announced by the platform. The platform will update the fee standard according to market demand or user feedback, and users should pay attention to check the latest published charging standard of the platform.<br />2.2.2 the platform can set up a pre-deposit account for users, and pay or transfer relevant fees (including information service fee, driving service fee and other fees or liquidated damages) according to the authorization of users.<br />2.3 evaluation service<br />2.3.1 users can make an evaluation or put forward opinions on the service behavior of driving agent through the platform, and the evaluation content shall be objective and true, and shall not contain abusive, defamatory and legal prohibited information, and shall not violate the rules of the platform. Otherwise, the platform has the right to take relevant measures, including but not limited to delete the evaluation, restrict or prohibit the use, claim compensation for losses, and investigate other responsibilities.<br />2.3.2 users shall not help others to improve their evaluation in improper ways and shall not threaten or blackmail other users.<br />2.3.3 the platform can conduct a comprehensive evaluation on users according to the evaluation statistics, and the specific evaluation method shall be formulated separately by the platform.<br />2.4 mediation services<br />2.4.1 if a user has a dispute in the process of using the platform and in the process of Valet driving service, and chooses to use the platform dispute mediation service, it means that the platform is recognized as an independent third party and the mediation decision made according to the facts and rules of the platform.<br />2.4.2 The user shall understand and agree that in the dispute mediation service, the platform personnel are not professional referees, and can only judge the user's certificates with the cognition of ordinary people, and the platform shall be exempted from liability for the mediation results.</p>
                        <p><span class="font-weight-bold">3. User obligations</span><br />3.1 when using or accepting the platform services, users shall abide by the order of the platform and undertake the following obligations:<br />3.1.1 the user shall provide the platform with true, legal, and effective information. If the information provided is false, wrong, inaccurate, and incomplete, the platform will not bear any responsibility for the service and information deviation and error; any loss caused thereby shall be borne by the user.<br />3.1.2 users should keep their own accounts properly and should not use others' accounts without authorization.<br />3.1.3 the user shall not use the platform to harass, obstruct others or engage in any illegal or infringement behavior (including but not limited to malicious order, order receiving, multiple sales orders affecting the platform, or other users).<br />3.1.4 the user shall not affect the normal operation of the platform, and shall not conduct any behavior that endangers the platform services or affects the platform data.<br />3.1.5 when the platform makes a reasonable request, the user shall provide any identification or vehicle, event proof materials.<br />3.1.6 in the process of using the platform services, the User shall bear all the taxable fees, as well as all the expenses of hardware, other application software, network communication services, and other aspects.<br />3.2 if the user violates the platform management or the platform rules, the platform has the right to take relevant measures, including but not limited to: information deletion, shielding processing, restricted use, service termination, account closure or cancellation, etc. In case of platform loss, users shall also compensate for indirect economic losses of the platform, including but not limited to direct economic loss, goodwill loss, advance payment or compensation, settlement or mediation payment, lawyer's fees, travel expenses, litigation costs, administrative fines, and other indirect economic losses.<br />3.3 users agree that the platform sends commercial short messages to them to inform, remind, and confirm the relevant contents and preferential information used by the platform.</p>
                        <p><span class="font-weight-bold">4. Responsibilities of all parties</span><br />4.1 except for the responsibilities specified in the rules, the platform shall not be liable for the disputes and losses arising from the valet service.<br />4.2 Our Aider&rsquo;s driver are all qualified and professional driver that has Malaysia&rsquo;s driving license had driving experienced for more than 2 years, so if there&rsquo;s any accident happened during the driving process, the car user shall claim third party liability insurance or other insurance of the vehicle of the agent driving service user shall be used to compensate for the relevant personal and property losses, and if there&rsquo;s any prohibited goods that are (drugs, weed and more) against the Malaysia law.<br />4.3 in view of the particularity of Internet services and the platform information is provided by users, the platform can not review the authenticity and accuracy of information one by one, so users should make a prudent judgment and make an independent decision. The platform shall not be liable for any damage caused by this unless it is intentionally caused by the platform.<br />4.4 the agent driving information service provided by the platform is subject to the user's use. The platform has the right to change, interrupt, or terminate part or all of the service at any time without prior notice, and does not guarantee that the service can meet the user's requirements.<br />4.5 if users fail to call or receive orders through the channels and methods recognized by the platform, they will not be able to enjoy any rights in the rules. The platform will not be responsible for any disputes and losses arising therefrom.<br />5、 Intellectual property<br />5.1 the ownership of all contents contained in the platform, including but not limited to text, graphics, logo, and software, belongs to MYAIDER SERVICE SDN BHD, which is protected by laws and regulations such as intellectual property rights. You shall not infringe upon the above-mentioned rights of the owner in any form in the process of using the service.<br />5.2 without the permission of the platform, you are not allowed to copy or send any platform content, modify the platform related procedures, or create derivative products or competitive products accordingly.</p>
                        <p><span class="font-weight-bold">6. Rule text, notice, change, and termination</span><br />All the 6.1 platform implementation rules, announcements, notices, products or process instructions issued through the official website, mobile phone app, and WeChat official account are regarded as an integral part of the rules and have the same legal effect as the rules.<br />6.2 these Rules shall come into force as soon as they are published. The platform can publish or notify the release, modification, addition, and abolishment of the contents of the rules through the official website, mobile app, WeChat, SMS, etc., or restrict or terminate the use of some functions. Users should pay attention to the public information on the platform at any time. Once the content of the rules is published or notified, it will take effect instead of the content of the original rules. If the user disagrees with the content published or changed, the user shall immediately stop using the relevant services of the platform; if the user continues to use the platform service, it shall be deemed that he agrees to publish or change the content.<br />6.3 the platform has the right to restrict or terminate the provision of services to users in the following circumstances:<br />6.3.1 the mobile phone number provided does not exist or cannot receive information, and there is no other way to contact.<br />6.3.2 when the rules are changed, express and inform the platform that they are not willing to accept the new changes.<br />6.3.3 those who have not used the platform service for 180 consecutive days.<br />6.3.4 illegal or breach of contract.<br />6.3.5 other service restriction or termination measures are taken to maintain the operation order of the platform.<br />6.4 the platform still enjoys the following rights after restricting or terminating the service provision:<br />6.4.1 continue to save the user's registration information and use platform service information.<br />6.4.2 claims legal liability for the user's illegal or breach of contract.<br />7、 Application of law and jurisdiction<br />7.1 the validity, interpretation, modification, implementation, and dispute resolution of the rules shall be governed by the laws of the mainland of the people's Republic of Malaysia. If there is no relevant law, general business practices and/or industry practices shall be referred to.<br />7.2 disputes arising from the use of platform services shall be handled through full and friendly negotiation; if the negotiation fails, the people's Court of the place where the platform operator is located, namely MYAIDER SERVICE SDN BHD, shall be the jurisdiction court.<br />7.3 the invalidity of any provision of the rules shall not affect the validity of the remaining provisions of the rules. MYAIDER SERVICE SDN BHD the platform operator, has the final right to interpret the rules.</p>
                        <p>Related definitions:<br />Telecommunication technology. Including official homepage, phone application, customer service, facebook account, instagram account and official email.<br />Phone application: Only It refers to the client developed by MYAIDER SERVICE SDN BHD.<br />Valet driving service: refers to the paid driving service provided directly by the service provider to the user.<br />Account number: refers to the user's account for entering and using the platform and receiving the platform services.<br />Registration number: refers to the number used by the user to log in to the account.<br />Pre deposit account: it refers to that the user sets up an account to deposit certain fees, and authorizes the platform to settle the account and deduct the information service fee or service fee for the driving agent. The user of Valet driving service can set up a pre deposit account, and the user of valet service provider shall set up a pre deposit account.<br />Order: refers to the electronic order recording the specific information of the user and provider of the valet service through the platform.<br />Order calling: refers to that the user of the valet service sends an offer for the valet service to the driver service provider through the way recognized by the platform.<br />Order receiving: refers to the commitment made by the service provider to provide Valet driving service through the way recognized by the platform, that is, to accept the order from the user of the valet service.</p>
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-primary d-block w-100' id='tac-accept-btn'>Accept</button>
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
                        <img src="../assets/images/aider-d-logo.png" class="d-block ml-auto mr-auto p-3" style="width: auto; height: 180px;" />
                    </div>
                </div>
                <h3 class="text-center font-weight-bold mt-5 text-primary" style="font-family: 'Barlow', sans-serif;">SIGN UP</h3>

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
                            <div class="input-group mt-3 d-inline-block  text-center">
                                <input type="checkbox" id="checkbox-tac" style="width: 20px; height: 20px; vertical-align: middle;" class="mr-1" required/>
                                <label for="checkbox-tac">
                                    I agree to the <a class="text-decoration-none text-primary" data-toggle="modal" data-target="#tac-modal">terms and conditions</a>
                                </label>
                            </div>
                            <button name="btn-reg" id="btn-reg" class="btn btn-outline-primary btn-block mt-5">Sign Up</button>
                        </form>

                        <h6 class="text-center mt-3">Already have an account? <a href="signin.php" class="text-primary">Sign in.</a></h6>
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

                    $response = $Aider->getCredentials()->getRegister()->registerCustomer($first_name, $last_name, $email, $phone_num, 0);

                    if(!$response['error']) {
                        echo $Aider->getAlerts()->sendToastCredentials("You've been registered successfully.", "A confirmation email has been sent to " . $response['email']);
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

                $('#tac-accept-btn').click(function() {
                    $('#tac-modal').modal('toggle');
                });
            });
        </script>
    </body>

</html>