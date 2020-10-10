<?php


class Dashboard {

    function getHeader() {
        echo "<div class=\"container-fluid bg-dark\">
            <div class=\"row\">
                <div class=\"col-12\">
                    <nav class=\"navbar navbar-dark fixed-top bg-dark rounded-bottom\">
                        <a class=\"navbar-brand text-primary m-0\" href=\"index.php\"><i class=\"fas fa-chevron-left mr-3\"></i>" . SITE_NAME . "</a>
                        <a class=\"navbar-brand text-primary m-0\" id=\"user_credit\" href=\"#\">RM 0.00 <i class=\" ml-2 fas fa-wallet\"></i> +</a>
                    </nav>
                </div>
            </div>
        </div>";
    }

    function getDashboardHeader() {
        echo "<!-- Header -->
            <div style=\"top: 0 !important;\">
                <img src=\"../assets/images/aider-logo-alt.png\" class=\"d-block ml-auto mr-auto\" style=\"height: 100px; width: auto;\"/>
            </div>";
    }

    function getBottomNavigation($id) {

        $active[] = array();
        $active[1] = "text-black-50";
        $active[2] = "text-black-50";
        $active[3] = "text-black-50";
        $active[$id] = "";

        echo "<div class='bg-white w-100 fixed-bottom rounded border-top' style='border-color: rgba(0, 0, 0, 0.5);'>
        <div class=\"container bg-white pt-1\">
            <div class=\"row\">
                <div class=\"col-4 text-center\">
                    <a class='text-decoration-none " . $active[1] . "' href=\"home.php\">
                        <i class=\"fas fa-home\"></i>
                        <h6 style=\"font-size: 9pt;\">Home</h6>
                    </a>

                </div>

                <div class=\"col-4 text-center\">
                    <a class='text-decoration-none " . $active[2] . "' href=\"payment.php\">
                        <i class=\"fas fa-wallet\"></i>
                        <h6 style=\"font-size: 9pt;\">Payment</h6>
                    </a>

                </div>

                <div class=\"col-4 text-center\">
                    <a class='text-decoration-none " . $active[3] . "' href=\"account.php\">
                        <i class=\"far fa-user-circle\"></i>
                        <h6 style=\"font-size: 9pt;\">Account</h6>
                    </a>
                </div>
            </div>
        </div>
    </div>";
    }

    function getAdminBottomNavigation($id) {

        $active[] = array();
        $active[1] = "text-black-50";
        $active[2] = "text-black-50";
        $active[3] = "text-black-50";
        $active[$id] = "";

        echo "<div class='bg-white w-100 fixed-bottom rounded border-top' style='border-color: rgba(0, 0, 0, 0.5);'>
        <div class=\"container bg-white pt-1\">
            <div class=\"row\">
                <div class=\"col-4 text-center\">
                    <a class='text-decoration-none " . $active[1] . "' href=\"home.php\">
                        <i class=\"fas fa-home\"></i>
                        <h6 style=\"font-size: 9pt;\">Home</h6>
                    </a>

                </div>

                <div class=\"col-4 text-center\">
                    <a class='text-decoration-none " . $active[2] . "' href=\"waitinglist.php\">
                        <i class=\"fas fa-clipboard-list\"></i>
                        <h6 style=\"font-size: 9pt;\">Waiting List</h6>
                    </a>

                </div>

                <div class=\"col-4 text-center\">
                    <a class='text-decoration-none " . $active[3] . "' href=\"settings.php\">
                        <i class=\"fas fa-cog\"></i>
                        <h6 style=\"font-size: 9pt;\">Settings</h6>
                    </a>
                </div>
            </div>
        </div>
    </div>";
    }

    function getFirstTimePasswordChangeModal() {
        echo "<div class=\"modal fade bd-example-modal-lg\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myLargeModalLabel\" aria-hidden=\"true\">
            <div class=\"modal-dialog modal-lg\">
                <div class=\"modal-content\">
                    <div class=\"modal-header\">
                        <h5 class=\"modal-title\" id=\"changePasswordModalLabel\">Set a new password.</h5>
                    </div>
                    <div class=\"modal-body\">
                        <form method=\"post\">
                            <div class=\"form-group\">
                                <label for=\"pass\" class=\"col-form-label\">Password:</label>
                                <input type=\"password\" class=\"form-control\" name=\"pswd\" id=\"pass\">
                            </div>
                            <div class=\"form-group\">
                                <label for=\"confirm-pass\" class=\"col-form-label\">Confirm Password:</label>
                                <input type=\"password\" class=\"form-control\" name=\"confirm-pswd\" id=\"confirm-pass\">
                            </div>
                        </form>
                    </div>
                    <div class=\"modal-footer\">
                        <button type=\"button\" class=\"btn btn-primary\" id=\"change-pass-btn\">Change Password</button>
                    </div>
                </div>
                <!-- START: Toast Messages Area -->
                <div class=\"toast-container-modal\" style=\"z-index: 9999;\">

                </div>
                <!-- END: Toast Messages Area -->
            </div>
        </div>";
    }
}