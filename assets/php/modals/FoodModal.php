<?php


class FoodModal {

    function addRestaurant($name, $cat, $email, $hp, $tele_user, $loc, $img) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = 'INSERT INTO aider_user_merchant(Name, Categories, Email_Address, Phone_Number, Telegram_Username, Location, Featured_Image) VALUES ("' . $name . '", "' . $cat . '", "' . $email . '", "' . $hp . '", "' . $tele_user . '", "' . $loc . '", "' . $img . '")';
        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an issue while adding the restaurant: " . $connection->error;
        }

        return $response;
    }

    function addMenuItem($merchant_id, $itemName, $itemCat, $itemDesc, $itemPrice, $img) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = 'INSERT INTO aider_merchant_product(Merchant_ID, Name, Categories, Description, Price, Featured_Image) VALUES ("' . intval($merchant_id) . '", "' . $itemName . '", "' . $itemCat . '", "' . $itemDesc . '", "' . floatval($itemPrice) . '", "' . $img . '")';
        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an issue while adding the item to the menu: " . $connection->error;
        }

        return $response;
    }

    function addCategory($type, $categoryName) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($type === "R") {
            $sql = 'INSERT INTO aider_merchant_r_categories(Category_Name) VALUES ("' . $categoryName . '")';
        } else {
            $sql = 'INSERT INTO aider_merchant_m_categories(Category_Name) VALUES ("' . $categoryName . '")';
        }

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an issue while adding the category: " . $connection->error;
        }

        return $response;
    }

    function getCategoryData($type, $categoryID, $data) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($type === "R") {
            $sql = 'SELECT * FROM aider_merchant_r_categories WHERE ID=' . intval($categoryID);
        } else {
            $sql = 'SELECT * FROM aider_merchant_m_categories WHERE ID=' . intval($categoryID);
        }

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {
                $response['data'] = $row[$data];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to connect to the database.";
        }

        return $response;
    }

    function getCategoryList($type) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($type === "R") {
            $sql = 'SELECT * FROM aider_merchant_r_categories';
        } else {
            $sql = 'SELECT * FROM aider_merchant_m_categories';
        }

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            $response['data'] = "";
            while($row = $statement->fetch_assoc()) {
                $response['data'] .= "
                <span class=\"badge badge-pill badge-dark mt-2\">
                                <span>" . $row['Category_Name'] . "</span>
                                <a id='" . $row['ID'] . "' onclick=\"deleteCategory('$type', this.id);\"><i class=\"far fa-times-circle fa-lg\"></i></a>
                            </span>
                ";
            }
        } else {
            $response['error'] = false;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Any categories added will appear here.</h6>
                    </div>";
        }

        return $response;
    }

    function deleteCategory($type, $categoryID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($type === "R") {
            $sql = 'DELETE FROM aider_merchant_r_categories WHERE ID=' . intval($categoryID);
        } else {
            $sql = 'DELETE FROM aider_merchant_m_categories WHERE ID=' . intval($categoryID);
        }

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an issue while deleting the category: " . $connection->error;
        }

        return $response;
    }

    function getCategoryForSelect($type) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if($type === "R") {
            $sql = 'SELECT * FROM aider_merchant_r_categories';
        } else {
            $sql = 'SELECT * FROM aider_merchant_m_categories';
        }

        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            $response['data'] = "";
            while($row = $statement->fetch_assoc()) {
                $response['data'] .= "<option value='" . $row['Category_Name'] . "'>" . $row['Category_Name'] . "</option>";
            }
        } else {
            $response['error'] = false;
            $response['data'] = "<option value='Uncategorized' selected>Uncategorized</option>";
        }

        return $response;
    }

    function updateRestaurant($id, $cat, $name, $email, $hp, $tele_user, $loc, $img) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if(empty($img)) {
            $sql = 'UPDATE aider_user_merchant SET Name="' . $name . '", Categories="' . $cat . '", Email_Address="' . $email . '", Phone_Number="' . $hp . '", Telegram_Username="' . $tele_user . '", Location="' . $loc . '" WHERE ID=' . intval($id);
        } else {
            $sql = 'UPDATE aider_user_merchant SET Name="' . $name . '", Categories="' . $cat . '", Email_Address="' . $email . '", Phone_Number="' . $hp . '", Telegram_Username="' . $tele_user . '", Location="' . $loc . '", Featured_Image="' . $img . '" WHERE ID=' . intval($id);
        }

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an issue while updating the restaurant: " . $connection->error;
        }

        return $response;
    }

    function updateMenuItem($id, $itemName, $cat, $itemDesc, $itemPrice, $img) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        if(empty($img)) {
            $sql = 'UPDATE aider_merchant_product SET Name="' . $itemName . '", Categories="' . $cat . '", Description="' . $itemDesc . '", Price=' . $itemPrice . ' WHERE ID=' . intval($id);
        } else {
            $sql = 'UPDATE aider_merchant_product SET Name="' . $itemName . '", Categories="' . $cat . '", Description="' . $itemDesc . '", Price=' . $itemPrice . ', Featured_Image="' . $img . '" WHERE ID=' . intval($id);
        }

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an issue while updating the menu: " . $connection->error;
        }

        return $response;
    }

    function deleteRestaurant($id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "DELETE FROM aider_user_merchant WHERE ID=" . intval($id);

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an issue while deleting the restaurant: " . $connection->error;
        }

        return $response;
    }

    function deleteMenuItem($id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "DELETE FROM aider_merchant_product WHERE ID=" . intval($id);

        $statement = $connection->query($sql);

        if($statement) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an issue while deleting the item: " . $connection->error;
        }

        return $response;
    }

    function getMerchantInformationByID($id, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_merchant WHERE ID=$id";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {
                $response['data'] = $row[$info];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to connect to the database.";
        }

        return $response;
    }

    function getMerchantInformationByEmail($email, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_merchant WHERE Email_Address='" . $email . "'";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {
                $response['data'] = $row[$info];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to connect to the database.";
        }

        return $response;
    }

    function getRestaurantList() {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_merchant";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            $response['data'] = "";

            while($row = $statement->fetch_assoc()) {
                $response['data'] .= "
                <div class=\"col-6 mt-3\">
                        <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick='openRestaurant(this.id);'>
                            <div class=\"card\">
                                <div class=\"card-body\">
                                    <div class=\"row\">
                                        <div class=\"col-12\">
                                            <div class=\"row\">
                                                <div class='col-12 text-center'>
                                                    <h5 class=\"text-success text-center\">" . $row['Name'] . "</h5>
                                                    <h6 class=\"text-center text-dark mt-4\">" . $row['Location'] . "</h6>
                                                    
                                                    <span class=\"badge badge-pill badge-dark mt-2 text-center\">
                                                        <span class='text-center'>" . $row['Categories'] . "</span>
                                                    </span>
                                                    
                                                    <br/>
                                                    
                                                    <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick=\"editRestaurant(this.id);\">
                                                        <button class=\"btn btn-sm btn-outline-primary rounded-circle float-left\" style=\"padding: 5px 6px 4px 6px;\">
                                                            <i class=\"fas fa-edit\"></i>
                                                        </button>
                                                    </a>
                                                    <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick=\"deleteRestaurant(this.id);\">
                                                        <button class=\"btn btn-sm btn-outline-danger rounded-circle float-right\">
                                                            <i class=\"fas fa-trash\"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                ";
            }
        } else {
            $response['error'] = true;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Any restaurants added will appear here.</h6>
                    </div>";
        }

        return $response;
    }

    function searchRestaurantList($searchArgs) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_merchant WHERE (Name LIKE '%" . $searchArgs . "%') OR (Email_Address LIKE '%" . $searchArgs . "%') OR (Phone_Number LIKE '%" . $searchArgs . "%') LIMIT 5";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            $response['data'] = "";

            while($row = $statement->fetch_assoc()) {
                $response['data'] .= "
                <div class=\"col-6 mt-3\">
                        <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick='openRestaurant(this.id);'>
                            <div class=\"card\">
                                <div class=\"card-body\">
                                    <div class=\"row\">
                                        <div class=\"col-12\">
                                            <div class=\"row\">
                                                <div class='col-12 text-center'>
                                                    <h5 class=\"text-success text-center\">" . $row['Name'] . "</h5>
                                                    <h6 class=\"text-center text-dark mt-4\">" . $row['Location'] . "</h6>
                                                    
                                                    <span class=\"badge badge-pill badge-dark mt-2 text-center\">
                                                        <span class='text-center'>" . $row['Categories'] . "</span>
                                                    </span>
                                                    
                                                    <br/>
                                                    
                                                    <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick=\"editRestaurant(this.id);\">
                                                        <button class=\"btn btn-sm btn-outline-primary rounded-circle float-left\" style=\"padding: 5px 6px 4px 6px;\">
                                                            <i class=\"fas fa-edit\"></i>
                                                        </button>
                                                    </a>
                                                    <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick=\"deleteRestaurant(this.id);\">
                                                        <button class=\"btn btn-sm btn-outline-danger rounded-circle float-right\">
                                                            <i class=\"fas fa-trash\"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                ";
            }
        } else {
            $response['error'] = true;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Refine your search to get better results.</h6>
                    </div>";
        }

        return $response;
    }

    function getMenuList($id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_merchant_product WHERE Merchant_ID=" . intval($id);
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            $response['data'] = "";

            while($row = $statement->fetch_assoc()) {
                $response['data'] .= "
                <div class=\"col-6 mt-2\">
                            <div class=\"card\">
                                <div class=\"card-body text-center\">
                                    <img src='data:image/jpeg;base64," . base64_encode($row['Featured_Image']) . "' style=\"width: 40%;\"/>

                                    <h6 class=\"mt-3\">" . $row['Name'] . "</h6>
                                    <h6 class=\"mt-2 font-weight-bold\">RM " . $row['Price'] . "</h6>
                                    
                                    <span class=\"badge badge-pill badge-dark mt-2 text-center\">
                                        <span class='text-center'>" . $row['Categories'] . "</span>
                                    </span>
                                                    
                                    <br/>

                                    <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick=\"editItemMenu(this.id);\">
                                        <button class=\"btn btn-sm btn-outline-primary rounded-circle float-left\" style=\"padding: 5px 6px 4px 6px;\">
                                            <i class=\"fas fa-edit\"></i>
                                        </button>
                                    </a>
                                    <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick=\"deleteItemMenu(this.id);\">
                                        <button class=\"btn btn-sm btn-outline-danger rounded-circle float-right\">
                                            <i class=\"fas fa-trash\"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                ";
            }
        } else {
            $response['error'] = true;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Any restaurants added will appear here.</h6>
                    </div>";
        }

        return $response;
    }

    function searchMenuList($id, $searchArgs) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_merchant_product WHERE Merchant_ID=" . intval($id) . " AND Name LIKE '%" . $searchArgs . "%'";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            $response['data'] = "";

            while($row = $statement->fetch_assoc()) {
                $response['data'] .= "
                <div class=\"col-6 mt-2\">
                            <div class=\"card\">
                                <div class=\"card-body text-center\">
                                    <img src='data:image/jpeg;base64," . base64_encode($row['Featured_Image']) . "' style=\"width: 40%;\"/>

                                    <h6 class=\"mt-3\">" . $row['Name'] . "</h6>
                                    <h6 class=\"mt-2 font-weight-bold\">RM " . $row['Price'] . "</h6>

                                    <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick=\"editItemMenu(this.id);\">
                                        <button class=\"btn btn-sm btn-outline-primary rounded-circle float-left\" style=\"padding: 5px 6px 4px 6px;\">
                                            <i class=\"fas fa-edit\"></i>
                                        </button>
                                    </a>
                                    <a class=\"text-decoration-none\" id='" . $row['ID'] . "' onclick=\"deleteItemMenu(this.id);\">
                                        <button class=\"btn btn-sm btn-outline-danger rounded-circle float-right\">
                                            <i class=\"fas fa-trash\"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                ";
            }
        } else {
            $response['error'] = true;
            $response['data'] = "<div class=\"col-12 text-center mt-5\">
                        <i class=\"fas fa-scroll fa-4x\" style=\"color: #DCDCDC;\"></i>
                        <h6 class=\"font-weight-bold mt-4\">Nothing here yet!</h6>
                        <h6 class=\"text-black-50\">Refine your search to get better results.</h6>
                    </div>";
        }

        return $response;
    }

    function getRestaurantDetails($id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_user_merchant WHERE ID=" . intval($id);
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;

            while($row = $statement->fetch_assoc()) {
                $response['data'] = array(
                    $row['ID'],
                    $row['Name'],
                    $row['Email_Address'],
                    $row['Phone_Number'],
                    $row['Telegram_Username'],
                    $row['Location']
                );
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to connect to the database: " . $connection->error;
        }

        return $response;
    }

    function getMenuItemDetails($id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_merchant_product WHERE ID=" . intval($id);
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;

            while($row = $statement->fetch_assoc()) {
                $response['data'] = array(
                    $row['ID'],
                    $row['Merchant_ID'],
                    $row['Name'],
                    $row['Description'],
                    $row['Price']
                );
            }
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to connect to the database: " . $connection->error;
        }

        return $response;
    }

}