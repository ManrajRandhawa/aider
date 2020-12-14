<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/aider/assets/php/Aider.php";

    $Aider = new Aider();


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="viewport-fit=cover, width=device-width, initial-scale=1, shrink-to-fit=no">

        <title><?php echo SITE_NAME; ?> | Food</title>

        <?php
            echo $Aider->getUI()->getBootstrapHead();
        ?>
    </head>

    <body class="bg-light">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <input type="text" placeholder="Search for exquisite delicacies..." class="input-group mt-3 p-3" style="height: 7vh; font-size: 13pt; border: 1px solid lightgray; border-radius: 5px; outline: none;"/>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div id="restaurant-container">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <img class="card-img-top" src="../assets/images/food-img.jpg" />
                                    <div class="card-body">
                                        <h4 class="card-title text-primary">Restaurant #1</h4>
                                        <h6>This is a description on the restaurant.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <img class="card-img-top" src="../assets/images/food-img.jpg" />
                                    <div class="card-body">
                                        <h4 class="card-title text-primary">Restaurant #2</h4>
                                        <h6>This is a description on the restaurant.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <img class="card-img-top" src="../assets/images/food-img.jpg" />
                                    <div class="card-body">
                                        <h4 class="card-title text-primary">Restaurant #3</h4>
                                        <h6>This is a description on the restaurant.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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