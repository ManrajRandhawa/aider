<?php

    include "php/Aider.php";

    $Whiz = new Aider();


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php echo SITE_NAME; ?> | Home</title>

        <?php
            echo $Whiz->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="container-fluid bg-light">

        <!-- Main Content -->
        <div class="row">
            <div class="col col-lg-4"></div>
            <div class="col-12 col-lg-4 bg-white rounded mt-5">
                <div class="mb-5">
                    <a href="">
                        <button type="button" class="btn btn-primary d-block ml-auto mr-auto mt-5">Aider Food</button>
                    </a>

                    <a href="">
                        <button type="button" class="btn btn-primary d-block ml-auto mr-auto mt-3">Aider Parcel</button>
                    </a>
                </div>

            </div>
            <div class="col col-lg-4"></div>
        </div>


        <?php
            echo $Whiz->getUI()->getBootstrapScripts();
        ?>
        <script>
            $(document).ready(function(){
                setTimeout(function() {
                    $(".alert").alert('close');
                }, 2000);
            });
        </script>
    </body>

</html>