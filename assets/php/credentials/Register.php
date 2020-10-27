<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/phpmailer/vendor/autoload.php";

class Register {

    function __construct() {}

    function registerCustomer($first_name, $last_name, $email, $phone_num, $credit) {
        // Check Duplicate Emails
        $responseDuplicateEmail = $this->checkDuplicateEmails("CUSTOMER", $email);

        if($responseDuplicateEmail['error']) {
            $response['error'] = true;
            $response['message'] = $responseDuplicateEmail['message'];
        } else {
            $DatabaseHandler = new DatabaseHandler();
            $connection = $DatabaseHandler->getMySQLiConnection();

            $sql = "INSERT INTO aider_user_customer(Name, Email_Address, Password_Hash, Phone_Number, Credit, First_Login) VALUES (?, ?, ?, ?, ?, ?)";

            $statement = $connection->prepare($sql);

            // START: Generate Temporary Password
            $pswd = $this->generatePassword();
            $pswdHash = password_hash($pswd, PASSWORD_DEFAULT);
            // END: Generate Temporary Password

            $name = $first_name . " " . $last_name;

            $first_login = "YES";
            $statement->bind_param("ssssis",$name, $email, $pswdHash, $phone_num, $credit, $first_login);

            if($statement->execute()) {
                // Send Confirmation Email

                $mail = new PHPMailer(true);

                $content = $this->getCustomerConfirmationEmailContent($name, $email, $pswd, "https://aider.my/", GOOGLE_PLAY_STORE_LINK, APPLE_APP_STORE_LINK);

                try {
                    $mail->isSMTP();
                    $mail->Mailer = "smtp";
                    $mail->SMTPAuth   = TRUE;
                    $mail->SMTPSecure = "tls";
                    $mail->Port       = 587;
                    $mail->Host       = "smtp.gmail.com";
                    $mail->Username   = "aider.delivery@gmail.com";
                    $mail->Password   = "Aider@2020";

                    $mail->setFrom('aider.delivery@gmail.com', 'myAider');
                    $mail->addAddress($email);
                    $mail->addReplyTo('info@aider.my', 'myAider');

                    $mail->isHTML(true);
                    $mail->Subject = 'myAider | Confirmation Email for ' . $first_name;
                    $mail->MsgHTML($content);
                    $mail->AltBody = 'Thank you for registering with us. You can log in with your email in the application with the following password: ' . $pswd;

                    if($mail->send()) {
                        $response['error'] = false;
                        $response['email'] = $email;
                    } else {
                        $response['error'] = true;
                        $response['message'] = "Message could not be sent.";
                    }
                } catch (Exception $e) {
                    $response['error'] = true;
                    $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $response['error'] = true;
                $response['message'] = "There was an error while registering you with us.";
            }

            $statement->close();
            $connection->close();
        }

        return $response;
    }

    private function checkDuplicateEmails($type, $email) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($type === "ADMIN") {
            $sql = "SELECT * FROM aider_user_admin WHERE Email_Address = '$email'";
        } elseif($type === "CUSTOMER") {
            $sql = "SELECT * FROM aider_user_customer WHERE Email_Address = '$email'";
        } elseif($type === "MERCHANT") {
            $sql = "SELECT * FROM aider_user_merchant WHERE Email_Address = '$email'";
        } else {
            $sql = "SELECT * FROM aider_user_rider WHERE Email_Address = '$email'";
        }

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = true;
            $response['message'] = "This email has already been used.";
        } else {
            $response['error'] = false;
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function generatePassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890?@!&$%#';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 12; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    function getCustomerConfirmationEmailContent($name, $email, $pass, $WebsiteLink, $AndroidLink, $AppleLink) {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html style=\"width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">
 <head> 
  <meta charset=\"UTF-8\"> 
  <meta content=\"width=device-width, initial-scale=1\" name=\"viewport\"> 
  <meta name=\"x-apple-disable-message-reformatting\"> 
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"> 
  <meta content=\"telephone=no\" name=\"format-detection\">
  <!--[if (mso 16)]>
    <style type=\"text/css\">
    a {text-decoration: none;}
    </style>
    <![endif]--> 
  <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--> 
  <!--[if !mso]>-->
  <link href=\"https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i\" rel=\"stylesheet\"> 
  <!--<![endif]--> 
  <style type=\"text/css\">
@media only screen and (max-width:600px) {p, ul li, ol li, a { font-size:16px!important; line-height:150%!important } h1 { font-size:30px!important; text-align:center; line-height:120%!important } h2 { font-size:26px!important; text-align:center; line-height:120%!important } h3 { font-size:20px!important; text-align:center; line-height:120%!important } h1 a { font-size:30px!important } h2 a { font-size:26px!important } h3 a { font-size:20px!important } .es-menu td a { font-size:14px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:14px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:14px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class=\"gmail-fix\"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:inline-block!important } a.es-button { font-size:20px!important; display:inline-block!important } .es-btn-fw { border-width:10px 0px!important; text-align:center!important } .es-adaptive table, .es-btn-fw, .es-btn-fw-brdr, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0px!important } .es-m-p0r { padding-right:0px!important } .es-m-p0l { padding-left:0px!important } .es-m-p0t { padding-top:0px!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } .es-desk-hidden { display:table-row!important; width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } .es-desk-menu-hidden { display:table-cell!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } }
#outlook a {
	padding:0;
}
.ExternalClass {
	width:100%;
}
.ExternalClass,
.ExternalClass p,
.ExternalClass span,
.ExternalClass font,
.ExternalClass td,
.ExternalClass div {
	line-height:100%;
}
.es-button {
	mso-style-priority:100!important;
	text-decoration:none!important;
}
a[x-apple-data-detectors] {
	color:inherit!important;
	text-decoration:none!important;
	font-size:inherit!important;
	font-family:inherit!important;
	font-weight:inherit!important;
	line-height:inherit!important;
}
.es-desk-hidden {
	display:none;
	float:left;
	overflow:hidden;
	width:0;
	max-height:0;
	line-height:0;
	mso-hide:all;
}
</style> 
 </head> 
 <body style=\"width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"> 
  <div class=\"es-wrapper-color\" style=\"background-color:#FFFFFF;\"> 
   <!--[if gte mso 9]>
			<v:background xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"t\">
				<v:fill type=\"tile\" color=\"#ffffff\"></v:fill>
			</v:background>
		<![endif]--> 
   <table class=\"es-wrapper\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"> 
     <tr style=\"border-collapse:collapse;\"> 
      <td valign=\"top\" style=\"padding:0;Margin:0;\"> 
       <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td align=\"center\" style=\"padding:0;Margin:0;\"> 
           <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"padding:0;Margin:0;\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"600\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:3px;background-color:#FCFCFC;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fcfcfc\" role=\"presentation\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td class=\"es-m-txt-l\" align=\"left\" style=\"padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:25px;\"><h2 style=\"Margin:0;line-height:46px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:38px;font-style:normal;font-weight:normal;color:#74A4CC;text-align:left;\">myAider<span style=\"font-size:39px;\"></span></h2></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td class=\"es-m-txt-l\" align=\"left\" style=\"padding:0;Margin:0;padding-top:10px;padding-left:20px;padding-right:20px;\"><h2 style=\"Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:24px;font-style:normal;font-weight:normal;color:#333333;\">Welcome, " . $name . "!</h2></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td bgcolor=\"#fcfcfc\" align=\"left\" style=\"padding:0;Margin:0;padding-top:10px;padding-left:20px;padding-right:20px;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#333333;text-align:justify;\">We're glad you're here! Thank you for joining us. Here are the login credentials to your account. Remember to change the password upon logging in.</p></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
             <tr style=\"border-collapse:collapse;\"> 
              <td style=\"padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;background-color:#FCFCFC;\" bgcolor=\"#fcfcfc\" align=\"left\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"560\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-color:#EFEFEF;border-style:solid;border-width:1px;border-radius:3px;background-color:#FFFFFF;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" role=\"presentation\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;padding-bottom:15px;padding-top:20px;\"><h3 style=\"Margin:0;line-height:22px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:18px;font-style:normal;font-weight:normal;color:#333333;\">Your account information:</h3></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#74A4CC;\">Email: " . $email . "<br>Temporary Password: " . $pass . "</p></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"Margin:0;padding-left:10px;padding-right:10px;padding-top:20px;padding-bottom:20px;\"><span class=\"es-button-border\" style=\"border-style:solid;border-color:transparent;background:#74a4cc;border-width:0px;display:inline-block;border-radius:3px;width:auto;\"><a href=\"" . $WebsiteLink . "\" class=\"es-button\" target=\"_blank\" style=\"mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:17px;color:#333333;border-style:solid;border-color:#74a4cc;border-width:10px 20px 10px 20px;display:inline-block;background:#74a4cc;border-radius:3px;font-weight:normal;font-style:normal;line-height:20px;width:auto;text-align:center;\">Log In Now</a></span></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table> 
       <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td align=\"center\" style=\"padding:0;Margin:0;\"> 
           <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FCFCFC;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fcfcfc\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"Margin:0;padding-left:20px;padding-right:20px;padding-bottom:25px;padding-top:40px;\"> 
               <!--[if mso]><table width=\"560\" cellpadding=\"0\" 
                            cellspacing=\"0\"><tr><td width=\"274\" valign=\"top\"><![endif]--> 
               <table class=\"es-left\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td class=\"es-m-p0r es-m-p20b\" width=\"254\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"left\" style=\"padding:0;Margin:0;\"><h3 style=\"Margin:0;line-height:20px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:17px;font-style:normal;font-weight:normal;color:#333333;text-align:justify;\">Download the app and start your experience with us.</h3></td> 
                     </tr> 
                   </table></td> 
                  <td class=\"es-hidden\" width=\"20\" style=\"padding:0;Margin:0;\"></td> 
                 </tr> 
               </table> 
               <!--[if mso]></td><td width=\"133\" valign=\"top\"><![endif]--> 
               <table class=\"es-left\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td class=\"es-m-p20b\" width=\"133\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;font-size:0;\"><a target=\"_blank\" href=\"" . $AppleLink . "\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:14px;text-decoration:none;color:#F6A1B4;\"><img src=\"https://hhefbb.stripocdn.email/content/guids/CABINET_e48ed8a1cdc6a86a71047ec89b3eabf6/images/92051534250512328.png\" alt=\"App Store\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;\" class=\"adapt-img\" title=\"App Store\" width=\"133\"></a></td> 
                     </tr> 
                   </table></td>
                 </tr> 
               </table> 
               <!--[if mso]></td><td width=\"20\"></td><td width=\"133\" valign=\"top\"><![endif]--> 
               <table class=\"es-right\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"133\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;font-size:0;\"><a target=\"_blank\" href=\"" . $AndroidLink . "\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:14px;text-decoration:none;color:#F6A1B4;\"><img class=\"adapt-img\" src=\"https://hhefbb.stripocdn.email/content/guids/CABINET_e48ed8a1cdc6a86a71047ec89b3eabf6/images/82871534250557673.png\" alt=\"Google Play\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;\" title=\"Google Play\" width=\"133\"></a></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table> 
               <!--[if mso]></td></tr></table><![endif]--></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table> 
       <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td align=\"center\" style=\"padding:0;Margin:0;\"> 
           <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"padding:0;Margin:0;\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"600\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFF4F7;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fff4f7\" role=\"presentation\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td bgcolor=\"#93c9fc\" align=\"center\" style=\"Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;font-size:0;\"> 
                       <table width=\"100%\" height=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                         <tr style=\"border-collapse:collapse;\"> 
                          <td style=\"padding:0;Margin:0px;border-bottom:1px solid #93C9FC;background:none 0% 0% repeat scroll#FFFFFF;height:1px;width:100%;margin:0px;\"></td> 
                         </tr> 
                       </table></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table> 
       <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-footer\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td style=\"padding:0;Margin:0;background-color:#666666;\" bgcolor=\"#666666\" align=\"center\"> 
           <table class=\"es-footer-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#666666;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#666666\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px;\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"560\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td esdev-links-color=\"#999999\" align=\"center\" style=\"padding:0;Margin:0;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#FFFFFF;\">You are receiving this email to confirm your registration with us at myAider</p></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" class=\"es-m-txt-c\" style=\"Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;font-size:0;\"> 
                       <table border=\"0\" width=\"20%\" height=\"100%\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                         <tr style=\"border-collapse:collapse;\"> 
                          <td style=\"padding:0;Margin:0px;border-bottom:1px solid #CCCCCC;background:none;height:1px;width:100%;margin:0px;\"></td> 
                         </tr> 
                       </table></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td esdev-links-color=\"#999999\" align=\"center\" style=\"padding:0;Margin:0;padding-bottom:5px;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#FFFFFF;\">Copyright &copy; " . date("Y") . " myAider | Powered by iWeb Solutions.</p></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table></td> 
     </tr> 
   </table> 
  </div>  
 </body>
</html>";
    }

    function getRiderConfirmationEmailContent($name, $email, $pass, $WebsiteLink, $AndroidLink, $AppleLink) {
        return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html style=\"width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\">
 <head> 
  <meta charset=\"UTF-8\"> 
  <meta content=\"width=device-width, initial-scale=1\" name=\"viewport\"> 
  <meta name=\"x-apple-disable-message-reformatting\"> 
  <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\"> 
  <meta content=\"telephone=no\" name=\"format-detection\">
  <!--[if (mso 16)]>
    <style type=\"text/css\">
    a {text-decoration: none;}
    </style>
    <![endif]--> 
  <!--[if gte mso 9]><style>sup { font-size: 100% !important; }</style><![endif]--> 
  <!--[if !mso]>-->
  <link href=\"https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i\" rel=\"stylesheet\"> 
  <!--<![endif]--> 
  <style type=\"text/css\">
@media only screen and (max-width:600px) {p, ul li, ol li, a { font-size:16px!important; line-height:150%!important } h1 { font-size:30px!important; text-align:center; line-height:120%!important } h2 { font-size:26px!important; text-align:center; line-height:120%!important } h3 { font-size:20px!important; text-align:center; line-height:120%!important } h1 a { font-size:30px!important } h2 a { font-size:26px!important } h3 a { font-size:20px!important } .es-menu td a { font-size:14px!important } .es-header-body p, .es-header-body ul li, .es-header-body ol li, .es-header-body a { font-size:14px!important } .es-footer-body p, .es-footer-body ul li, .es-footer-body ol li, .es-footer-body a { font-size:14px!important } .es-infoblock p, .es-infoblock ul li, .es-infoblock ol li, .es-infoblock a { font-size:12px!important } *[class=\"gmail-fix\"] { display:none!important } .es-m-txt-c, .es-m-txt-c h1, .es-m-txt-c h2, .es-m-txt-c h3 { text-align:center!important } .es-m-txt-r, .es-m-txt-r h1, .es-m-txt-r h2, .es-m-txt-r h3 { text-align:right!important } .es-m-txt-l, .es-m-txt-l h1, .es-m-txt-l h2, .es-m-txt-l h3 { text-align:left!important } .es-m-txt-r img, .es-m-txt-c img, .es-m-txt-l img { display:inline!important } .es-button-border { display:inline-block!important } a.es-button { font-size:20px!important; display:inline-block!important } .es-btn-fw { border-width:10px 0px!important; text-align:center!important } .es-adaptive table, .es-btn-fw, .es-btn-fw-brdr, .es-left, .es-right { width:100%!important } .es-content table, .es-header table, .es-footer table, .es-content, .es-footer, .es-header { width:100%!important; max-width:600px!important } .es-adapt-td { display:block!important; width:100%!important } .adapt-img { width:100%!important; height:auto!important } .es-m-p0 { padding:0px!important } .es-m-p0r { padding-right:0px!important } .es-m-p0l { padding-left:0px!important } .es-m-p0t { padding-top:0px!important } .es-m-p0b { padding-bottom:0!important } .es-m-p20b { padding-bottom:20px!important } .es-mobile-hidden, .es-hidden { display:none!important } .es-desk-hidden { display:table-row!important; width:auto!important; overflow:visible!important; float:none!important; max-height:inherit!important; line-height:inherit!important } .es-desk-menu-hidden { display:table-cell!important } table.es-table-not-adapt, .esd-block-html table { width:auto!important } table.es-social { display:inline-block!important } table.es-social td { display:inline-block!important } }
#outlook a {
	padding:0;
}
.ExternalClass {
	width:100%;
}
.ExternalClass,
.ExternalClass p,
.ExternalClass span,
.ExternalClass font,
.ExternalClass td,
.ExternalClass div {
	line-height:100%;
}
.es-button {
	mso-style-priority:100!important;
	text-decoration:none!important;
}
a[x-apple-data-detectors] {
	color:inherit!important;
	text-decoration:none!important;
	font-size:inherit!important;
	font-family:inherit!important;
	font-weight:inherit!important;
	line-height:inherit!important;
}
.es-desk-hidden {
	display:none;
	float:left;
	overflow:hidden;
	width:0;
	max-height:0;
	line-height:0;
	mso-hide:all;
}
</style> 
 </head> 
 <body style=\"width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0;\"> 
  <div class=\"es-wrapper-color\" style=\"background-color:#FFFFFF;\"> 
   <!--[if gte mso 9]>
			<v:background xmlns:v=\"urn:schemas-microsoft-com:vml\" fill=\"t\">
				<v:fill type=\"tile\" color=\"#ffffff\"></v:fill>
			</v:background>
		<![endif]--> 
   <table class=\"es-wrapper\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\"> 
     <tr style=\"border-collapse:collapse;\"> 
      <td valign=\"top\" style=\"padding:0;Margin:0;\"> 
       <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td align=\"center\" style=\"padding:0;Margin:0;\"> 
           <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"padding:0;Margin:0;\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"600\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:3px;background-color:#FCFCFC;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fcfcfc\" role=\"presentation\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td class=\"es-m-txt-l\" align=\"left\" style=\"padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:25px;\"><h2 style=\"Margin:0;line-height:46px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:38px;font-style:normal;font-weight:normal;color:#74A4CC;text-align:left;\">myAider<span style=\"font-size:39px;\"></span></h2></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td class=\"es-m-txt-l\" align=\"left\" style=\"padding:0;Margin:0;padding-top:10px;padding-left:20px;padding-right:20px;\"><h2 style=\"Margin:0;line-height:29px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:24px;font-style:normal;font-weight:normal;color:#333333;\">Welcome, " . $name . "!</h2></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td bgcolor=\"#fcfcfc\" align=\"left\" style=\"padding:0;Margin:0;padding-top:10px;padding-left:20px;padding-right:20px;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#333333;text-align:justify;\">We're glad to welcome you onboard! Thank you for joining us. Here are the login credentials to your account. Remember to change the password upon logging in.</p></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
             <tr style=\"border-collapse:collapse;\"> 
              <td style=\"padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-top:30px;background-color:#FCFCFC;\" bgcolor=\"#fcfcfc\" align=\"left\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"560\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-color:#EFEFEF;border-style:solid;border-width:1px;border-radius:3px;background-color:#FFFFFF;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" role=\"presentation\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;padding-bottom:15px;padding-top:20px;\"><h3 style=\"Margin:0;line-height:22px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:18px;font-style:normal;font-weight:normal;color:#333333;\">Your account information:</h3></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:16px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:24px;color:#74A4CC;\">Email: " . $email . "<br>Temporary Password: " . $pass . "</p></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"Margin:0;padding-left:10px;padding-right:10px;padding-top:20px;padding-bottom:20px;\"><span class=\"es-button-border\" style=\"border-style:solid;border-color:transparent;background:#74a4cc;border-width:0px;display:inline-block;border-radius:3px;width:auto;\"><a href=\"" . $WebsiteLink . "\" class=\"es-button\" target=\"_blank\" style=\"mso-style-priority:100 !important;text-decoration:none;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:17px;color:#333333;border-style:solid;border-color:#74a4cc;border-width:10px 20px 10px 20px;display:inline-block;background:#74a4cc;border-radius:3px;font-weight:normal;font-style:normal;line-height:20px;width:auto;text-align:center;\">Log In Now</a></span></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table> 
       <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td align=\"center\" style=\"padding:0;Margin:0;\"> 
           <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FCFCFC;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fcfcfc\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"Margin:0;padding-left:20px;padding-right:20px;padding-bottom:25px;padding-top:40px;\"> 
               <!--[if mso]><table width=\"560\" cellpadding=\"0\" 
                            cellspacing=\"0\"><tr><td width=\"274\" valign=\"top\"><![endif]--> 
               <table class=\"es-left\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td class=\"es-m-p0r es-m-p20b\" width=\"254\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"left\" style=\"padding:0;Margin:0;\"><h3 style=\"Margin:0;line-height:20px;mso-line-height-rule:exactly;font-family:roboto, 'helvetica neue', helvetica, arial, sans-serif;font-size:17px;font-style:normal;font-weight:normal;color:#333333;text-align:justify;\">Download the app and start your experience with us.</h3></td> 
                     </tr> 
                   </table></td> 
                  <td class=\"es-hidden\" width=\"20\" style=\"padding:0;Margin:0;\"></td> 
                 </tr> 
               </table> 
               <!--[if mso]></td><td width=\"133\" valign=\"top\"><![endif]--> 
               <table class=\"es-left\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td class=\"es-m-p20b\" width=\"133\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;font-size:0;\"><a target=\"_blank\" href=\"" . $AppleLink . "\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:14px;text-decoration:none;color:#F6A1B4;\"><img src=\"https://hhefbb.stripocdn.email/content/guids/CABINET_e48ed8a1cdc6a86a71047ec89b3eabf6/images/92051534250512328.png\" alt=\"App Store\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;\" class=\"adapt-img\" title=\"App Store\" width=\"133\"></a></td> 
                     </tr> 
                   </table></td>
                 </tr> 
               </table> 
               <!--[if mso]></td><td width=\"20\"></td><td width=\"133\" valign=\"top\"><![endif]--> 
               <table class=\"es-right\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:right;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"133\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" style=\"padding:0;Margin:0;font-size:0;\"><a target=\"_blank\" href=\"" . $AndroidLink . "\" style=\"-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;font-size:14px;text-decoration:none;color:#F6A1B4;\"><img class=\"adapt-img\" src=\"https://hhefbb.stripocdn.email/content/guids/CABINET_e48ed8a1cdc6a86a71047ec89b3eabf6/images/82871534250557673.png\" alt=\"Google Play\" style=\"display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;\" title=\"Google Play\" width=\"133\"></a></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table> 
               <!--[if mso]></td></tr></table><![endif]--></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table> 
       <table class=\"es-content\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td align=\"center\" style=\"padding:0;Margin:0;\"> 
           <table class=\"es-content-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#ffffff\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"padding:0;Margin:0;\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"600\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFF4F7;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#fff4f7\" role=\"presentation\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td bgcolor=\"#93c9fc\" align=\"center\" style=\"Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;font-size:0;\"> 
                       <table width=\"100%\" height=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                         <tr style=\"border-collapse:collapse;\"> 
                          <td style=\"padding:0;Margin:0px;border-bottom:1px solid #93C9FC;background:none 0% 0% repeat scroll#FFFFFF;height:1px;width:100%;margin:0px;\"></td> 
                         </tr> 
                       </table></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table> 
       <table cellpadding=\"0\" cellspacing=\"0\" class=\"es-footer\" align=\"center\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;\"> 
         <tr style=\"border-collapse:collapse;\"> 
          <td style=\"padding:0;Margin:0;background-color:#666666;\" bgcolor=\"#666666\" align=\"center\"> 
           <table class=\"es-footer-body\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#666666;\" width=\"600\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#666666\" align=\"center\"> 
             <tr style=\"border-collapse:collapse;\"> 
              <td align=\"left\" style=\"Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px;\"> 
               <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                 <tr style=\"border-collapse:collapse;\"> 
                  <td width=\"560\" valign=\"top\" align=\"center\" style=\"padding:0;Margin:0;\"> 
                   <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td esdev-links-color=\"#999999\" align=\"center\" style=\"padding:0;Margin:0;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#FFFFFF;\">You are receiving this email to confirm your registration with us at myAider</p></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td align=\"center\" class=\"es-m-txt-c\" style=\"Margin:0;padding-top:5px;padding-bottom:5px;padding-left:20px;padding-right:20px;font-size:0;\"> 
                       <table border=\"0\" width=\"20%\" height=\"100%\" cellpadding=\"0\" cellspacing=\"0\" role=\"presentation\" style=\"mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;\"> 
                         <tr style=\"border-collapse:collapse;\"> 
                          <td style=\"padding:0;Margin:0px;border-bottom:1px solid #CCCCCC;background:none;height:1px;width:100%;margin:0px;\"></td> 
                         </tr> 
                       </table></td> 
                     </tr> 
                     <tr style=\"border-collapse:collapse;\"> 
                      <td esdev-links-color=\"#999999\" align=\"center\" style=\"padding:0;Margin:0;padding-bottom:5px;\"><p style=\"Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;line-height:21px;color:#FFFFFF;\">Copyright &copy; " . date("Y") . " myAider | Powered by iWeb Solutions.</p></td> 
                     </tr> 
                   </table></td> 
                 </tr> 
               </table></td> 
             </tr> 
           </table></td> 
         </tr> 
       </table></td> 
     </tr> 
   </table> 
  </div>  
 </body>
</html>";
    }
}