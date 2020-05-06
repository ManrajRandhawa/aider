<?php


class DatabaseHandler {

    function getMySQLiConnection() {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if($connection->connect_error) {
            die(MSG_DB_CONNECT_ERROR);
        } else {
            return $connection;
        }
    }

}