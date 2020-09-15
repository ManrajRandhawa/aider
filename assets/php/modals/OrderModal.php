<?php


class OrderModal {

    function getOrderDetailsByID($orderType, $id, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $tableName = "";

        if($orderType === "PARCEL") {
            $tableName = "aider_transaction_parcel";
        } elseif($orderType === "FOOD") {
            $tableName = "aider_transaction_food";
        } elseif($orderType === "DRIVER") {
            $tableName = "aider_transaction_driver";
        }

        $sql = "SELECT * FROM `$tableName` WHERE ID=$id";
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

        $statement->close();
        $connection->close();

        return $response;
    }

    function getSortingOrderDetailsByID($id, $orderType, $info) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM `aider_transaction_sorting` WHERE Transaction_ID=$id AND Transaction_Type='$orderType'";
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

        $statement->close();
        $connection->close();

        return $response;
    }

    function getOrderDetails($orderType, $id) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $tableName = "";
        $dataArray = "";

        if($orderType === "PARCEL") {
            $tableName = "aider_transaction_parcel";
        } elseif($orderType === "FOOD") {
            $tableName = "aider_transaction_food";
        } elseif($orderType === "DRIVER") {
            $tableName = "aider_transaction_driver";
        }

        $sql = "SELECT * FROM $tableName WHERE ID=$id";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {

                $pickUpLocCoords = $this->getCoordinates($row['Pickup_Location']);
                $dropOffLocCoords = $this->getCoordinates($row['Dropoff_Location']);

                $drivingDistance = $this->getDrivingDistance(
                    $pickUpLocCoords['lat'],
                    $dropOffLocCoords['lat'],
                    $pickUpLocCoords['lng'],
                    $dropOffLocCoords['lng']);

                if($orderType === "PARCEL") {
                    $dataArray = array(
                        $row['Customer_ID'],
                        $row['Pickup_Location'],
                        $row['Dropoff_Location'],
                        $row['Pickup_Details_Name'],
                        $row['Pickup_Details_PhoneNum'],
                        $row['Dropoff_Details_Name'],
                        $row['Dropoff_Details_PhoneNum'],
                        $row['Pickup_Datetime'],
                        $row['Price'],
                        $row['Transaction_Datetime'],
                        $drivingDistance['distance'],
                        $drivingDistance['time']
                    );
                } elseif($orderType === "FOOD") {
                    $dataArray = array(
                        $row['Customer_ID'],
                        $row['Merchant_ID'],
                        $row['Pickup_Location'],
                        $row['Dropoff_Location'],
                        $row['Pickup_Details_Name'],
                        $row['Pickup_Details_PhoneNum'],
                        $row['Dropoff_Details_Name'],
                        $row['Dropoff_Details_PhoneNum'],
                        $row['Pickup_Datetime'],
                        $row['Price'],
                        $row['Transaction_Datetime'],
                        $drivingDistance['distance'],
                        $drivingDistance['time']
                    );
                } elseif($orderType === "DRIVER") {
                    $dataArray = array(
                        $row['Customer_ID'],
                        $row['Pickup_Location'],
                        $row['Dropoff_Location'],
                        $row['Price'],
                        $row['Transaction_Datetime'],
                        $drivingDistance['distance'],
                        $drivingDistance['time']
                    );
                }


            }

            $response['data'] = $dataArray;
        } else {
            $response['error'] = true;
            $response['message'] = "There was an error while trying to connect to the database.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function getOrdersThatRequireARider($riderID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_transaction_sorting WHERE (`Transaction_Status`='FINDING-RIDER') AND (`Transaction_Type`='PARCEL' OR `Transaction_Type`='FOOD') AND (Rider_Denied_List NOT LIKE '%" . $riderID . "%') LIMIT 1";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {
                $response['data'] = array(
                    $row['Transaction_ID'],
                    $row["Transaction_Type"],
                    $row['Transaction_Datetime']
                );
            }
        } else {
            $response['error'] = true;
            $response['message'] = "No results found.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }

    function getTeamOrdersThatRequireARider($teamID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $sql = "SELECT * FROM aider_transaction_sorting WHERE (`Transaction_Status`='FINDING-RIDER') AND (`Transaction_Type`='DRIVER') AND (Rider_Denied_List NOT LIKE '%" . $teamID . "%') LIMIT 1";
        $statement = $connection->query($sql);

        if($statement->num_rows > 0) {
            $response['error'] = false;
            while($row = $statement->fetch_assoc()) {
                $response['data'] = array(
                    $row['Transaction_ID'],
                    $row["Transaction_Type"],
                    $row['Transaction_Datetime']
                );
            }
        } else {
            $response['error'] = true;
            $response['message'] = "No results found.";
        }

        $statement->close();
        $connection->close();

        return $response;
    }


    function addRiderToDeniedList($OrderType, $OrderID, $RiderID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $previousDeniedList = $this->getOrderDetailsByID($OrderType, $OrderID, "Rider_Denied_List");

        $deniedList = "";
        $tableName = null;
        $riderExists = false;

        if(!$previousDeniedList['error']) {
            $deniedList = $previousDeniedList['data'];
        }

        $riderDeniedList = explode(',', $deniedList);
        foreach($riderDeniedList as $riderDenied) {
            $riderDenied = trim($riderDenied);

            if($riderDenied === $RiderID) {
                $riderExists = true;
            }
        }

        if(!$riderExists) {
            if(empty($deniedList) || $deniedList === null) {
                $deniedList = $RiderID;
            } else {
                $deniedList .= "," . $RiderID;
            }

            $columnName = "Rider_Denied_List";

            if($OrderType === "PARCEL") {
                $tableName = "aider_transaction_parcel";
            } elseif($OrderType === "FOOD") {
                $tableName = "aider_transaction_food";
            }

            $sql = "UPDATE $tableName SET $columnName = '$deniedList' WHERE ID = $OrderID";

            $statement = $connection->query($sql);

            if($statement) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }
        } else {
            $response['error'] = false;
        }

        $connection->close();

        return $response;
    }

    function addTeamToDeniedList($OrderType, $OrderID, $TeamID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $previousDeniedList = $this->getOrderDetailsByID($OrderType, $OrderID, "Team_Denied_List");

        $deniedList = "";
        $tableName = null;
        $teamExists = false;

        if(!$previousDeniedList['error']) {
            $deniedList = $previousDeniedList['data'];
        }

        $riderDeniedList = explode(',', $deniedList);
        foreach($riderDeniedList as $riderDenied) {
            $riderDenied = trim($riderDenied);

            if($riderDenied === $TeamID) {
                $teamExists = true;
            }
        }

        if(!$teamExists) {
            if(empty($deniedList) || $deniedList === null) {
                $deniedList = $TeamID;
            } else {
                $deniedList .= "," . $TeamID;
            }

            $tableName = "aider_transaction_driver";
            $columnName = "Team_Denied_List";

            $sql = "UPDATE $tableName SET $columnName = '$deniedList' WHERE ID = $OrderID";

            $statement = $connection->query($sql);

            if($statement) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }
        } else {
            $response['error'] = false;
        }

        $connection->close();

        return $response;
    }

    function addRiderToSortingDeniedList($OrderType, $OrderID, $RiderID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $previousDeniedList = $this->getSortingOrderDetailsByID($OrderID, $OrderType, "Rider_Denied_List");

        $deniedList = "";
        $tableName = null;
        $riderExists = false;

        if(!$previousDeniedList['error']) {
            $deniedList = $previousDeniedList['data'];
        }

        $riderDeniedList = explode(',', $deniedList);
        foreach($riderDeniedList as $riderDenied) {
            $riderDenied = trim($riderDenied);

            if($riderDenied === $RiderID) {
                $riderExists = true;
            }
        }

        if(!$riderExists) {
            if(empty($deniedList) || $deniedList === null) {
                $deniedList = $RiderID;
            } else {
                $deniedList .= "," . $RiderID;
            }

            $sql = "UPDATE aider_transaction_sorting SET Rider_Denied_List = '$deniedList' WHERE Transaction_ID = $OrderID AND Transaction_Type = '$OrderType'";

            $statement = $connection->query($sql);

            if($statement) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }
        } else {
            $response['error'] = false;
        }

        $connection->close();

        return $response;
    }

    function addTeamToSortingDeniedList($OrderType, $OrderID, $Team_ID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $previousDeniedList = $this->getSortingOrderDetailsByID($OrderID, $OrderType, "Rider_Denied_List");

        $deniedList = "";
        $tableName = null;
        $riderExists = false;

        if(!$previousDeniedList['error']) {
            $deniedList = $previousDeniedList['data'];
        }

        $riderDeniedList = explode(',', $deniedList);
        foreach($riderDeniedList as $riderDenied) {
            $riderDenied = trim($riderDenied);

            if($riderDenied === $Team_ID) {
                $riderExists = true;
            }
        }

        if(!$riderExists) {
            if(empty($deniedList) || $deniedList === null) {
                $deniedList = $Team_ID;
            } else {
                $deniedList .= "," . $Team_ID;
            }

            $sql = "UPDATE aider_transaction_sorting SET Rider_Denied_List = '$deniedList' WHERE Transaction_ID = $OrderID AND Transaction_Type = '$OrderType'";

            $statement = $connection->query($sql);

            if($statement) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }
        } else {
            $response['error'] = false;
        }

        $connection->close();

        return $response;
    }

    function acceptOrder($OrderType, $OrderID, $Rider_ID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $tableName = "";
        $columnNameOne = "Rider_ID";

        if($OrderType === "PARCEL") {
            $tableName = "aider_transaction_parcel";
        } elseif($OrderType === "FOOD") {
            $tableName = "aider_transaction_food";
        }

        $sql = "UPDATE $tableName SET $columnNameOne = " . intval($Rider_ID). ", `Status` = 'RIDER-FOUND' WHERE ID = " . intval($OrderID);

        $statement = $connection->query($sql);

        if($statement) {
            $sqlSort = "UPDATE aider_transaction_sorting SET `Transaction_Status` = 'RIDER-FOUND' WHERE Transaction_ID = " . intval($OrderID) . " AND Transaction_Type = '$OrderType'";
            $statementSort = $connection->query($sqlSort);

            if($statementSort) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }
        } else {
            $response['error'] = true;
        }

        $connection->close();

        return $response;
    }

    function acceptTeamOrder($OrderType, $OrderID, $Team_ID) {
        $DatabaseHandler = new DatabaseHandler();
        $connection = $DatabaseHandler->getMySQLiConnection();

        $tableName = "aider_transaction_driver";
        $columnNameOne = "Team_ID";

        $sql = "UPDATE $tableName SET $columnNameOne = " . intval($Team_ID). ", `Status` = 'RIDER-FOUND' WHERE ID = " . intval($OrderID);

        $statement = $connection->query($sql);

        if($statement) {
            $sqlSort = "UPDATE aider_transaction_sorting SET `Transaction_Status` = 'RIDER-FOUND' WHERE Transaction_ID = " . intval($OrderID) . " AND Transaction_Type = '$OrderType'";
            $statementSort = $connection->query($sqlSort);

            if($statementSort) {
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }
        } else {
            $response['error'] = true;
        }

        $connection->close();

        return $response;
    }

    function getCoordinates($address) {
        $address = urlencode($address);
        $url = "https://maps.google.com/maps/api/geocode/json?key=" . MAP_API_KEY . "&address=$address&sensor=false&region=Malaysia";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response);
        $status = $response_a->status;

        if ($status == 'ZERO_RESULTS') {
            return false;
        }
        else {
            return array('lat' => $response_a->results[0]->geometry->location->lat, 'lng' => $long = $response_a->results[0]->geometry->location->lng);
        }
    }

    function getDrivingDistance($lat1, $lat2, $lng1, $lng2) {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?key=" . MAP_API_KEY . "&origins=".$lat1.",".$lng1."&destinations=".$lat2.",".$lng2."&mode=driving&language=en-MY";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

        return array('distance' => $dist, 'time' => $time);
    }

}