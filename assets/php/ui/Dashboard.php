<?php


class Dashboard {

    function getHeader() {
        echo "<div class=\"container-fluid bg-dark\">
            <div class=\"row\">
                <div class=\"col-12\">
                    <nav class=\"navbar navbar-dark fixed-top bg-dark rounded-bottom\">
                        <a class=\"navbar-brand text-primary m-0\" href=\"home.php\"><i class=\"fas fa-chevron-left mr-3\"></i>" . SITE_NAME . "</a>
                        <a class=\"navbar-brand text-primary m-0\" id=\"user_credit\" href=\"#\">RM 0.00 <i class=\" ml-2 fas fa-wallet\"></i> +</a>
                    </nav>
                </div>
            </div>
        </div>";
    }

    function getFooter() {

    }
}