<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php echo SITE_NAME; ?> | Food</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="bg-light">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark min-vw-100 nav-pills nav-fill">
            <a class="navbar-brand text-primary" href="#"><?php echo SITE_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-item nav-link active d-inline-block" href="#">Food</a>
                    <a class="nav-item nav-link d-inline-block" href="parcel.php">Parcel</a>
                    <a class="nav-item nav-link d-inline-block" href="#">Wallet</a>
                    <a class="nav-item nav-link d-inline-block" href="#">Transactions</a>
                </div>
                <div class="navbar-nav ml-auto">
                    <a class="nav-item nav-link d-inline-block mr-lg-2 ml-lg-2" href="#">
                        <table class="table table-bordered rounded m-0 p-0">
                            <tr>
                                <td class="p-0 pr-3 pl-3 text-white" id="user_name">Name</td>
                                <td class="p-0 pr-3 pl-3 text-white" id="user_credit">RM 0</td>
                            </tr>
                        </table>
                    </a>
                    <a class="nav-item nav-link d-inline-block fa-lg mr-lg-2 ml-lg-2" href="#">
                        <i class="fas fa-cog"></i>
                    </a>
                    <a class="nav-item nav-link d-inline-block fa-lg mr-lg-2 ml-lg-2" href="#">
                        <i class="far fa-question-circle"></i>
                    </a>
                </div>
            </div>
        </nav>



        <!-- Main Content -->
        <div class="container">
            <div class="row">
                <div class="col col-lg-4"></div>
                <div class="col-12 col-lg-4 bg-white rounded mt-5">
                    <div class="mb-5">
                        <a class="text-decoration-none" href="">
                            <button type="button" class="btn btn-primary d-block ml-auto mr-auto mt-5">Aider Food</button>
                        </a>

                        <a class="text-decoration-none" href="">
                            <button type="button" class="btn btn-primary d-block ml-auto mr-auto mt-3">Aider Parcel</button>
                        </a>
                    </div>

                </div>
                <div class="col col-lg-4"></div>
            </div>
        </div>


        <?php
            echo $Aider->getUI()->getBootstrapScripts();
        ?>
        <script src="../assets/js/Dashboard.js"></script>
        <script>
            $(document).ready(function(){
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);

                getDashboardJS();
            });
        </script>
    </body>

</html>