<?php


class Alerts {

    function sendToastCredentials($title, $message) {
        return "<div aria-live=\"polite\" aria-atomic=\"true\" class=\"toast-inner d-flex justify-content-center align-items-center fixed-bottom\" style=\"min-height: 200px;\">
            <!-- Then put toasts within -->
            <div class=\"toast hide\" role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\" data-autohide='true' data-delay='4000' style='min-width: 90vw'>
                <div class=\"toast-header\">
                    <img src=\"../assets/images/aider-logo-alt.png\" class=\"rounded mr-2\" alt=\"\" style='width: 25px; height: 25px;'>
                    <strong class=\"mr-auto\">" . $title . "</strong>
                </div>
                <div class=\"toast-body\">
                    " . $message . "
                </div>
            </div>
        </div>";
    }

    function sendToastDashboard($title, $message) {
        return "<div aria-live=\"polite\" aria-atomic=\"true\" class=\"toast-inner d-flex justify-content-center align-items-center fixed-bottom mb-5\" style=\"min-height: 200px;\">
            <!-- Then put toasts within -->
            <div class=\"toast hide\" role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\" data-autohide='true' data-delay='4000' style='min-width: 90vw'>
                <div class=\"toast-header\">
                    <img src=\"../assets/images/aider-logo-alt.png\" class=\"rounded mr-2\" alt=\"\" style='width: 25px; height: 25px;'>
                    <strong class=\"mr-auto\">" . $title . "</strong>
                </div>
                <div class=\"toast-body\">
                    " . $message . "
                </div>
            </div>
        </div>";
    }

}