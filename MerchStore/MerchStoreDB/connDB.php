<?php

global $conn, $hostName, $username, $pwd, $dbName;
if (!function_exists('connDB')) {
    function connDB()
    {
        /*
    $hostName = "merchstoredb.freehosting.dev";
    $username = "if0_42256352";
    $pwd = "39FNHV17OBf";
    $dbName = "MerchStoreDB";
    */
        $hostName = "localhost";
        $username = "root";
        $pwd = "";
        $dbName = "MerchStoreDB";

        $conn = mysqli_connect($hostName, $username, $pwd);
        if (mysqli_connect_errno()) {
            printf("Connection failed: %s\n ", mysqli_connect_error());
            exit();
        }
        if (mysqli_select_db($conn, $dbName)) {
            return $conn;
        } else {
            printf("Database '%s' does not exist.\n", $dbName);
            return $conn; // Return connection without database selected
        }
    }
}
