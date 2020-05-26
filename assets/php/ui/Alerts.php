<?php


class Alerts {

    function sendSuccessMessage($message) {
        return "<div class=\"row ml-md-5\">
                            <div class=\"ml-lg-5 ml-md-5 col-12\">
                                <div style=\"z-index: 9999;\" class=\"mt-5 offset-sm-2 offset-md-1 offset-lg-1 position-fixed alert alert-success alert-dismissible fade show\" role=\"alert\">
                                    <strong>Success! </strong>" . $message . "
                                    <span type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                                        <span aria-hidden=\"true\">&times;</span>
                                    </span>
                                </div>
                            </div>
                        </div>";
    }

    function sendErrorMessage($message) {
        return "<div class=\"row ml-md-5\">
                            <div class=\"ml-lg-5 ml-md-5 col-12\">
                                <div style=\"z-index: 9999;\" class=\"mt-5 offset-sm-2 offset-md-1 offset-lg-1 position-fixed alert alert-danger alert-dismissible fade show\" role=\"alert\">
                                    <strong>Something went wrong! </strong>" . $message . "
                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                                        <span aria-hidden=\"true\">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>";
    }

    function sendToastDashboard($title, $message) {
        return "<div aria-live=\"polite\" aria-atomic=\"true\" class=\"d-flex justify-content-center align-items-center fixed-bottom mb-5\" style=\"min-height: 200px;\">
            <!-- Then put toasts within -->
            <div class=\"toast hide\" role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\" style='min-width: 90vw'>
                <div class=\"toast-header\">
                    <img src=\"../assets/images/aider-logo.png\" class=\"rounded mr-2\" alt=\"\" style='width: 25px; height: 25px;'>
                    <strong class=\"mr-auto\">" . $title . "</strong>
                </div>
                <div class=\"toast-body\">
                    " . $message . "
                </div>
            </div>
        </div>";
    }

}