<?php


class PromoModal {

    function getPromos() {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $promoHTML = "";

        $sql = "SELECT * FROM aider_promo";

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            while($row = $statement->fetch_assoc()) {
                $imgHolder = "";

                if(is_null($row['Promo_Image_Name']) || empty($row['Promo_Image_Name'])) {
                    $imgHolder = "";
                } else {
                    $imgHolder = "<img class=\"card-img\" src='../assets/images/promos/" . $row['Promo_Image_Name']. "' />";
                }

                if(is_null($row['Promo_URL']) || empty($row['Promo_URL'])) {
                    $promoHTML .= "<div class=\"col-12\">
                            <div class=\"card mt-3 shadow-lg\">
                                " . $imgHolder . "
                                <div class=\"card-body\">
                                    <h5 class=\"card-title text-center p-0 m-0 text-dark\">" . $row['Promo_Title'] . "</h5>
                                    <hr/>
                                    <p class=\"card-text text-center mt-2 text-dark\">" . $row['Promo_Description'] . "</p>
                                </div>
                            </div>
                        </div>";
                } else {
                    $promoHTML .= "<div class=\"col-12\">
                            <a class='text-decoration-none' onclick=\"window.open('" . $row['Promo_URL'] . "', '_system');\">
                                <div class=\"card mt-3 shadow-lg\">
                                    " . $imgHolder . "
                                    <div class=\"card-body\">
                                        <h5 class=\"card-title text-center p-0 m-0 text-dark\">" . $row['Promo_Title'] . "</h5>
                                        <hr/>
                                        <p class=\"card-text text-center mt-2 text-dark\">" . $row['Promo_Description'] . "</p>
                                    </div>
                                </div>
                            </a>
                        </div>";
                }
            }

            return $promoHTML;
        } else {
            $promoHTML = "<div class='container mt-4 mb-5'>
                            <div class='row'>
                                <div class='col-1'></div>
                                <div class='col-10 text-center mt-5'>
                                    <i class='fas fa-scroll fa-4x' style='color: #DCDCDC;'></i>
                                    <h6 class='font-weight-bold mt-4'>No promotions as of now!</h6>
                                    <h6 class='text-black-50'>Any promotions available will appear here.</h6>
                                </div>
                                <div class='col-1'></div>
                            </div>
                        </div>";

            return $promoHTML;
        }
    }

    function addPromo($promoTitle, $promoUrl, $promoDesc, $promoImageName) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "INSERT INTO aider_promo(Promo_Title, Promo_URL, Promo_Description, Promo_Image_Name) VALUES (?,?,?,?)";

        $statement = $connection->prepare($sql);

        $statement->bind_param("ssss",$promoTitle, $promoUrl, $promoDesc, $promoImageName);

        if($statement->execute()) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while adding the promotion.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function getPromoList() {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_promo";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $num = 0;

            $response['error'] = false;

            $response['data'] = "";

            while($row = $statement->fetch_assoc()) {
                $num++;

                $response['data'] .= "<div class=\"col-6 mt-2\">
                            <div class=\"card\">
                                <div class=\"card-body\">
                                    <h5 class=\"card-title font-weight-bold text-success text-center\">" . $row['Promo_Title'] . "</h5>
                                    <hr class=\"my-2\"/>
                                    <h6 class=\"card-text text-center p-0 m-0 mb-3\">" . $row['Promo_Description'] . "</h6>
                                    <a href=\"#\" onclick='deletePromo(this.id);' class=\"btn btn-outline-danger d-block w-100\" id='" . $row['ID'] . "'><i class=\"far fa-trash-alt\"></i></a>
                                </div>
                            </div>
                        </div>";
            }
        } else {
            $response['error'] = true;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Any promotions added will appear here.</h6>
                    </div>";
        }

        return $response;
    }

    function deletePromo($promoID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $deleteTeamSQL = "DELETE FROM aider_promo WHERE ID=$promoID";
        $statementDeleteTeam = $connection->query($deleteTeamSQL);

        if($statementDeleteTeam) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
        }

        $statementDeleteTeam->close();
        $connection->close();

        return $response;
    }

}